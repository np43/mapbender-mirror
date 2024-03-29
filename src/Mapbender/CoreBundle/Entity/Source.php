<?php
namespace Mapbender\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mapbender\CoreBundle\Component\Source\MutableHttpOriginInterface;

/**
 * Source entity
 *
 * @author Paul Schmidt
 *
 * @ORM\Entity
 * @ORM\Table(name="mb_core_source")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * ORM\DiscriminatorMap({"mb_core_source" = "Source"})
 * @ORM\HasLifecycleCallbacks()
 */
abstract class Source implements MutableHttpOriginInterface
{

    /** @deprecated only relevant client-side, and it doesn't even use the same string values there */
    const STATUS_OK = 'OK';
    /** @deprecated only relevant client-side, and it doesn't even use the same string values there */
    const STATUS_UNREACHABLE = 'UNREACHABLE';

    const TYPE_WMS = "WMS";
    const TYPE_WMTS = "WMTS";
    const TYPE_TMS = "TMS";

    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $title The source title
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @var string $alias The source alias
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $alias = "";

    /**
     * @var boolean $valid is a source valid
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $valid = false;

    /**
     * @var string $description The source description
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $type;
    
    public function __construct()
    {
    }

    /**
     * @return string
     */
    abstract public function getTypeLabel();

    /**
     * @return ArrayCollection|SourceInstance[]
     */
    abstract public function getInstances();

    /**
     * @return ArrayCollection|SourceItem[]
     */
    abstract public function getLayers();

    /**
     * Set id
     * @param integer $id source id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param  string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set alias
     *
     * @param  string $alias
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set valid
     *
     * @param  boolean $valid
     * @return $this
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid
     *
     * @return boolean
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Source status is a client-side runtime concept helping to visualize live network response errors.
     * It's meaningless server-side.
     *
     * @return string
     * @deprecated
     */
    final public function getStatus()
    {
        return self::STATUS_OK;
    }

    /**
     * Returns a Source as String
     *
     * @return String Source as String
     */
    public function __toString()
    {
        return (string) $this->id;
    }

    /**
     * Returns a source type
     *
     * @return String type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets source type.
     * Possible types available from Source::TYPE_*
     *
     * @param string $type Set type. Possible types available from Source::TYPE_*
     * @return $this
     */
    public function setType($type)
    {
        if ($type === self::TYPE_WMTS || $type === self::TYPE_WMS || $type === self::TYPE_TMS) {
            $this->type = $type;
        }

        return $this;
    }

    /**
     * Returns a manager type
     *
     * @return String a manager type
     */
    public function getManagertype()
    {
        return strtolower($this->getType());
    }

    /**
     * @return string
     */
    abstract public function getViewTemplate();

    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        if (!$this->type) {
            // Ancient db (Mapbender 3.0.4); amend missing value
            @trigger_error("WARNING: Missing type value on " . get_class($this) . "#{$this->id}, assuming WMS");
            $this->setType(self::TYPE_WMS);
        }
    }
}

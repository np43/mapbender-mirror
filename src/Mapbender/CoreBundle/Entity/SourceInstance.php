<?php
namespace Mapbender\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mapbender\CoreBundle\Component\SourceMetadata;

/**
 * @author Karim Malhas
 * @author Andriy Oblivantsev <andriy.oblivantsev@wheregroup.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="mb_core_sourceinstance")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * ORM\DiscriminatorMap({"mb_core_sourceinstance" = "SourceInstance"})
 */
abstract class SourceInstance
{
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
     * @ORM\ManyToOne(targetEntity="Layerset", inversedBy="instances", cascade={"refresh"})
     * @ORM\JoinColumn(name="layerset", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $layerset;

    /**
     * @var integer $weight The sorting weight for display
     * @ORM\Column(type="integer")
     */
    protected $weight;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $enabled = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $basesource = false;

    /**
     *
     * @var Source a source
     */
    protected $source;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param String $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Returns a source type
     *
     * @return String type
     */
    public function getType()
    {
        return $this->source->getType();
    }

    /**
     * Returns a manager type
     *
     * @return String a manager type
     */
    public function getManagertype()
    {
        return $this->source->getManagertype();
    }

    /**
     * Sets a weight
     *
     * @param integer $weight
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Returns a weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Sets the layerset
     *
     * @param  Layerset       $layerset Layerset
     * @return $this
     */
    public function setLayerset(Layerset $layerset)
    {
        $this->layerset = $layerset;

        return $this;
    }

    /**
     * Returns the layerset
     * @return Layerset
     */
    public function getLayerset()
    {
        return $this->layerset;
    }

    /**
     * Sets an enabled
     *
     * @param  integer        $enabled
     * @return SourceInstance SourceInstance
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Returns an enabled
     *
     * @return integer
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Sets base source
     *
     * @param  boolean $baseSource
     * @return $this
     */
    public function setBasesource($baseSource)
    {
        $this->basesource = $baseSource;

        return $this;
    }

    /**
     * Returns a basesource
     *
     * @return bool
     */
    public function isBasesource()
    {
        return $this->basesource;
    }

    /**
     * Sets source
     *
     * @param Source $source Source object
     * @return SourceInstance
     */
    abstract public function setSource($source);

    /**
     * Returns source
     *
     * @return \Mapbender\WmsBundle\Entity\WmsSource|Source
     */
    abstract public function getSource();

    /**
     *
     * @return SourceMetadata|null
     */
    abstract public function getMetadata();

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getId();
    }
}

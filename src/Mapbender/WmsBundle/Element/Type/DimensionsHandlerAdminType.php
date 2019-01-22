<?php

namespace Mapbender\WmsBundle\Element\Type;

use Mapbender\CoreBundle\Component\ExtendedCollection;
use Mapbender\CoreBundle\Element\Type\TargetElementType;
use Mapbender\CoreBundle\Entity\Application;
use Mapbender\CoreBundle\Entity\Layerset;
use Mapbender\CoreBundle\Utils\ArrayUtil;
use Mapbender\WmsBundle\Component\DimensionInst;
use Mapbender\WmsBundle\Entity\WmsInstance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DimensionsHandlerAdminType
 * @package Mapbender\WmsBundle\Element\Type
 */
class DimensionsHandlerAdminType extends AbstractType implements ExtendedCollection
{
    public $hasSubForm = true;

    /**
     * @return bool
     */
    public function isSubForm()
    {
        return $this->hasSubForm;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dimensionshandler';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'application' => null,
            'element' => null,
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Application $application */
        $application = $options["application"];
        $element = $options["element"];
        $dimensions = array();
        if ($element !== null && $element->getId() !== null) {
            $configuration = $element->getConfiguration();
            if (!empty($configuration['target'])) {
                $mapId = $configuration['target'];
                $dimensions = $this->collectDimensions($application, $mapId);
            }
        }
        $builder
            ->add('tooltip', TextType::class, array(
                'required' => false,
            ))
            ->add('target', TargetElementType::class, array(
                'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                'application' => $options['application'],
                'property_path' => '[target]',
                'required' => false,
            ))
        ;
        if ($dimensions) {
            $builder
                ->add('dimensionsets', CollectionType::class, array(
                    'type' => new DimensionSetAdminType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'auto_initialize' => false,
                    'options' => array(
                        'dimensions' => $dimensions,
                    ),
                ))
            ;
        }
    }

    /**
     * @param Application $application
     * @param int $mapId
     * @return DimensionInst[]
     */
    protected function collectDimensions($application, $mapId)
    {
        $dimensions = array();
        foreach ($this->getMapLayersets($application, $mapId) as $layerset) {
            foreach ($layerset->getInstances() as $instance) {
                if ($instance instanceof WmsInstance) {
                    foreach ($instance->getDimensions() ?: array() as $ix => $dimension) {
                        /** @var DimensionInst $dimension */
                        $key = "{$instance->getId()}-{$ix}";
                        $dimension->id = $key;
                        $dimensions[$key] = $dimension;
                    }
                }
            }
        }
        return $dimensions;
    }

    /**
     * @param Application $application
     * @param int|string $elementId
     * @return mixed[]
     */
    protected function getElementConfiguration($application, $elementId)
    {
        foreach ($application->getElements() as $element) {
            if (strval($element->getId()) === strval($elementId)) {
                return $element->getConfiguration();
            }
        }
        throw new \RuntimeException("No Element with id " . var_export($elementId, true));
    }

    /**
     * @param Application $application
     * @param int|string $mapId
     * @return Layerset[]
     */
    protected function getMapLayersets($application, $mapId)
    {
        $mapConfig = $this->getElementConfiguration($application, $mapId);
        $layersetIds = array_map('strval', ArrayUtil::getDefault($mapConfig, 'layersets', array()));
        $layersets = array();
        foreach ($application->getLayersets() as $layerset) {
            if (in_array(strval($layerset->getId()), $layersetIds)) {
                $layersets[] = $layerset;
            }
        }
        return $layersets;
    }
}

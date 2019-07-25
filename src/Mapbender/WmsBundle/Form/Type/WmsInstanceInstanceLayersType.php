<?php

namespace Mapbender\WmsBundle\Form\Type;

use Mapbender\WmsBundle\Entity\WmsInstance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * WmsInstanceInstanceLayersType class
 */
class WmsInstanceInstanceLayersType extends AbstractType
{
    /** @var bool */
    protected $exposeLayerOrder;

    /**
     * @param bool $exposeLayerOrder to expose layer order controls; from parameter mapbender.preview.layer_order.wms
     */
    public function __construct($exposeLayerOrder = false)
    {
        $this->exposeLayerOrder = $exposeLayerOrder;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'wmsinstanceinstancelayers';
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var WmsInstance $wmsinstance */
        $wmsinstance = $options["data"];
        $arr = $wmsinstance->getSource()->getGetMap()->getFormats() !== null ?
            $wmsinstance->getSource()->getGetMap()->getFormats() : array();
        $formats = array();
        foreach ($arr as $value) {
            $formats[$value] = $value;
        }
        $builder->add('title', 'text', array(
                'required' => true))
            ->add('format', 'choice', array(
                'choices' => $formats,
                'required' => true));
        $gfi = $wmsinstance->getSource()->getGetFeatureInfo();
        $arr = $gfi && $gfi->getFormats() !== null ? $gfi->getFormats() : array();
        $formats_gfi = array();
        foreach ($arr as $value) {
            $formats_gfi[$value] = $value;
        }
        $builder->add('infoformat', 'choice', array(
            'choices' => $formats_gfi,
            'required' => false));
        $arr = $wmsinstance->getSource()->getExceptionFormats() !== null ?
            $wmsinstance->getSource()->getExceptionFormats() : array();
        $formats_exc = array();
        foreach ($arr as $value) {
            $formats_exc[$value] = $value;
        }
        $opacity = array();
        foreach (range(0, 100, 10) as $value) {
            $opacity[$value] = $value;
        }
        $builder->add('exceptionformat', 'choice', array(
                'choices' => $formats_exc,
                'required' => false))
            ->add('basesource', 'checkbox', array(
                'required' => false,
                'label' => 'mb.wms.wmsloader.repo.instance.label.basesource',
            ))
            ->add('visible', 'checkbox', array(
                'required' => false,
                'label' => 'mb.wms.wmsloader.repo.instance.label.visible',
            ))
            ->add('proxy', 'checkbox', array(
                'required' => false,
                'label' => 'mb.wms.wmsloader.repo.instance.label.proxy',
            ))
            ->add('opacity', 'choice', array(
                'choices' => $opacity,
                'required' => true))
            ->add('transparency', 'checkbox', array(
                'required' => false,
                'label' => 'mb.wms.wmsloader.repo.instance.label.transparency',
            ))
            ->add('tiled', 'checkbox', array(
                'required' => false,
                'label' => 'mb.wms.wmsloader.repo.instance.label.tiled',
            ))
            ->add('ratio', 'number', array(
                'required' => false,
                'precision' => 2,
                'label' => 'mb.wms.wmsloader.repo.instance.label.ratio',
            ))
            ->add('buffer', 'integer', array(
                'required' => false,
                'label' => 'mb.wms.wmsloader.repo.instance.label.buffer',
            ))
            ->add('dimensions', 'collection', array(
                'required' => false,
                'type' => new DimensionInstType(),
                'allow_add' => false,
                'allow_delete' => false,
            ))
            ->add('vendorspecifics', 'collection', array(
                'required' => false,
                'type' => new VendorSpecificType(),
                'allow_add' => true,
                'allow_delete' => true,
            ))
            ->add('layers', 'collection', array(
                'type' => new WmsInstanceLayerType(),
                'options' => array(
                    'data_class' => 'Mapbender\WmsBundle\Entity\WmsInstanceLayer',
                ),
            ))
        ;

        if ($this->exposeLayerOrder) {
            $layerOrderChoices = array();
            foreach (WmsInstance::validLayerOrderChoices() as $validChoice) {
                $translationKey = "mb.wms.wmsloader.repo.instance.label.layerOrder.$validChoice";
                $layerOrderChoices[$validChoice] = $translationKey;
            }
            $builder->add('layerOrder', 'choice', array(
                'choices' => $layerOrderChoices,
                'required' => true,
                'auto_initialize' => true,
            ));
        }
    }
}

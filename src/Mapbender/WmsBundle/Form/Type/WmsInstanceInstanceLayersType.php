<?php

namespace Mapbender\WmsBundle\Form\Type;

use Mapbender\WmsBundle\Entity\WmsInstance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class WmsInstanceInstanceLayersType
 * @package Mapbender\WmsBundle\Form\Type
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
     * @return string
     */
    public function getName()
    {
        return 'wmsinstanceinstancelayers';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'available_templates' => array(),
                'gfg' => function (FormInterface $form) {
                    $data = $form->getData()->getWmssourcelayer();
                    return true;
                }
            )
        );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $wmsinstance = $options["data"];
        $arr = $wmsinstance->getSource()->getGetMap()->getFormats() !== null ?
            $wmsinstance->getSource()->getGetMap()->getFormats() : array();
        $formats = array();
        foreach ($arr as $value) {
            $formats[$value] = $value;
        }
        $builder->add('title', TextType::class, array(
                'required' => true))
            ->add('format', ChoiceType::class, array(
                'choices' => $formats,
                'required' => true));
        $gfi = $wmsinstance->getSource()->getGetFeatureInfo();
        $arr = $gfi && $gfi->getFormats() !== null ? $gfi->getFormats() : array();
        $formats_gfi = array();
        foreach ($arr as $value) {
            $formats_gfi[$value] = $value;
        }
        $builder->add('infoformat', ChoiceType::class, array(
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
        $builder->add('exceptionformat', ChoiceType::class, array(
                'choices' => $formats_exc,
                'required' => false))
            ->add('basesource', CheckboxType::class, array(
                'required' => false))
            ->add('visible', CheckboxType::class, array(
                'required' => false))
            ->add('proxy', CheckboxType::class, array(
                'required' => false))
            ->add('opacity', ChoiceType::class, array(
                'choices' => $opacity,
                'required' => true))
            ->add('transparency', CheckboxType::class, array(
                'required' => false))
            ->add('tiled', CheckboxType::class, array(
                'required' => false))
            ->add('ratio', NumberType::class, array(
                'required' => false,
                'precision' => 2,
                'label' => 'mb.wms.wmsloader.repo.instance.label.ratio',
            ))
            ->add('buffer', IntegerType::class, array(
                'required' => false,
                'label' => 'mb.wms.wmsloader.repo.instance.label.buffer',
            ))
            ->add('dimensions', CollectionType::class, array(
                'required' => false,
                'type' => new DimensionInstType(),
                'allow_add' => false,
                'allow_delete' => false,
            ))
            ->add('vendorspecifics', CollectionType::class, array(
                'required' => false,
                'type' => new VendorSpecificType(),
                'allow_add' => true,
                'allow_delete' => true,
            ))
            ->add('layers', CollectionType::class, array(
                'type' => new WmsInstanceLayerType(),
                'options' => array(
                    'data_class' => 'Mapbender\WmsBundle\Entity\WmsInstanceLayer',
                    'num_layers' => count($wmsinstance->getLayers()))));

        if ($this->exposeLayerOrder) {
            $layerOrderChoices = array();
            foreach (WmsInstance::validLayerOrderChoices() as $validChoice) {
                $translationKey = "mb.wms.wmsloader.repo.instance.label.layerOrder.$validChoice";
                $layerOrderChoices[$validChoice] = $translationKey;
            }
            $builder->add('layerOrder', ChoiceType::class, array(
                'choices' => $layerOrderChoices,
                'required' => true,
                'auto_initialize' => true,
            ));
        }
    }
}

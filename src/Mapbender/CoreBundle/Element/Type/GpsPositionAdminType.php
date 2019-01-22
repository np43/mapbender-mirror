<?php

namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GpsPositionAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class GpsPositionAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'gpsposition';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'application' => null,
            'average'     => 1
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tooltip', TextType::class, array('required' => false))
            ->add('label', CheckboxType::class, array('required' => false))
            ->add('autoStart', CheckboxType::class, array('required' => false))
            ->add(
                'target',
                TargetElementType::class,
                array(
                    'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                    'application' => $options['application'],
                    'property_path' => '[target]',
                    'required' => false
                )
            )
            ->add('icon', IconClassType::class, array('required' => false))
            ->add('action', TextType::class, array('required' => false))
            ->add('refreshinterval', TextType::class, array('required' => false))
            ->add('average', TextType::class, array(
                'required' => false,
                'property_path' => '[average]'
                ))
            ->add('follow', CheckboxType::class, array(
                'required' => false,
                'property_path' => '[follow]'))
            ->add('centerOnFirstPosition', CheckboxType::class, array(
                'required' => false,
                'property_path' => '[centerOnFirstPosition]'))
            ->add('zoomToAccuracy', CheckboxType::class, array(
                'required' => false,
                'property_path' => '[zoomToAccuracy]'))
            ->add('zoomToAccuracyOnFirstPosition', CheckboxType::class, array(
                'required' => false,
                'property_path' => '[zoomToAccuracyOnFirstPosition]'));
    }
}

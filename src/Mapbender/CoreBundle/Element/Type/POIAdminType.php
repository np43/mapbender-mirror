<?php

namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class POIAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class POIAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'poi';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'application' => null
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
            ->add('useMailto', CheckboxType::class, array('required' => false))
            ->add('body', TextType::class, array('required' => true))
            ->add(
                'gps',
                TargetElementType::class,
                array(
                    'element_class' => 'Mapbender\\CoreBundle\\Element\\GpsPosition',
                    'application' => $options['application'],
                    'property_path' => '[gps]',
                    'required' => false
                )
            )
            ->add(
                'target',
                TargetElementType::class,
                array(
                    'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                    'application' => $options['application'],
                    'property_path' => '[target]',
                    'required' => false
                )
            );
    }
}

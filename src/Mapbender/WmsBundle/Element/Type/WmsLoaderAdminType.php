<?php

namespace Mapbender\WmsBundle\Element\Type;

use Mapbender\CoreBundle\Element\Type\TargetElementType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class WmsLoaderAdminType
 * @package Mapbender\WmsBundle\Element\Type
 */
class WmsLoaderAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'wmsloader';
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
        $builder->add('tooltip', TextType::class, array('required' => false))
            ->add('target', TargetElementType::class,
                array(
                'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                'application' => $options['application'],
                'property_path' => '[target]',
                'required' => false))
            ->add('defaultFormat', ChoiceType::class,
                array(
                "choices" => array(
                    "image/png" => "image/png",
                    "image/gif" => "image/gif",
                    "image/jpeg" => "image/jpeg")))
            ->add('defaultInfoFormat', ChoiceType::class,
                array(
                "choices" => array(
                    "text/html" => "text/html",
                    "text/xml" => "text/xml",
                    "text/plain" => "text/plain")))
            ->add('autoOpen', CheckboxType::class, array('required' => false))
            ->add('splitLayers', CheckboxType::class, array('required' => false))
            ->add('useDeclarative', CheckboxType::class, array('required' => false));
    }
}

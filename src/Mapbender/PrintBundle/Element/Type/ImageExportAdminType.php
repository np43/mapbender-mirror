<?php

namespace Mapbender\PrintBundle\Element\Type;

use Mapbender\CoreBundle\Element\Type\TargetElementType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ImageExportAdminType
 * @package Mapbender\PrintBundle\Element\Type
 */
class ImageExportAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'imageexport';
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
        $builder->add('target', TargetElementType::class,
            array(
            'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
            'application' => $options['application'],
            'property_path' => '[target]',
            'required' => false));
    }
}

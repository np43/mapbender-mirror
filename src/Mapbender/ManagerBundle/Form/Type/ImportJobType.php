<?php

namespace Mapbender\ManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ImportJobType
 * @package Mapbender\ManagerBundle\Form\Type
 * ImportJobType class creates a form for an ImportJob object.
 */
class ImportJobType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'importjob';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array());
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('importFile', FileType::class, array('required' => true))
        ;
    }
}

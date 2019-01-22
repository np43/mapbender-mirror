<?php

namespace Mapbender\ManagerBundle\Form\Type;

use Mapbender\ManagerBundle\Component\ExchangeJob;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class ExportJobType
 * @package Mapbender\ManagerBundle\Form\Type
 * ExportJobType class creates a form for an ExportJob object.
 */
class ExportJobType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'exportjob';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'application' => null,
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('application', EntityType::class, array(
                'class' => 'Mapbender\CoreBundle\Entity\Application',
                'property' => 'title',
                'multiple' => false,
                'choices' => $options['application'],
                'required' => true,
            ))
            ->add('format', TextType::class,
                array(
                'required' => true,
                'choices' => array(
                    ExchangeJob::FORMAT_JSON => ExchangeJob::FORMAT_JSON,
                    ExchangeJob::FORMAT_YAML => ExchangeJob::FORMAT_YAML)));
    }
}

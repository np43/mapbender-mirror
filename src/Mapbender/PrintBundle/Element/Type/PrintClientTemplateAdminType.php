<?php

namespace Mapbender\PrintBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PrintClientTemplateAdminType
 * @package Mapbender\PrintBundle\Element\Type
 */
class PrintClientTemplateAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'printclienttemplate';
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
        $builder->add('template', TextType::class, array('required' => false))
            ->add('label', TextType::class, array('required' => false));
    }
}

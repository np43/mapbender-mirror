<?php

namespace Mapbender\PrintBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PrintClientQualityAdminType
 * @package Mapbender\PrintBundle\Element\Type
 */
class PrintClientQualityAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'printclientquality';
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
        $builder->add('dpi', TextType::class, array('required' => false))
            ->add('label', TextType::class, array('required' => false));
    }
}

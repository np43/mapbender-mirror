<?php

namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class InstanceSetAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class InstanceSetAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'instanceset';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'instances' => null
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
                'required' => true,
                'property_path' => '[title]'))
            ->add('group', TextType::class, array(
                'required' => false,
                'property_path' => '[group]'))
            ->add('instances', ChoiceType::class, array(
                'choices' => $options['instances'],
                'required' => false,
                'multiple' => true));
    }
}

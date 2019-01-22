<?php

namespace Mapbender\WmcBundle\Element\Type;

use Mapbender\CoreBundle\Element\Type\TargetElementType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SuggestMapAdminType
 * @package Mapbender\WmcBundle\Element\Type
 */
class SuggestMapAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'suggestmap';
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
            ->add('receiver', ChoiceType::class,
                array(
                'multiple' => true,
                'required' => true,
                'choices' => array(
                    'email' => 'E-Mail',
                    'facebook' => 'Facebook',
                    'twitter' => 'Twitter',
                    'google+' => 'Google+')));
    }
}

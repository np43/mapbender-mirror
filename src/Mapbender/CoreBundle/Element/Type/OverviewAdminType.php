<?php

namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OverviewAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class OverviewAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'overview';
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
            ->add('layerset', LayersetAdminType::class,
                  array(
                'application'   => $options['application'],
                'property_path' => '[layerset]',
                'required'      => true))
            ->add('target', TargetElementType::class,
                  array(
                'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                'application'   => $options['application'],
                'property_path' => '[target]',
                'required'      => false))
            ->add('anchor', ChoiceType::class,
                  array(
                'required' => true,
                "choices"  => array(
                    'left-top'     => 'left-top',
                    'left-bottom'  => 'left-bottom',
                    'right-top'    => 'right-top',
                    'right-bottom' => 'right-bottom')))
            ->add('maximized', CheckboxType::class, array('required' => false))
            ->add('fixed', CheckboxType::class, array('required' => false))
            ->add('width', TextType::class, array('required' => true))
            ->add('height', TextType::class, array('required' => true));
    }
}

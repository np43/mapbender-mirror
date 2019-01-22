<?php

namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LayerThemeType
 * @package Mapbender\CoreBundle\Element\Type
 */
class LayerThemeType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'theme';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'id' => null,
            'title' => '',
            'useTheme' => true,
            'opened' => true,
            'sourceVisibility' => false,
            'allSelected' => false,
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', HiddenType::class, array('required' => true, 'property_path' => '[id]'))
            ->add('title', HiddenType::class, array('required' => false, 'property_path' => '[title]'))
            ->add('useTheme', CheckboxType::class, array('required' => false, 'property_path' => '[useTheme]'))
            ->add('opened', CheckboxType::class, array('required' => false, 'property_path' => '[opened]'))
            ->add('sourceVisibility', CheckboxType::class, array('required' => false, 'property_path' => '[sourceVisibility]'))
            ->add('allSelected', CheckboxType::class, array('required' => false, 'property_path' => '[allSelected]'));
    }
}

<?php
namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LegendAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class LegendAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'legend';
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
            ->add('elementType', ChoiceType::class,
                array(
                'required' => true,
                'choices' => array(
                    "dialog" => "dialog",
                    "blockelement" => "blockelement")))
            ->add('autoOpen', CheckboxType::class, array('required' => false))
            ->add('displayType', ChoiceType::class,
                array(
                'required' => true,
                'choices' => array(
                    "list" => "list")))
            ->add('target', TargetElementType::class,
                array(
                'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                'application' => $options['application'],
                'property_path' => '[target]',
                'required' => false))
            ->add('hideEmptyLayers', CheckboxType::class, array('required' => false))
            ->add('generateLegendGraphicUrl', CheckboxType::class,
                array('required' => false))
            ->add('showSourceTitle', CheckboxType::class, array('required' => false))
            ->add('showLayerTitle', CheckboxType::class, array('required' => false))
            ->add('showGrouppedTitle', CheckboxType::class, array('required' => false));
    }
}

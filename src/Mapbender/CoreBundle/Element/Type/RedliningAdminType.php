<?php

namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RedliningAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class RedliningAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'redlining';
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
        $builder->add('target', TargetElementType::class, array(
                'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                'application' => $options['application'],
                'property_path' => '[target]',
                'required' => false))
            ->add('display_type', ChoiceType::class, array(
                'required' => true,
                'choices' => array('dialog' => 'Dialog', 'element' => 'Element')))
            ->add('auto_activate', CheckboxType::class, array('required' => false))
            ->add('deactivate_on_close', CheckboxType::class, array('required' => false))
            ->add('geometrytypes', ChoiceType::class, array(
                'required' => true,
                'multiple' => true,
                'choices' => array(
                    'point' => 'Point',
                    'line' => 'Line',
                    'polygon' => 'Polygon',
                    'rectangle' => 'Rectangle',
                    'text' => 'Text')));
    }
}

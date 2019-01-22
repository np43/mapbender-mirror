<?php

namespace Mapbender\WmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Mapbender\WmsBundle\Form\EventListener\FieldSubscriber;

/**
 * Class WmsInstanceLayerType
 * @package Mapbender\WmsBundle\Form\Type
 */
class WmsInstanceLayerType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'wmsinstancelayer';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'num_layers' => 0));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subscriber = new FieldSubscriber($builder->getFormFactory());
        $builder->addEventSubscriber($subscriber);
        $builder->add('title', TextType::class, array('required' => false))
                ->add('active', CheckboxType::class, array('required' => false))
                ->add('selected', CheckboxType::class, array('required' => false))
                ->add('info', CheckboxType::class,
                      array('required' => false,'disabled' => true))
                ->add('toggle', CheckboxType::class,
                    array('required' => false))
                ->add('allowselected', CheckboxType::class,
                      array('required' => false))
                ->add('allowinfo', CheckboxType::class,
                      array('required' => false, 'disabled' => true))
                ->add('allowtoggle', CheckboxType::class,
                      array('required' => false))
                ->add('allowreorder', CheckboxType::class,
                      array('required' => false))
                ->add('minScale', TextType::class,
                      array('required' => false))
                ->add('maxScale', TextType::class,
                      array('required' => false))
                ->add('style', ChoiceType::class,
                      array(
                    'label' => 'style',
                    'choices' => array(),
                    'required' => false))
                ->add('priority', HiddenType::class,
                      array('required' => true));
    }
}

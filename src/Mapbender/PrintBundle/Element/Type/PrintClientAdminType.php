<?php

namespace Mapbender\PrintBundle\Element\Type;

use Mapbender\CoreBundle\Element\Type\TargetElementType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Mapbender\PrintBundle\Form\EventListener\PrintClientSubscriber;
use Mapbender\ManagerBundle\Form\Type\YAMLConfigurationType;

/**
 * Class PrintClientAdminType
 * @package Mapbender\PrintBundle\Element\Type
 */
class PrintClientAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'printclient';
    }

    /**
     * @inheritdoc
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
        $subscriber = new PrintClientSubscriber($builder->getFormFactory());
        $builder->addEventSubscriber($subscriber);
        $builder
            ->add('target', TargetElementType::class, array(
                'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                'application' => $options['application'],
                'property_path' => '[target]',
                'required' => false,
            ))
            ->add('type', ChoiceType::class, array(
                    'required' => true,
                    'choices' => array(
                        'dialog' => 'Dialog',
                        'element' => 'Element',
                    ),
            ))
            ->add('scales', TextType::class, array(
                'required' => false,
            ))
            ->add('file_prefix', TextType::class, array(
                'required' => false,
            ))
            ->add('rotatable', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('legend', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('legend_default_behaviour', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('optional_fields', YAMLConfigurationType::class, array(
                'required' => false,
            ))
            ->add('required_fields_first', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('replace_pattern', YAMLConfigurationType::class, array(
                'required' => false,
            ))
            ->add('templates', CollectionType::class, array(
                'type' => new PrintClientTemplateAdminType(),
                'allow_add' => true,
                'allow_delete' => true,
                'auto_initialize' => false,
            ))
        ;
    }
}

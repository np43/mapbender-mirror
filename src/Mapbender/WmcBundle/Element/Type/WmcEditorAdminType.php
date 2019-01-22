<?php

namespace Mapbender\WmcBundle\Element\Type;

use Mapbender\CoreBundle\Element\Type\TargetElementType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class WmcEditorAdminType
 * @package Mapbender\WmcBundle\Element\Type
 */
class WmcEditorAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'wmceditor';
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'application' => null
            )
        );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tooltip', TextType::class, array('required' => false))
            ->add(
                'target',
                TargetElementType::class,
                array(
                    'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                    'application' => $options['application'],
                    'property_path' => '[target]',
                    'required' => false
                )
            )
            ->add('width', IntegerType::class, array('required' => false)
            )
            ->add('height', IntegerType::class, array('required' => false));
    }
}

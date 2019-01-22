<?php

namespace Mapbender\WmsBundle\Element\Type;

use Mapbender\WmsBundle\Element\Type\Transformer\DimensionSetTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DimensionSetAdminType
 * @package Mapbender\WmsBundle\Element\Type
 */
class DimensionSetAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'dimensionset';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'dimensions' => array(),
            'title' => null,
            'group' => null,
            'dimension' => null,
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'required' => true,
                'attr' => array(
                    'data-name' => 'title',
                ),
            ))
            ->add('group', DimensionSetDimensionChoiceType::class, array(
                'required' => true,
                'multiple' => true,
                'mapped' => true,
                'dimensions' => $options['dimensions'],
                'attr' => array(
                    'data-name' => 'group',
                ),
            ))
            ->add('dimension', HiddenType::class, array(
                'required' => true,
                'mapped' => true,
                'attr' => array(
                    'data-name' => 'dimension',
                ),
            ))
        ;
        $builder->addModelTransformer(new DimensionSetTransformer($options['dimensions']));
    }
}

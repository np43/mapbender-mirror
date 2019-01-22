<?php

namespace Mapbender\WmsBundle\Form\Type;

use Mapbender\WmsBundle\Form\DataTransformer\VendorSpecificTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Mapbender\WmsBundle\Component\VendorSpecific as VS;

/**
 * Class VendorSpecificType
 * @package Mapbender\WmsBundle\Form\Type
 */
class VendorSpecificType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return "vendorspecific";
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'name' => '',
            'vstype' => VS::TYPE_VS_SIMPLE,
            'hidden' => false,
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vstype', ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    VS::TYPE_VS_SIMPLE => VS::TYPE_VS_SIMPLE,
                    VS::TYPE_VS_USER => VS::TYPE_VS_USER,
                    VS::TYPE_VS_GROUP => VS::TYPE_VS_GROUP
                ),
            ))
            ->add('name', TextType::class, array(
                'required' => true,
            ))
            ->add('default', TextType::class, array(
                'required' => true,
            ))
            ->add('hidden', CheckboxType::class, array(
                'required' => false,
            ))
            ->addModelTransformer(new VendorSpecificTransformer())
        ;
    }
}

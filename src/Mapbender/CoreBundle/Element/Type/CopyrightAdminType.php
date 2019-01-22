<?php
namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CopyrightAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class CopyrightAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'copyright';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'application' => null,
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tooltip', TextType::class, array('required' => false))
            ->add('autoOpen', CheckboxType::class, array('required' => false))
            ->add('popupWidth', TextType::class, array('required' => true))
            ->add('popupHeight', TextType::class, array('required' => true))
            ->add('content', TextareaType::class, array('required' => true));
    }
}

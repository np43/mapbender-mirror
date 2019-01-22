<?php
namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ButtonAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class ButtonAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'button';
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
            ->add('icon', IconClassType::class, array('required' => false))
            ->add('label', CheckboxType::class, array('required' => false))
            ->add('target', TargetElementType::class,
                array(
                'application' => $options['application'],
                'property_path' => '[target]',
                'required' => false))
            ->add('click', TextType::class, array('required' => false))
            ->add('group', TextType::class, array('required' => false))
            ->add('action', TextType::class, array('required' => false))
            ->add('deactivate', TextType::class, array('required' => false));
    }
}

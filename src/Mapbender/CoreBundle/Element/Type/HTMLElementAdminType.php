<?php
namespace Mapbender\CoreBundle\Element\Type;

use Mapbender\CoreBundle\Form\Type\HtmlFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class HTMLElementAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class HTMLElementAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'htmlelement';
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
        $builder
            ->add('content', HtmlFormType::class, [
                'required' => false,
            ])
            ->add('classes', TextType::class, [
                'required' => false,
            ]);
    }
}

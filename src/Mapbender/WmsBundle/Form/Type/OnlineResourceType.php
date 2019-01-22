<?php

namespace Mapbender\WmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OnlineResourceType
 * @package Mapbender\WmsBundle\Form\Type
 */
class OnlineResourceType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'onlineresource';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('format', TextType::class, array(
                'required' => false,))
            ->add('href', TextType::class, array(
                'required' => false,));
    }
}

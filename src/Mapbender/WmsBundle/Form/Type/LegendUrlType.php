<?php

namespace Mapbender\WmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LegendUrlType
 * @package Mapbender\WmsBundle\Form\Type
 */
class LegendUrlType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'legendurl';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('width', TextType::class, array(
                'required' => false,))
            ->add('height', TextType::class, array(
                'required' => false,))
            ->add('onlineResource', OnlineResourceType::class, array(
                'data_class' => 'Mapbender\WmsBundle\Component\OnlineResource'));
    }
}

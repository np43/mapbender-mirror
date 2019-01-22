<?php

namespace Mapbender\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LayersetType
 * @package Mapbender\CoreBundle\Form\Type
 */
class LayersetType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'layerset';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("id", HiddenType::class, array("required" => false))
                ->add("title", TextType::class, array(
                    'max_length' => 128));
    }
}


<?php

namespace Mapbender\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class StateType
 * @package Mapbender\CoreBundle\Form\Type
 */
class StateType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'state';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("id", HiddenType::class, array("required" => false))
                ->add("serverurl", HiddenType::class, array("required" => true))
                ->add("slug", HiddenType::class, array("required" => true))
                ->add("json", HiddenType::class, array("required" => true))
                ->add("title", TextType::class, array("required" => true));
    }
}


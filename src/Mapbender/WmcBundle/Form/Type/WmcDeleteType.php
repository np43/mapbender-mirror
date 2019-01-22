<?php

namespace Mapbender\WmcBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class WmcDeleteType
 * @package Mapbender\WmcBundle\Form\Type
 */
class WmcDeleteType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'wmc';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', HiddenType::class);
    }
}

<?php
namespace Mapbender\WmcBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class WmcStateType
 * @package Mapbender\WmcBundle\Form\Type
 */
class WmcStateType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'wmcstate';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('state', HiddenType::class,
            array(
            'required' => false,
            'data_class' => 'Mapbender\CoreBundle\Entity\State'));
    }
}

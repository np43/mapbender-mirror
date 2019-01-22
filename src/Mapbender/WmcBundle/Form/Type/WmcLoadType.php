<?php
namespace Mapbender\WmcBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class WmcLoadType
 * @package Mapbender\WmcBundle\Form\Type
 */
class WmcLoadType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'wmcload';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('xml', FileType::class, array('required' => true));
    }
}

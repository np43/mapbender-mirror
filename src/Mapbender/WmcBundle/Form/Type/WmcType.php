<?php

namespace Mapbender\WmcBundle\Form\Type;

use Mapbender\CoreBundle\Form\Type\StateType;
use Mapbender\WmsBundle\Form\Type\LegendUrlType;
use Mapbender\WmsBundle\Form\Type\OnlineResourceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class WmcType
 * @package Mapbender\WmcBundle\Form\Type
 */
class WmcType extends AbstractType
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
        $builder
            ->add('id', HiddenType::class)
            ->add('public', CheckboxType::class,
                array('required' => false))
            ->add('state', StateType::class,
                array('data_class' => 'Mapbender\CoreBundle\Entity\State'))
            ->add('keywords', TextType::class,
                array('required' => false))
            ->add('abstract', TextareaType::class,
                array('required' => false))
            ->add('logourl', LegendUrlType::class,
                array('data_class' => 'Mapbender\WmsBundle\Component\LegendUrl'))
            ->add('screenshot', FileType::class,
                array('required' => false))
            ->add('descriptionurl', OnlineResourceType::class,
                array('data_class' => 'Mapbender\WmsBundle\Component\OnlineResource'));
    }
}

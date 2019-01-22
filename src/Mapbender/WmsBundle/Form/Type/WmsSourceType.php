<?php

namespace Mapbender\WmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class WmsSourceType
 * @package Mapbender\WmsBundle\Form\Type
 */
class WmsSourceType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'wmssource';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                // Base data
                ->add('url', TextType::class,
                      array(
                    'attr' => array(
                        'title' => 'The application title, as shown in the browser '
                        . 'title bar and in lists.')))
                ->add('username', TextType::class,
                      array(
                    'attr' => array(
                        'title' => 'The slug is based on the title and used in the '
                        . 'application URL.')))
                ->add('password', TextareaType::class,
                      array(
                    'required' => false,
                    'attr' => array(
                        'title' => 'The description is used in overview lists.')));
    }
}


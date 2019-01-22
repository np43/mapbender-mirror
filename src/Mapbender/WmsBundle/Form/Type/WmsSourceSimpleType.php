<?php

namespace Mapbender\WmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class WmsSourceSimpleType
 * @package Mapbender\WmsBundle\Form\Type
 */
class WmsSourceSimpleType extends AbstractType
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
            ->add('onlyvalid', CheckboxType::class,
                array(
                'mapped' => false,
                'data' => false,
                'attr' => array(
                    'title' => 'The application title, as shown in the browser '
                    . 'title bar and in lists.')))
            ->add('originUrl', TextType::class,
                array(
                'required' => true,
                'attr' => array(
                    'title' => 'The wms GetCapabilities url.')))
            ->add('username', TextType::class,
                array(
                'required' => false,
                'attr' => array(
                    'title' => 'The username.',
                    'autocomplete' => 'off')))
            ->add('password', PasswordType::class,
                array(
                'required' => false,
                'attr' => array(
                    'title' => 'The password.',
                    'autocomplete' => 'off')));
    }
}

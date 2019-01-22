<?php
namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SimpleSearchAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class SimpleSearchAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'simplesearch_form';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'application' => null));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query_url', TextType::class, array(
                'label' => 'Query URL',
                'property_path' => '[query_url]',
                'required' => true))
            ->add('query_key', TextType::class, array(
                'label' => 'Query URL key',
                'property_path' => '[query_key]',
                'required' => true))
            ->add('query_ws_replace', TextType::class, array(
                'label' => 'Query Whitespace replacement pattern',
                'trim' => false,
                'property_path' => '[query_ws_replace]'))
            ->add('query_format', TextType::class, array(
                'label' => 'Query key format',
                'property_path' => '[query_format]',
                'required' => true))
            ->add('token_regex', TextType::class, array(
                'label' => 'Token (JavaScript regex)',
                'property_path' => '[token_regex]',
                'required' => false))
            ->add('token_regex_in', TextType::class, array(
                'label' => 'Token search (JavaScript regex)',
                'property_path' => '[token_regex_in]',
                'required' => false))
            ->add('token_regex_out', TextType::class, array(
                'label' => 'Token replace (JavaScript regex)',
                'property_path' => '[token_regex_out]',
                'required' => false))
            ->add('collection_path', TextType::class, array(
                'property_path' => '[collection_path]',
                'required' => false))
            ->add('label_attribute', TextType::class, array(
                'property_path' => '[label_attribute]',
                'required' => true))
            ->add('geom_attribute', TextType::class, array(
                'property_path' => '[geom_attribute]',
                'required' => true))
            ->add('geom_format', ChoiceType::class, array(
                'property_path' => '[geom_format]',
                'choices' => array(
                    'WKT' => 'WKT',
                    'GeoJSON' => 'GeoJSON'),
                'required' => true))
            ->add('delay', NumberType::class, array(
                'property_path' => '[delay]',
                'required' => true))
            ->add('result_buffer', NumberType::class, array(
                'property_path' => '[result][buffer]'))
            ->add('result_minscale', NumberType::class, array(
                'property_path' => '[result][minscale]'))
            ->add('result_maxscale', NumberType::class, array(
                'property_path' => '[result][maxscale]'))
            ->add('result_icon_url', TextType::class, array(
                'property_path' => '[result][icon_url]'))
            ->add('result_icon_offset', TextType::class, array(
                'property_path' => '[result][icon_offset]'));
    }
}

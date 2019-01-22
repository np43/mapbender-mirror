<?php
namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Mapbender\ManagerBundle\Form\DataTransformer\YAMLDataTransformer;

/**
 * Class SearchRouterRouteAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class SearchRouterRouteAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'search_form_route';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array());
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $yamlTransformer = new YAMLDataTransformer(20);
        $builder->add('title', TextType::class, array(
            'label' => 'Title'));
        $builder->add($builder->create('configuration', TextareaType::class, array(
            'label' => 'Configuration'))->addViewTransformer($yamlTransformer));
    }
}

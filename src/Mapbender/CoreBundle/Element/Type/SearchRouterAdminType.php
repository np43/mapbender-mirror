<?php
namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Mapbender\CoreBundle\Element\DataTransformer\SearchRouterRouteTransformer;

/**
 * Class SearchRouterAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class SearchRouterAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'search_form';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'application' => null,
            'routes' => array(),));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('target', TargetElementType::class, array(
                'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                'application' => $options['application'],
                'property_path' => '[target]',
                'required' => false))
            ->add('dialog', CheckboxType::class, array(
                'property_path' => '[asDialog]'))
            ->add('timeout', IntegerType::class, array(
                'label' => 'Timeout factor',
                'property_path' => '[timeoutFactor]'))
            ->add('width', IntegerType::class, array('required' => true))
            ->add('height', IntegerType::class, array('required' => true))
            ->add($builder->create('routes', CollectionType::class, array(
                'type' => new SearchRouterRouteAdminType(),
                'allow_add' => true,
                'allow_delete' => true,
                'auto_initialize' => false,))
            ->addViewTransformer(new SearchRouterRouteTransformer()));
    }
}

<?php
namespace Mapbender\CoreBundle\Element\Type;

use Mapbender\CoreBundle\Element\SearchRouter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SearchRouterSelectType
 * @package Mapbender\CoreBundle\Element\Type
 */
class SearchRouterSelectType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'search_routes';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(SearchRouter::getDefaultConfiguration());
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $routes = array();

        foreach ($options['routes'] as $name => $conf) {
            $routes[ $name ] = $conf['title'];
        }

        $builder->add('route', ChoiceType::class, array(
            'choices'  => $routes,
            'mapped'   => false,
            'multiple' => false,
            'expanded' => false,
            'attr'     => array(
                'autocomplete' => 'off'
            )));
    }
}

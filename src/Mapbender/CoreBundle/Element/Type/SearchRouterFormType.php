<?php
namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SearchRouterFormType
 * @package Mapbender\CoreBundle\Element\Type
 */
class SearchRouterFormType extends AbstractType
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
            'fields' => array()));
    }

    /**
     * @param $name
     * @return mixed
     */
    private function escapeName($name)
    {
        return str_replace('"', '', $name);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['fields']['form'] as $name => $conf) {
            $builder->add($this->escapeName($name), $conf['type'], $conf['options']);
        }
    }
}

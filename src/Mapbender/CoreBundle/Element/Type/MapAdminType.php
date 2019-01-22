<?php

namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Mapbender\CoreBundle\Form\Type\ExtentType;
use Mapbender\CoreBundle\Form\EventListener\MapFieldSubscriber;

/**
 * Class MapAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class MapAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'map';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'application' => null,
            'available_templates' => array()));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subscriber = new MapFieldSubscriber($builder->getFormFactory(), $options['application']);
        $builder->addEventSubscriber($subscriber);
        $builder
            ->add('dpi', NumberType::class, array(
                'label' => 'DPI'))
            ->add('tileSize', NumberType::class, array(
                'required' => false,
                'label' => 'Tile size'))
            ->add('wmsTileDelay', NumberType::class, array(
                'required' => false,
                'label' => 'Delay before tiles are loaded'))
            ->add('srs', TextType::class, array(
                'label' => 'SRS'))
            ->add('units', ChoiceType::class, array(
                'label' => 'Map units',
                'choices' => array(
                    'degrees' => 'Degrees',
                    'm' => 'Meters',
                    'ft' => 'Feet',
                    'mi' => 'Miles',
                    'inches' => 'Inches')))
            ->add('extent_max', new ExtentType(), array(
                'label' => 'Max. extent',
                'property_path' => '[extents][max]'))
            ->add('extent_start', new ExtentType(), array(
                'label' => 'Start. extent',
                'property_path' => '[extents][start]'))
            ->add('scales', TextType::class, array(
                'label' => 'Scales (csv)',
                'required' => true))
            ->add('otherSrs', TextType::class, array(
                'label' => 'Other SRS',
                'required' => false));
    }
}

<?php
namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FeatureInfoAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class FeatureInfoAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'featureinfo';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'application' => null
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tooltip', TextType::class, array('required' => false))
            ->add('type', ChoiceType::class, array(
                'required' => true,
                'choices' => array('dialog' => 'Dialog', 'element' => 'Element')))
            ->add('displayType', ChoiceType::class, array(
                'required' => true,
                'choices' => array('tabs' => 'Tabs', 'accordion' => 'Accordion')))
            ->add('autoActivate', CheckboxType::class, array('required' => false))
            ->add('printResult', CheckboxType::class, array('required' => false))
            ->add('deactivateOnClose', CheckboxType::class, array('required' => false))
            ->add('showOriginal', CheckboxType::class, array('required' => false))
            ->add('onlyValid', CheckboxType::class, array('required' => false))
            ->add('target', TargetElementType::class, array(
                'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                'application' => $options['application'],
                'property_path' => '[target]',
                'required' => false))
            ->add('width', IntegerType::class, array('required' => true))
            ->add('height', IntegerType::class, array('required' => true));
    }
}

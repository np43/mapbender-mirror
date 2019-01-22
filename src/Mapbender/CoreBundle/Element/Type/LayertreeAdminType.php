<?php
namespace Mapbender\CoreBundle\Element\Type;

use Mapbender\CoreBundle\Element\EventListener\LayertreeSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LayertreeAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class LayertreeAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'layertree';
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
        $subscriber = new LayertreeSubscriber($builder->getFormFactory(), $options['application']);
        $builder->addEventSubscriber($subscriber);
        $builder->add('target', TargetElementType::class, array(
                'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                'application' => $options['application'],
                'property_path' => '[target]',
                'required' => false))
            ->add('type', ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'element' => 'Element',
                    'dialog' => 'Dialog')))
            ->add('autoOpen', CheckboxType::class, array(
                'required' => false))
            ->add('useTheme', CheckboxType::class, array(
                'required' => false))
            ->add('displaytype', ChoiceType::class, array(
                'required' => true,
                'choices' => array('tree' => 'Tree')))
            ->add('titlemaxlength', TextType::class, array(
                'required' => true))
            ->add('showBaseSource', CheckboxType::class, array(
                'required' => false))
            ->add('showHeader', CheckboxType::class, array(
                'required' => false))
            ->add('hideInfo', CheckboxType::class, array(
                'required' => false))
            ->add('hideNotToggleable', CheckboxType::class, array(
                'required' => false))
            ->add('hideSelect', CheckboxType::class, array(
                'required' => false))
            // see LayerTreeMenuType.php
            ->add('menu', LayerTreeMenuType::class, array(
                'required' => false,
            ))
        ;
    }
}

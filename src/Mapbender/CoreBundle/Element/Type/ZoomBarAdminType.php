<?php
namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * 
 */
class ZoomBarAdminType extends AbstractType
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'zoombar';
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'application' => null
        ));
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tooltip', TextType::class, array('required' => false))
            ->add('components', ChoiceType::class,
                array(
                'required' => true,
                'multiple' => true,
                'choices' => array(
                    "pan" => "Pan",
                    "history" => "History",
                    "zoom_box" => "Zoom box",
                    "zoom_max" => "zoom to max extent",
                    "zoom_in_out" => "Zoom in/out",
                    "zoom_slider" => "Zoom slider")))
            ->add('target', TargetElementType::class,
                array(
                    'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                    'application' => $options['application'],
                    'property_path' => '[target]',
                    'required' => false
                ))
            ->add('stepSize', TextType::class, array('required' => false))
            ->add('stepByPixel', ChoiceType::class,
                array(
                'choices' => array('true' => 'true', 'false' => 'false')))
            ->add('anchor', ChoiceType::class,
                array(
                'required' => true,
                "choices" => array(
                    'inline' => 'inline',
                    'left-top' => 'left-top',
                    'left-bottom' => 'left-bottom',
                    'right-top' => 'right-top',
                    'right-bottom' => 'right-bottom')))
            ->add('draggable', CheckboxType::class, array('required' => false));
    }
}

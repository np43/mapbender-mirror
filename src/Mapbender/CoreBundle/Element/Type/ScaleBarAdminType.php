<?php
namespace Mapbender\CoreBundle\Element\Type;

use Mapbender\CoreBundle\Entity\Layerset;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ScaleBarAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class ScaleBarAdminType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'scalebar';
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
        $app = $options['application'];
        $layersets = array();

        /** @var Layerset $layerset */
        foreach ($app->getLayersets() as $layerset) {
            $layersets[$layerset->getId()] = $layerset->getTitle();
        }

        $builder->add('tooltip', TextType::class, array('required' => false))
            ->add('target', TargetElementType::class,
                array(
                'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                'application' => $options['application'],
                'property_path' => '[target]',
                'required' => false))
            ->add('maxWidth', TextType::class, array('required' => true))
            ->add('anchor', ChoiceType::class,
                array(
                'required' => true,
                "choices" => array(
                    'left-top' => 'left-top',
                    'left-bottom' => 'left-bottom',
                    'right-top' => 'right-top',
                    'right-bottom' => 'right-bottom')))
            ->add('units', ChoiceType::class,
                array(
                'required' => true,
                'multiple' => true,
                'choices' => array(
                    'km' => 'kilometer',
                    'ml' => 'mile')));
    }
}

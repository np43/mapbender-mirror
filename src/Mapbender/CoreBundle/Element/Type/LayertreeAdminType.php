<?php
namespace Mapbender\CoreBundle\Element\Type;

use Mapbender\CoreBundle\Element\EventListener\LayertreeSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LayertreeAdminType
 */
class LayertreeAdminType extends AbstractType
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'layertree';
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
        $subscriber = new LayertreeSubscriber($options['application']);
        $builder->addEventSubscriber($subscriber);
        $builder->add('target', 'target_element', array(
                'element_class' => 'Mapbender\\CoreBundle\\Element\\Map',
                'application' => $options['application'],
                'property_path' => '[target]',
                'required' => false))
            ->add('type', 'choice', array(
                'required' => true,
                'choices' => array(
                    'Element' => 'element',
                    'Dialog' => 'dialog',
                ),
                'choices_as_values' => true,
            ))
            ->add('autoOpen', 'checkbox', array(
                'required' => false,
                'label' => 'mb.core.admin.layertree.label.autoopen',
            ))
            ->add('useTheme', 'checkbox', array(
                'required' => false,
                'label' => 'mb.core.admin.layertree.label.usetheme',
            ))
            ->add('showBaseSource', 'checkbox', array(
                'required' => false,
                'label' => 'mb.core.admin.layertree.label.showbasesources',
            ))
            ->add('showHeader', 'checkbox', array(
                'required' => false,
                'label' => 'mb.core.admin.layertree.label.showheader',
            ))
            ->add('hideInfo', 'checkbox', array(
                'required' => false,
                'label' => 'mb.core.admin.layertree.label.hideinfo',
            ))
            ->add('hideNotToggleable', 'checkbox', array(
                'required' => false,
                'label' => 'mb.core.admin.layertree.label.hidenottoggleable',
            ))
            ->add('hideSelect', 'checkbox', array(
                'required' => false,
                'label' => 'mb.core.admin.layertree.label.hideselect',
            ))
            // see LayerTreeMenuType.php
            ->add('menu', 'layertree_menu', array(
                'required' => false,
            ))
        ;
    }
}

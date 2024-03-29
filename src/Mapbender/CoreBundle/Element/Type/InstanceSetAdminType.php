<?php

namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstanceSetAdminType extends AbstractType
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'instanceset';
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'instances' => null,
            'choices_as_values' => false,
        ));
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
                'required' => true,
                'property_path' => '[title]'))
            ->add('group', 'text', array(
                'required' => false,
                'property_path' => '[group]'))
            ->add('instances', 'choice', array(
                'choices' => $options['instances'],
                'choices_as_values' => $options['choices_as_values'],
                'required' => false,
                'multiple' => true,
            ))
        ;
    }

}

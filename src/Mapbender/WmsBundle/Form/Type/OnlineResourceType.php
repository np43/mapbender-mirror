<?php

namespace Mapbender\WmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OnlineResourceType extends AbstractType
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'onlineresource';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => true,
            'label_attr' => array(
                'class' => 'hidden',
            ),
        ));
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('format', 'text',
                      array(
                    'required' => false,))
                ->add('href', 'text',
                      array(
                    'required' => false,));
    }

}


<?php


namespace Mapbender\CoreBundle\Element\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LayerTreeMenuType extends AbstractType
{
    protected $exposedChoices = array();

    public function __construct($legacyDummy)
    {
        $this->exposedChoices = array(
            "Remove layer" => "layerremove",
            "Opacity" => "opacity",
            "Zoom to layer" => "zoomtolayer",
            "Metadata" => "metadata",
            "Dimension" => "dimension",
        );
    }


    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->exposedChoices,
            'multiple' => true,
            'choices_as_values' => true,
        ));
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'layertree_menu';
    }

    /**
     * @return null|string|\Symfony\Component\Form\FormTypeInterface
     */
    public function getParent()
    {
        return 'choice';
    }
}

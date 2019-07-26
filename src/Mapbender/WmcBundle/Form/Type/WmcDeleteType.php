<?php
namespace Mapbender\WmcBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class WmcDeleteType extends AbstractType
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'wmc';
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden');
    }

}

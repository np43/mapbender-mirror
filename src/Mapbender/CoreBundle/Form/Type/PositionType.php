<?php

namespace Mapbender\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

/**
 * Class PositionType
 * @package Mapbender\CoreBundle\Form\Type
 */
class PositionType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'position';
    }

    /**
     * @return string|\Symfony\Component\Form\FormTypeInterface|null
     */
    public function getParent()
    {
        return 'collection';
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->children[0]->vars['label'] = 'x';
        $view->children[0]->vars['attr'] = array('placeholder' => 'x');
        $view->children[1]->vars['label'] = 'y';
        $view->children[1]->vars['attr'] = array('placeholder' => 'y');
    }
}


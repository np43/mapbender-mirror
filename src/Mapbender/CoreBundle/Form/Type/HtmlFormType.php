<?php

namespace Mapbender\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Mapbender\CoreBundle\Validator\Constraints\HtmlConstraint;
use Mapbender\CoreBundle\Validator\Constraints\TwigConstraint;

/**
 * Class HtmlFormType
 * @package Mapbender\CoreBundle\Form\Type
 */
class HtmlFormType extends AbstractType
{
    /**
     * @var HtmlConstraint
     */
    private $htmlConstraint;

    /**
     * @var TwigConstraint
     */
    private $twigConstraint;

    /**
     * HTMLElementAdminType constructor
     *
     * @param HtmlConstraint $htmlConstraint
     * @param TwigConstraint $twigConstraint
     */
    public function __construct(HtmlConstraint $htmlConstraint, TwigConstraint $twigConstraint)
    {
        $this->htmlConstraint = $htmlConstraint;
        $this->twigConstraint = $twigConstraint;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'constraints' => array(
                $this->htmlConstraint,
                $this->twigConstraint,
            )
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'html';
    }

    /**
     * @return string|\Symfony\Component\Form\FormTypeInterface|null
     */
    public function getParent()
    {
        return 'textarea';
    }
}


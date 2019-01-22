<?php

namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mapbender\CoreBundle\Form\DataTransformer\ObjectIdTransformer;

/**
 * Class LayersetInstancesAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class LayersetInstancesAdminType extends AbstractType
{
    protected $container;

    /**
     * LayersetInstancesAdminType constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'layerset_instances';
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return 'entity';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $type = $this;
        $resolver->setDefaults(array(
            'layerset' => null,
            'class' => 'MapbenderCoreBundle:SourceInstance',
            'property' => 'title',
            'query_builder' => function(Options $options) use ($type) {
            $layerset = $options['layerset'];
            $repository = $type->getContainer()->get('doctrine')->getRepository($options['class']);
            return $repository->createQueryBuilder('inst')
                    ->select('inst')
                    ->where('inst.layerset = :ls')
                    ->setParameter('ls', $layerset);
        }));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager = $this->container->get('doctrine')->getManager();
        $transformer = new ObjectIdTransformer($entityManager, 'MapbenderCoreBundle:SourceInstance');
        $builder->addModelTransformer($transformer);
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $choices = $view->vars['choices'];
        $translator = $this->container->get('translator');

        usort($choices,
            function($a, $b) use ($translator) {
            return strcasecmp($translator->trans($a->label), $translator->trans($b->label));
        });

        $view->vars = array_replace($view->vars, array(
            'choices' => $choices
        ));
    }
}

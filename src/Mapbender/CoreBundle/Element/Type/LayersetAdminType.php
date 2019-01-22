<?php
namespace Mapbender\CoreBundle\Element\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mapbender\CoreBundle\Form\DataTransformer\ObjectIdTransformer;

/**
 * Class LayersetAdminType
 * @package Mapbender\CoreBundle\Element\Type
 */
class LayersetAdminType extends AbstractType
{
    protected $container;

    /**
     * LayersetAdminType constructor.
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
        return 'app_layerset';
    }

    /**
     * @return string|\Symfony\Component\Form\FormTypeInterface|null
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
            'application' => null,
            'class' => 'MapbenderCoreBundle:Layerset',
            'property' => 'title',
            'query_builder' => function(Options $options) use ($type) {
                $repository = $type->getContainer()->get('doctrine')->getRepository($options['class']);
                return $repository->createQueryBuilder('ls')
                        ->select('ls')
                        ->where('ls.application = :appl')
                        ->setParameter('appl', $options['application']);
            }));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager = $this->container->get('doctrine')->getManager();
        $transformer = new ObjectIdTransformer($entityManager,
            'MapbenderCoreBundle:Layerset');
        $builder->addModelTransformer($transformer);
    }
}

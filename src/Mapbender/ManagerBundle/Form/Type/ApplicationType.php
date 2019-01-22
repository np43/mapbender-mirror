<?php
namespace Mapbender\ManagerBundle\Form\Type;

use FOM\UserBundle\Form\Type\ACLType;
use Mapbender\CoreBundle\Entity\RegionProperties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ApplicationType
 * @package Mapbender\ManagerBundle\Form\Type
 */
class ApplicationType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'application';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'available_templates' => array(),
            'available_properties' => array(),
            'maxFileSize' => 0,
            'screenshotHeight' => 0,
            'screenshotWidth' => 0
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
                'attr' => array(
                    'title' => 'The application title, as shown in the browser '
                    . 'title bar and in lists.')))
            ->add('slug', TextType::class, array(
                'attr' => array(
                    'title' => 'The URL title (slug) is based on the title and used in the '
                    . 'application URL.')))
            ->add('description', TextareaType::class, array(
                'required' => false,
                'attr' => array(
                    'title' => 'The description is used in overview lists.')))
            ->add('template', ChoiceType::class, array(
                'choices' => $options['available_templates'],
                'attr' => array(
                    'title' => 'The HTML template used for this application.')))
            ->add('screenshotFile', FileType::class, array(
                'label' => 'Screenshot',
                'attr' => array(
                    'required' => false,
                    'accept'=>'image/*')))
            ->add('removeScreenShot', HiddenType::class,array(
                'mapped' => false))
            ->add('uploadScreenShot', HiddenType::class,array(
                'mapped' => false))
            ->add('maxFileSize', HiddenType::class,array(
                'mapped' => false,
                'data' => $options['maxFileSize']))
            ->add('screenshotWidth', HiddenType::class,array(
                'mapped' => false,
                'data' => $options['screenshotWidth']))
            ->add('screenshotHeight', HiddenType::class,array(
                'mapped' => false,
                'data' => $options['screenshotHeight']))
            ->add('custom_css', TextareaType::class, array(
                'required' => false))

            // Security
            ->add('published', CheckboxType::class,
                array(
                'required' => false,
                'label' => 'Published'));

        $app = $options['data'];
        foreach ($options['available_properties'] as $region => $properties) {
            $data = "";

            /** @var RegionProperties $regProps */
            foreach ($app->getRegionProperties() as $key => $regProps) {
                if ($regProps->getName() === $region) {
                    $help = $regProps->getProperties();
                    if (array_key_exists('name', $help)) {
                        $data = $help['name'];
                    }
                }
            }

            $choices = array();
            foreach ($properties as $values) {
                $choices[$values['name']] = $values['label'];
            }
            $builder->add($region, ChoiceType::class, array(
                'property_path' => '[' . $region . ']',
                'required' => false,
                'mapped' => false,
                'expanded' => true,
                'data' => $data,
                'choices' => $choices
            ));
        }

        // Security
        $builder->add('acl', ACLType::class, array(
            'mapped' => false,
            'data' => $options['data'],
            'permissions' => 'standard::object'));
    }
}

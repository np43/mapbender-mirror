<?php

namespace Mapbender\WmsBundle\Form\EventListener;

use Mapbender\WmsBundle\Component\DimensionInst;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * DimensionSubscriber class
 */
class DimensionSubscriber implements EventSubscriberInterface
{

    /**
     * Returns defined events
     *
     * @return array events
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    /**
     * Presets a form data
     *
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        $this->addFields($form, $data);
    }

    /**
     * @param FormInterface $form
     * @param DimensionInst $data
     */
    protected function addFields($form, $data)
    {
        $isVordefined = $data && $data->getOrigextent();

        $form->add('creator', HiddenType::class,  array(
                'auto_initialize' => false,
                'read_only' => $isVordefined,
                'required' => true,
            ))
            ->add('type', HiddenType::class, array(
                'auto_initialize' => false,
                'read_only' => $isVordefined,
                'required' => true,
            ))
            ->add('name', TextType::class, array(
                'auto_initialize' => false,
                'read_only' => $isVordefined,
                'required' => true,
            ))
            ->add('units', TextType::class, array(
                'auto_initialize' => false,
                'read_only' => $isVordefined,
                'required' => false,
                'attr' => array(
                    'data-name' => 'units',
                ),
            ))
            ->add('unitSymbol', TextType::class, array(
                'auto_initialize' => false,
                'read_only' => $isVordefined,
                'required' => false,
                'attr' => array(
                    'data-name' => 'unitSymbol',
                ),
            ))
            ->add('multipleValues', CheckboxType::class, array(
                'auto_initialize' => false,
                'disabled' => $isVordefined,
                'required' => false,
            ))
            ->add('nearestValue', CheckboxType::class, array(
                'auto_initialize' => false,
                'disabled' => $isVordefined,
                'required' => false,
            ))
            ->add('current', CheckboxType::class, array(
                'auto_initialize' => false,
                'disabled' => $isVordefined,
                'required' => false,
            ))
        ;
        $this->addExtentFields($form, $data);

        if ($isVordefined) {
            $dataArr = $data->getData($data->getExtent());
            $dataOrigArr = $data->getData($data->getOrigextent());
        } else {
            $dataArr = $dataOrigArr = $data->getData($data->getExtent());
        }
            if ($data->getType() === $data::TYPE_SINGLE) {
                $form
                    ->add('extentEdit', TextType::class, array(
                        'required' => true,
                        'auto_initialize' => false,
                    ))
                ;
            } elseif ($data->getType() === $data::TYPE_MULTIPLE) {
                $choices = array_combine($dataOrigArr, $dataOrigArr);
                $form
                    ->add('extentEdit', ChoiceType::class, array(
                        'data' => $dataArr,
                        'mapped' => false,
                        'choices' => $choices,
                        'auto_initialize' => false,
                        'multiple' => true,
                        'required' => true,
                    ))
                    ->add('default', ChoiceType::class, array(
                        'choices' => $choices,
                        'auto_initialize' => false,
                    ))
                ;
            } elseif ($data->getType() === $data::TYPE_INTERVAL) {
                $form
                    ->add('extentEdit', TextType::class, array(
                        'required' => true,
                        'auto_initialize' => false,
                    ))
                    ->add('default', TextType::class, array(
                        'auto_initialize' => false,
                        'read_only' => $isVordefined,
                        'required' => false,
                    ))
                ;
            }
    }

    /**
     * @param FormInterface $form
     * @param DimensionInst $data
     */
    protected function addExtentFields($form, $data)
    {
        $form
            ->add('extent', HiddenType::class, array(
                'required' => true,
                'auto_initialize' => false,
                'attr' => array(
                    'data-extent' => 'group-dimension-extent',
                    'data-name' => 'extent',
                ),
            ))
            ->add('origextent', HiddenType::class, array(
                'required' => true,
                'auto_initialize' => false,
                'mapped' => false,
                'attr' => array(
                    'data-extent' => 'group-dimension-origextent',
                    'data-name' => 'origextent',
                ),
            ))
        ;

        $dimJs = $data->getConfiguration();
        $form
            ->add('json', HiddenType::class, array(
                'required' => true,
                'data' => json_encode($dimJs),
                'auto_initialize' => false,
            ))
        ;
    }
}

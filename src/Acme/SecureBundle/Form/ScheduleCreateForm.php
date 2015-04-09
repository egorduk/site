<?php

namespace Acme\SecureBundle\Form;

use Helper\Helper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class ScheduleCreateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('selectorSubject', 'genemu_jqueryselect2_entity', array(
                    'attr' => array(
                        //'class' => 'form-control',
                        'title' => 'Внимание!',
                        'data-content' => "Вы должны выбрать предмет!",
                        'data-trigger' => 'hover',
                    ),
                    'mapped' => true,
                    'required' => true,
                    'label'=>'Предмет:',
                    'class' => 'Acme\SecureBundle\Entity\Subject',
                    //'property' => 'child_name',
                    'property' => 'name',
                    'empty_value' => '',
                    //'group_by' => 'parent_name'
                    'group_by' => 'name'
                ));

        $builder->addEventListener(FormEvents::POST_BIND, function(FormEvent $event)
        {
           /* $form = $event->getForm();
            $task = $form->get('fieldTask')->getData();
            $task = strip_tags($task);

            if ($task != null) {
                $task = Helper::convertFromUtfToCp($task);

                if (strlen($task) < 5) {
                    $form->get('fieldTask')->addError(new FormError('Введите описание более конкретно!'));
                }
            }*/
        });
    }

    public function getName()
    {
        return 'scheduleCreateForm';
    }
}

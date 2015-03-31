<?php

namespace Acme\AuthBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Util\StringUtils;


class RegForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('fieldSurname', 'text', array('label'=>'', 'required' => true, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 25, 'maxlength' => 25, 'placeholder' => 'Фамилия')))
                ->add('fieldName', 'text', array('label'=>'', 'required' => true, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 25, 'maxlength' => 25, 'placeholder' => 'Имя')))
                ->add('fieldPatronymic', 'text', array('label'=>'', 'required' => true, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 25, 'maxlength' => 25, 'placeholder' => 'Отчество')))
                ->add('fieldInstitute', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldLogin, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Институт')))
                ->add('fieldChair', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldLogin, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Кафедра')))
                ->add('fieldSpeciality', 'text', array('label'=>'', 'required' => true, 'data' => $options['data']->fieldLogin, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Специальность')))
                ->add('fieldGroup', 'text', array('label'=>'', 'required' => true, 'data' => $options['data']->fieldLogin, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Группа')))
                ->add('fieldInfo', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldLogin, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Дополнительно')))
                ->add('fieldWork', 'text', array('label'=>'', 'required' => true, 'data' => $options['data']->fieldLogin, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Место работы и должность')))
                ->add('fieldDescribe', 'text', array('label'=>'', 'required' => true, 'data' => $options['data']->fieldLogin, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Что связывает вас с университетом')))
                ->add('fieldPass', 'password', array('label'=>'', 'required' => true, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Пароль')))
                ->add('fieldPassApprove', 'password', array('label'=>'', 'required' => true, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Повторите пароль')))
                ->add('fieldEmail', 'text', array('label'=>'', 'required' => true, 'data' => $options['data']->fieldEmail, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 25, 'placeholder' => 'Почта')))
                ->add('fieldOptions', 'hidden', array())
                ->add('reg', 'submit', array('attr' => array('class' => 'hidden')))
                ->add('back', 'button', array('attr' => array('class' => 'hidden')));

        $builder->addEventListener(FormEvents::POST_BIND, function(FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            if ($data->getFieldPass() !== null && $data->getFieldPassApprove() !== null) {
                $newPassword = $form->get('fieldPass')->getData();
                $approvePassword = $form->get('fieldPassApprove')->getData();
                if (!StringUtils::equals($newPassword, $approvePassword)) {
                    $form->get('fieldPassApprove')->addError(new FormError('Введенные пароли не совпадают!'));
                    $form->get('fieldPass')->addError(new FormError('Введенные пароли не совпадают!'));
                }
            }
        });
    }

    public function getName() {
        return 'formReg';
    }
}

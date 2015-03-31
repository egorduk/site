<?php

namespace Acme\SecureBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Util\StringUtils;


class ProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('fieldSurname', 'text', array('label'=>'', 'required' => false, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 25, 'maxlength' => 25, 'placeholder' => 'Фамилия')))
                ->add('fieldName', 'text', array('label'=>'', 'required' => false, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 25, 'maxlength' => 25, 'placeholder' => 'Имя')))
                ->add('fieldPatronymic', 'text', array('label'=>'', 'required' => false, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 25, 'maxlength' => 25, 'placeholder' => 'Отчество')))
                ->add('fieldInstitute', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldInstitute, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Институт')))
                ->add('fieldChair', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldChair, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Кафедра')))
                ->add('fieldSpeciality', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldSpeciality, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Специальность')))
                ->add('fieldGroup', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldGroup, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Группа')))
                //->add('fieldInfo', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldInfo, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Дополнительно')))
                ->add('fieldWork', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldWork, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Место работы и должность')))
                ->add('fieldCourse', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldCourse, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Курс')))
                //->add('fieldDescribe', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldDescribe, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Что связывает вас с университетом')))
                ->add('fieldPassOld', 'password', array('label'=>'', 'required' => false, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Старый')))
                ->add('fieldPassNew', 'password', array('label'=>'', 'required' => false, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Новый')))
                ->add('fieldPassApprove', 'password', array('label'=>'', 'required' => false, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 12, 'placeholder' => 'Повтор нового')))
                ->add('fieldEmail', 'text', array('label'=>'', 'required' => true, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 25, 'placeholder' => 'Почта')))
                ->add('fieldOptions', 'hidden', array())
                ->add('fieldTypeProfile', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldTypeProfile, 'attr' => array('class' => 'form-control', 'disabled' => true,'title' => '', 'size' => 20, 'maxlength' => 25, 'placeholder' => 'День рождения')))
                ->add('fieldDateBirthday', 'text', array('label'=>'', 'required' => false, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 25, 'placeholder' => 'День рождения')))
                ->add('fieldAbout', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldAbout, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 25, 'placeholder' => 'Немного о себе')))
                ->add('fieldPhone', 'text', array('label'=>'', 'required' => false, 'data' => $options['data']->fieldPhone, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 20, 'maxlength' => 25, 'placeholder' => 'Телефоны')))
                ->add('fieldIsShowEmail', 'checkbox', array('label'=>'Показывать другим пользователям почту', 'required' => false, 'data' => (bool)($options['data']->fieldIsShowEmail), 'attr' => array('class' => 'form-control', 'title' => '')))
                ->add('save', 'submit', array('attr' => array('class' => 'hidden')))
                ->add('saveNewPassword', 'submit', array('attr' => array('class' => 'hidden')))
                ->add('writeNewMessage', 'submit', array('attr' => array('class' => 'hidden')))
                ->add('deleteUser', 'submit', array('attr' => array('class' => 'hidden')))
                ->add('back', 'button', array('attr' => array('class' => 'hidden')));

        $builder->addEventListener(FormEvents::POST_BIND, function(FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            //var_dump($data);die;
            if ($data->getFieldPassNew() !== null && $data->getFieldPassApprove() !== null) {
                $newPassword = $form->get('fieldPassNew')->getData();
                $approvePassword = $form->get('fieldPassApprove')->getData();
                if (!StringUtils::equals($newPassword, $approvePassword)) {
                    $form->get('fieldPassApprove')->addError(new FormError('Введенные пароли не совпадают!'));
                    $form->get('fieldPassNew')->addError(new FormError('Введенные пароли не совпадают!'));
                }
            }
        });
    }

    public function getName() {
        return 'formProfile';
    }
}

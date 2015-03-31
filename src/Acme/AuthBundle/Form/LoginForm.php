<?php

namespace Acme\AuthBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('fieldEmail', 'text', array('label'=>'Email', 'required' => true, 'attr' => array('class' => 'form-control', 'size' => 50, 'maxlength' => 25, 'placeholder' => 'Введите почту')))
                ->add('fieldPass', 'password', array('label'=>'Пароль', 'required' => true, 'attr' => array('class' => 'form-control', 'size' => 50, 'maxlength' => 10, 'placeholder' => 'Введите пароль')))
                ->add('enter', 'submit', array('label'=>'Вход', 'attr' => array('class' => 'hidden')));
    }

    public function getName() {
        return 'formLogin';
    }
}

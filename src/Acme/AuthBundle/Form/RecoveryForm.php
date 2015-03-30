<?php

namespace Acme\AuthBundle\Form;

use Helper\Helper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;


class RecoveryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fieldSurname', 'text', array('label'=>'', 'required' => true, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 25, 'maxlength' => 25, 'placeholder' => 'Фамилия')))
                ->add('fieldName', 'text', array('label'=>'', 'required' => true, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 25, 'maxlength' => 25, 'placeholder' => 'Имя')))
                ->add('fieldPatronymic', 'text', array('label'=>'', 'required' => true, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 25, 'maxlength' => 25, 'placeholder' => 'Отчество')))
                ->add('recovery', 'submit', array('label'=>'Восстановить пароль', 'attr' => array('class' => 'hidden')));

        $builder->addEventListener(FormEvents::POST_BIND, function(FormEvent $event) {
           /* $form = $event->getForm();
            $email = $form->get('fieldEmail')->getData();
            if ($email != null) {
                $emailConstraint = new EmailConstraint();
                $container = Helper::getContainer();
                $errors = $container->get('validator')->validateValue($email, $emailConstraint);
                if (count($errors)) {
                    $form->get('fieldEmail')->addError(new FormError('Email введен неправильно!'));
                }
            }*/
        });
    }

    public function getName()
    {
        return 'formRecovery';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }
}

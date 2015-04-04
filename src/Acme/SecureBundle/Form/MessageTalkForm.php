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


class MessageTalkForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('fieldTheme', 'text', array('label'=>'', 'required' => false, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 25, 'maxlength' => 25, 'placeholder' => 'Тема')))
                ->add('fieldMessage', 'text', array('label'=>'', 'required' => false, 'attr' => array('class' => 'form-control', 'title' => '', 'size' => 25, 'maxlength' => 255, 'placeholder' => 'Сообщение')))
                ->add('fieldShowAll', 'checkbox', array('label'=>'Для всех', 'required' => false, 'attr' => array('class' => 'form-control', 'title' => '')))
                ->add('fieldResponseId', 'hidden')
                ->add('send', 'submit', array('attr' => array('class' => 'hidden')));
    }

    public function getName() {
        return 'formMessageTalk';
    }
}

<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\User;
use AppBundle\Utils\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('newPassword', PasswordType::class, [
                'label'=>'Password',
                'required'=>false,
            ])
            ->add('active', ChoiceType::class, [
                'choices'=>Config::getYesStatus(),
                'multiple'=>false,
                'expanded'=>true,
            ])
            ->add('locked', ChoiceType::class, [
                'choices'=>Config::getYesStatus(),
                'multiple'=>false,
                'expanded'=>true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>User::class,
        ]);
    }
}

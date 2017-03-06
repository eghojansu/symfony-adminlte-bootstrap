<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Admin;
use AppBundle\Utils\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('gender', ChoiceType::class, [
                'choices'=>Config::getGenders(),
                'multiple'=>false,
                'expanded'=>true,
            ])
            ->add('birthplace')
            ->add('birthdate', DateType::class, [
                'widget'=>'single_text',
                'html5'=>false,
                'format'=>'dd/MM/yyyy',
                'attr'=>[
                    'data-provide'=>'datepicker',
                    'data-date-format'=>'mm/dd/yyyy',
                ],
            ])
            ->add('user', UserType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>Admin::class,
        ]);
    }
}

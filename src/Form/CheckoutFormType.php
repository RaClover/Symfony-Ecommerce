<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('checkoutFirstName' , TextType::class , [
                'attr' => [
                    'class' => '',
                    'name' => 'checkoutFirstName'
                ]
            ])
            ->add('checkoutLastName' , TextType::class , [
                'attr' => [
                    'name' => 'checkoutLastName'
                ]
            ])
            ->add('checkoutEmail' , EmailType::class , [
                'attr' => [
                    'name' => 'checkoutEmail'
                ]
            ])
            ->add('checkoutCountry' , TextType::class , [
                'attr' => [
                    'name' => 'checkoutCountry'
                ]
            ])
            ->add('checkoutCity' , TextType::class , [
                'attr' => [
                    'name' => 'checkoutCity'
                ]
            ])
            ->add('checkoutStreet' , TextType::class , [
                'attr' => [
                    'name' => 'checkoutStreet'
                ]
            ])
            ->add('checkoutPhone' , NumberType::class , [
                'attr' => [
                    'name' => 'checkoutPhone'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

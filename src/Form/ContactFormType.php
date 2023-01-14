<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name' , TextType::class , [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('email' , EmailType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('subject' , TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('message' , TextareaType::class, [
                'attr' => [
                    'class' => 'text-editor'
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

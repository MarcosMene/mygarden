<?php

namespace App\Form\Users;

use App\Entity\ReviewClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'empty_data' => '',
                'label' => 'First name',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex.John',
                    'minlength' => 3,
                    'maxlength' => 41,
                    'class' => 'w-full rounded bg-white shadow shadow-gray-100  appearance-none outline-none py-2 px-3 leading-4 text-black  invalid:[&:not(:placeholder-shown):not(:focus)]:border-red peer  py-2 px-3 leading-4 text-black',
                ]
            ])
            ->add('rate', ChoiceType::class, [
                'empty_data' => '',
                'label' => 'Your rate',
                'placeholder' => 'Choose one',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'attr' => [
                    'class' => 'w-full rounded bg-white shadow shadow-gray-100   py-2 px-3 leading-4 text-black  invalid:[&:not(:placeholder-shown):not(:focus)]:border-red peer  py-2 px-3 leading-4 text-gray',
                ],
                'multiple' => false,
            ])
            ->add('comment', TextareaType::class, [
                'empty_data' => '',
                'label' => 'Give your comment',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex.This product...',
                    'minlength' => 20,
                    'maxlength' => 151,
                    'class' => 'w-full rounded bg-white shadow shadow-gray-100  appearance-none outline-none py-2 px-3 leading-4 text-black  invalid:[&:not(:placeholder-shown):not(:focus)]:border-red peer  py-2 px-3 leading-4 text-black',
                    'rows' => 5
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReviewClient::class,
        ]);
    }
}

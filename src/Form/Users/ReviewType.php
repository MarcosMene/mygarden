<?php

namespace App\Form\Users;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rate', ChoiceType::class, [
                'empty_data' => '',
                'label' => 'Rate of product',
                'placeholder' => 'Choose one',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'attr' => [
                    'class' => 'w-fit rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray',
                ],
                'multiple' => false,
            ])
            ->add('review', TextareaType::class, [
                'empty_data' => '',
                'label' => 'Your review',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex.This product...',
                    'minlength' => 20,
                    'maxlength' => 200,
                    'class' => 'w-full rounded   bg-white p-3 shadow shadow-gray-100 text-base',
                    'rows' => 5
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,

            //CSRF TOKEN
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'review_item',  // UNIQUE TOKEN ID FOR THE REVIEW FORM
        ]);
    }
}

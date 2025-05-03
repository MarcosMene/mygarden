<?php

namespace App\Form\Users;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // CHECK IF THE FORM IS FOR CREATING A NEW CARRIER (NO EXISTING CARRIER PASSED)

        $builder
            ->add('name', TextType::class, [
                'label' => 'Name of your address',
                'attr' => [
                    'placeholder' => 'Ex. Home or office',
                    'minlength' => 3,
                    'maxlength' => 26,
                    'class' => 'w-full rounded bg-white  shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Your first name',
                'attr' => [
                    'placeholder' => 'Ex. John',
                    'minlength' => 3,
                    'maxlength' => 26,
                    'class' => 'w-full rounded bg-white  shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Your last name',
                'attr' => [
                    'placeholder' => 'Ex. Doe',
                    'minlength' => 3,
                    'maxlength' => 26,
                    'class' => 'w-full rounded bg-white  shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Company name(optional)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Company name',
                    'minlength' => 3,
                    'maxlength' => 26,
                    'class' => 'w-full rounded bg-white  shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Your address',
                'attr' => [
                    'placeholder' => 'Ex. 809 Boulevard Tower',
                    'minlength' => 10,
                    'maxlength' => 61,
                    'class' => 'w-full rounded bg-white  shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Postal code / ZIP code',
                'attr' => [
                    'placeholder' => '12345 or MDF233R',
                    'minlength' => 5,
                    'maxlength' => 11,
                    'class' => 'w-full rounded bg-white  shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Your city',
                'attr' => [
                    'placeholder' => 'Ex. Montreal',
                    'minlength' => 3,
                    'maxlength' => 26,
                    'class' => 'w-full rounded bg-white  shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Your phone number',
                'attr' => [
                    'placeholder' => '+1601020304 or 0909090909',
                    'minlength' => 8,
                    'maxlength' => 25,
                    'class' => 'w-full rounded bg-white  shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
            'csrf_protection' => true, // THIS IS THE DEFAULT, ENABLING CSRF PROTECTION
            'csrf_token_id' => 'post_item', // UNIQUE CSRF TOKEN ID FOR THIS FORM
        ]);
    }
}

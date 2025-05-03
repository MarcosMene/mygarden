<?php

namespace App\Form\Users;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'First name',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Your first name is required',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'Your first name must be at least {{ limit }} characters long',
                        'max' => 25,
                        'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s\-]/',
                        'message' => 'Your first name can only contain letters, spaces, or hyphens',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'John',
                    'minlength' => 3,
                    'maxlength' => 25,
                    'class' => 'w-full rounded bg-white shadow shadow-gray-100  appearance-none outline-none py-2 px-3 leading-4 text-black  invalid:[&:not(:placeholder-shown):not(:focus)]:border-red peer  py-2 px-3 leading-4 text-black'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last name',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Your last name is required',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'Your last name must be at least {{ limit }} characters long',
                        'max' => 25,
                        'maxMessage' => 'Your last name cannot be longer than {{ limit }} characters',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ]/',
                        'message' => 'Your last name can only contain letters, spaces, or hyphens',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Enter your last name',
                    'minlength' => 3,
                    'maxlength' => 25,
                    'class' => 'w-full rounded bg-white shadow shadow-gray-100  appearance-none outline-none py-2 px-3 leading-4 text-black  invalid:[&:not(:placeholder-shown):not(:focus)]:border-red peer  py-2 px-3 leading-4 text-black'
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Your email is required.',
                    ]),
                    new Assert\Email([
                        'message' => 'Please enter a valid email',
                    ]),
                    new Assert\Length([
                        'min' => 10,
                        'minMessage' => 'Your email must be at least {{ limit }} characters long',
                        'max' => 50,
                        'maxMessage' => 'Your email cannot be longer than {{ limit }} characters',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'name@example.com',
                    'min' => 10,
                    'max' => 50,
                    'class' => 'w-full rounded bg-white shadow shadow-gray-100  appearance-none outline-none py-2 px-3 leading-4 text-black  invalid:[&:not(:placeholder-shown):not(:focus)]:border-red peer  py-2 px-3 leading-4 text-black'
                ]
            ])

            ->add('password', RepeatedType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Your password is required.',
                    ]),

                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Your password must be at least {{ limit }} characters long.',
                        'max' => 16,
                        'maxMessage' => 'Your password cannot be longer than {{ limit }} characters.',
                    ]),
                ],
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => [
                    'label' => 'Password',
                    'attr' => [
                        'minlength' => 6,
                        'maxlength' => 16,
                        'placeholder' => 'Password',
                        'class' => 'w-full rounded bg-white shadow shadow-gray-100  appearance-none outline-none py-2 px-3 leading-4 text-black  invalid:[&:not(:placeholder-shown):not(:focus)]:border-red peer  py-2 px-3 leading-4 text-black mb-4'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                    'attr' => [
                        'minlength' => 6,
                        'maxlength' => 16,
                        'placeholder' => 'Enter your password again',
                        'class' => 'w-full rounded bg-white shadow shadow-gray-100  appearance-none outline-none py-2 px-3 leading-4 text-black  invalid:[&:not(:placeholder-shown):not(:focus)]:border-red peer  py-2 px-3 leading-4 text-black'
                    ]
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

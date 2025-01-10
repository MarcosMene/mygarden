<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_password', RepeatedType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Your password is required.',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Your password must be at least {{ limit }} characters long.',
                        'max' => 16,
                        'maxMessage' => 'Your password cannot be longer than {{ limit }} characters.',
                    ]),
                ],
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'The new passwords fields must match.',
                'required' => true,
                'label' => 'New password',
                'first_options'  => [
                    'label' => 'New password',
                    'attr' => [
                        'placeholder' => 'Enter your new password',
                        'minlength' => 8,
                        'maxlength' => 16,
                        'class' => 'w-full rounded bg-white shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                    ],
                    'hash_property_path' => 'password' //password will be hashed
                ],
                'second_options' => [
                    'label' => 'Confirm new password',
                    'attr' => [
                        'placeholder' => 'Cofirm your new password',
                        'minlength' => 8,
                        'maxlength' => 16,
                        'class' => 'w-full rounded bg-white shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                    ]
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modify password',
                'attr' => [
                    'class' => 'py-2 px-4 w-full  mt-6 hover:bg-white hover:text-primary transition ease-in-out delay-100 uppercase border border-primary text-base font-medium text-white  bg-primary rounded-sm group-invalid:pointer-events-none group-invalid:opacity-60 group-invalid:disabled"'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => User::class,
        ]);
    }
}

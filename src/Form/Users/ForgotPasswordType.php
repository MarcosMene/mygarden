<?php

namespace App\Form\Users;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ForgotPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                    'minlength' => 10,
                    'maxlength' => 50,
                    'class' => 'w-full rounded invalid:border-red-500  bg-white p-3 shadow shadow-gray-100 mt-2 appearance-none outline-none  invalid:[&:not(:placeholder-shown):not(:focus)]:border-red peer  py-2 px-3 leading-4 text-black'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // CONFIGURE YOUR FORM OPTIONS HERE
        ]);
    }
}

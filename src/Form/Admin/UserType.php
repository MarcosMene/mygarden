<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // CHECK IF THE FORM IS FOR CREATING A NEW CARRIER (NO EXISTING CARRIER PASSED)
        $isEdit = $options['is_edit'];

        $builder
            ->add('firstname', TextType::class, [
                'empty_data' => '',
                'label' => 'First name',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex.John',
                    'minlength' => 3,
                    'maxlength' => 41,
                    'class' => 'w-full rounded bg-white shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ]
            ])
            ->add('lastname', TextType::class, [
                'empty_data' => '',
                'label' => 'Last name',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex.Doe',
                    'minlength' => 5,
                    'maxlength' => 26,
                    'class' => 'w-full rounded bg-white shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ]
            ])
            ->add('profileImageFile', VichImageType::class, [
                'required' => !$isEdit,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'delete_label' => 'Delete image',
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
                    'minlength' => 10,
                    'maxlength' => 50,
                    'class' => 'w-full rounded invalid:border-red-500  bg-white p-3 shadow shadow-gray-100 mt-2 appearance-none outline-none  invalid:[&:not(:placeholder-shown):not(:focus)]:border-red peer  py-2 px-3 leading-4 text-black'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ],
                'multiple' => true,  // ALLOW MULTIPLE SELECTION
                'expanded' => true,  // RENDER AS CHECKBOXES
                'label' => 'Roles',
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700 mt-4 space-x-2',  // LABEL STYLES
                ],
                'attr' => [
                    'class' => 'flex flex-wrap space-x-2 ',  // FLEX WITH WRAPPING
                ],
                'csrf_protection' => true,  // ENABLE CSRF PROTECTION
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}

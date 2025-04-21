<?php

namespace App\Form\Admin;

use App\Entity\Employees;
use App\Entity\Posts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // CHECK IF THE FORM IS FOR CREATING A NEW PRODUCT (NO EXISTING PRODUCT PASSED)
        $isEdit = $options['is_edit'];

        $builder
            ->add('firstName', TextType::class, [
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
            ->add('lastName', TextType::class, [
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
            ->add('imageFile', VichImageType::class, [
                'empty_data' => '',
                'label' => 'Employee Image',
                'required' => !$isEdit, // IMAGE REQUIRED IF CREATING PRODUCT
                'attr' => ['class' => 'form-input mt-1 block w-full'],
                'constraints' => !$isEdit ? [
                    new NotBlank(),
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (JPG,JPEG, WEBP or PNG)',
                    ]),
                ] : [],
            ])
            ->add('orderAppear', ChoiceType::class, [
                'empty_data' => '',
                'label' => 'Choose order',
                'placeholder' => 'Choose a order of apperance on the page',
                'required' => true,
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                ],
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray-500',
                ],
                'multiple' => false,
            ])
            ->add('employeePost', EntityType::class, [
                'class' => Posts::class,
                'label' => 'Post',
                'placeholder' => 'Choose a post',
                'choice_label' => 'name',
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray-500',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employees::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}

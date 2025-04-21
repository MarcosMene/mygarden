<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Entity\Header;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class HeaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // CHECK IF THE FORM IS FOR CREATING A NEW PRODUCT (NO EXISTING PRODUCT PASSED)
        $isEdit = $options['is_edit'];

        $builder
            ->add('categoryProduct', EntityType::class, [
                'empty_data' => '',
                'label' => 'Category',
                'placeholder' => 'Choose a category',
                'required' => true,
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray-500',
                ],
            ])

            ->add('content', TextType::class, [
                'empty_data' => '',
                'label' => 'Content',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Today is a beautiful...',
                    'minlength' => 10,
                    'maxlength' => 51,
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ],
            ])
            ->add('paragraph', TextType::class, [
                'empty_data' => '',
                'label' => 'Message',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Today is a beautiful...',
                    'minlength' => 10,
                    'maxlength' => 51,
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ],
            ])
            ->add('backgroundColor', ChoiceType::class, [
                'empty_data' => '',
                'placeholder' => 'Choose a color for background',
                'label' => 'Color of background',
                'choices' => [
                    'Orange' => '#fef5ea',
                    'Green' => '#f0f8ec',
                    'White' => "#fcfcfc",
                ],
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray-500',
                ],
                'multiple' => false,
                'required' => true,
            ])
            ->add('buttonTitle', TextType::class, [
                'empty_data' => '',
                'label' => 'Title of button',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Shop now',
                    'minlength' => 3,
                    'maxlength' => 16,
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black lowercase',
                ],
            ])

            ->add('imageFile', VichImageType::class, [
                'empty_data' => '',
                'label' => 'Header Image',
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
                    '3' => 3,
                    '4' => 4,
                ],
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray-500',
                ],
                'multiple' => false,
            ])
            ->add('sideImage', ChoiceType::class, [
                'empty_data' => '',
                'label' => 'Side of image',
                'placeholder' => 'Choose left or right',
                'required' => true,
                'choices' => [
                    'left' => "left",
                    'right' => "right",
                ],
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray-500',
                ],
                'multiple' => false,
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Header::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}

<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Entity\ColorProduct;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // CHECK IF THE FORM IS FOR CREATING A NEW PRODUCT (NO EXISTING PRODUCT PASSED)
        $isEdit = $options['is_edit'];

        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'label' => 'Name of product',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex.Ariscalis mantras',
                    'minlength' => 3,
                    'maxlength' => 41,
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                ],
            ])

            ->add('category', EntityType::class, [
                'empty_data' => '',
                'label' => 'Category',
                'placeholder' => 'Choose a category',
                'required' => true,
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray',
                ],
            ])

            ->add('imageFile', VichImageType::class, [
                'empty_data' => '',
                'label' => 'Product Image',
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
            ->add('description', TextareaType::class, [
                'empty_data' => '',
                'label' => 'Description of product',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex.This product...',
                    'minlength' => 20,
                    'maxlength' => 200,
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                    'rows' => 5,
                ],
            ])
            ->add('price', NumberType::class, [
                'empty_data' => '',
                'label' => 'Price of product',
                'required' => true,
                'attr' => [
                    'placeholder' => '100,00',
                    'minlength' => 2,
                    'maxlength' => 8,
                    'class' => 'w-full rounded bg-white  shadow shadow-gray-100 shadow shadow-gray-100 mt-2  py-2 px-3 leading-4'
                ],
            ])
            ->add('promotion', ChoiceType::class, [
                'label' => 'Promotion',
                'placeholder' => 'Choose a tax promotion',
                'choices' => [
                    '0%' => 0,
                    '5%' => 5,
                    '10%' => 10,
                    '15%' => 15,
                    '20%' => 20,
                    '25%' => 25,
                    '30%' => 30,
                    '35%' => 35,
                    '40%' => 40,
                ],
                'required' => true,
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray',
                ],
            ])
            ->add('tva', ChoiceType::class, [
                'label' => 'Tax of product',
                'placeholder' => 'Tax em %',
                'required' => true,
                'choices' => [
                    '5%' => 5,
                    '10%' => 10,
                    '15%' => 15,
                    '20%' => 20,
                ],
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray',
                ],
            ])
            ->add('isSuggestion', ChoiceType::class, [
                'placeholder' => 'Choose yes or no',
                'empty_data' => '',
                'label' => 'Is a suggestion',
                'choices' => [
                    'yes' => 1,
                    'no' => 0,
                ],
                'required' => true,
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray',
                ],
                'multiple' => false,
            ])
            ->add('colorProduct', EntityType::class, [
                'class' => ColorProduct::class,
                'placeholder' => 'Choose a color',
                'choice_label' => 'color',
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}

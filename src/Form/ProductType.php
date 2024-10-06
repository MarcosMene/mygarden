<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\ColorProduct;
use App\Entity\Product;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Service\Attribute\Required;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'label' => 'Name of product',
                'required' => true,
                'constraints' => [
                    // new Assert\NotBlank([
                    //     'message' => 'Name of product is required',
                    // ]),
                    // new Assert\Length([
                    //     'min' => 3,
                    //     'max' => 40,
                    //     'minMessage' => 'Product name must be at least {{ limit }} characters long',
                    //     'maxMessage' => 'Product name must be at most {{ limit }} characters long',
                    // ]),
                    // new Assert\Regex([
                    //     'pattern' => '/^[a-zA-ZÀ-ÿ0-9\s\-_]*$/',
                    //     'message' => 'Product name can only contains letters, numbers, and underscores',
                    // ])
                ],
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
                // 'constraints' => [
                //     new Assert\NotBlank([
                //         'message' => 'Your category is required',
                //     ]),
                // ],
                'attr' => [
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 shadow shadow-gray-100 mt-2  py-2 px-3'
                ],
            ])

            ->add('imageFile', VichImageType::class, [
                'empty_data' => '',
                'label' => 'Image of product',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'empty_data' => '',
                'label' => 'Description of product',
                'required' => true,
                // 'constraints' => [
                //     new Assert\Length([
                //         'min' => 20,
                //         'max' => 200,
                //         'minMessage' => 'The description must be at least {{ limit }} characters long',
                //         'maxMessage' => 'The description must be at most {{ limit }} characters long',
                //     ]),
                //     new Assert\Regex([
                //         'pattern' => '/^[a-zA-ZÀ-ÿ0-9\s!?,.-_]*$/',
                //         'message' => 'The description can only contains letters, numbers,(,.!?),space and underscores.',
                //     ])
                // ],
                'attr' => [
                    'placeholder' => 'Ex.This product...',
                    'minlength' => 20,
                    'maxlength' => 200,
                    'class' => 'w-full rounded   bg-white p-3 shadow shadow-gray-100 text-base'
                ],
            ])

            ->add('price', NumberType::class, [
                'empty_data' => '',
                'label' => 'Price of product',
                'required' => true,
                // 'constraints' => [
                //     new Assert\Length([
                //         'min' => 2,
                //         'max' => 7,
                //         'minMessage' => 'The price must be at least {{ limit }} characters long',
                //         'maxMessage' => 'The price must be at most {{ limit }} characters long',
                //     ]),
                //     new Assert\Positive(),
                //     new Assert\Range([
                //         'min' => 10,
                //         'max' => 5000,
                //         'notInRangeMessage' => 'The price must be from {{ min }} to {{ max }}.',
                //     ])
                // ],
                'attr' => [
                    'placeholder' => '100,00',
                    'minlength' => 2,
                    'maxlength' => 8,
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 shadow shadow-gray-100 mt-2  py-2 px-3 leading-4'
                ],
            ])


            ->add('promotion', ChoiceType::class, [
                'empty_data' => '',
                'placeholder' => 'Choose a tax promotion',
                'label' => 'Promotion',
                'choices' => [
                    '0' => 0,
                    '5%' => 5,
                    '10%' => 10,
                    '15%' => 15,
                    '20%' => 20,
                    '25%' => 25,
                    '30%' => 30,
                    '35%' => 35,
                    '40%' => 40,
                ],
                'attr' => [
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 shadow shadow-gray-100 mt-2  py-2 px-3'
                ],
                'multiple' => false,
                'required' => true,
            ])
            ->add('tva', ChoiceType::class, [
                'empty_data' => '',
                'label' => 'Tax of product',
                'placeholder' => 'Tax em %',
                'choices' => [
                    '5%' => 5,
                    '10%' => 10,
                    '15%' => 15,
                    '20%' => 20,
                ],
                'attr' => [
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 shadow shadow-gray-100 mt-2  py-2 px-3'
                ],
                'multiple' => false,
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
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 shadow shadow-gray-100 mt-2  py-2 px-3'
                ],
                'multiple' => false,
            ])


            ->add('colorProduct', EntityType::class, [
                'class' => ColorProduct::class,
                'placeholder' => 'Choose a color',
                'choice_label' => 'color',
                'attr' => [
                    'minlength' => 3,
                    'maxlength' => 25,
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 shadow shadow-gray-100 mt-2  py-2 px-3'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create an account',
                'attr' => [
                    'class' => 'py-2 px-4 mt-6 hover:bg-white hover:text-primary transition ease-in-out delay-100 capitalize border border-primary text-base font-medium text-white  bg-primary rounded-sm w-fit'
                ]
            ])

        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

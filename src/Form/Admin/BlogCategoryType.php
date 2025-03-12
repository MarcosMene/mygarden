<?php

namespace App\Form\Admin;

use App\Entity\BlogCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class BlogCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            // NAME CATEGORY
            ->add('name', TextType::class, [
                'label' => 'Category',
                'attr' => [
                    'class' =>  'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray-500',
                    'placeholder' => 'Category name...',
                    'minlength' => 3,
                    'maxlength' => 21,
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'The title is required.']),
                    new Assert\Length(['min' => 3, 'max' => 20, 'minMessage' => 'The title must be at least {{limit}} characters.', 'maxMessage' => 'The title must not exceed {{limit}} characters.']),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ0-9_\-\s]{3,}$/',
                        'message' => 'The title can only contain letters, spaces, or hyphens.'
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogCategory::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}

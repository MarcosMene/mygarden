<?php

namespace App\Form\Admin;

use App\Entity\BlogArticle;
use App\Entity\BlogCategory;
use App\Entity\BlogTag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints as Assert;

class BlogArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // CHECK IF THE FORM IS FOR CREATING A NEW PRODUCT (NO EXISTING PRODUCT PASSED)
        $isEdit = $options['is_edit'];

        $builder
            // TITLE ARTICLE
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'class' => 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black',
                    'placeholder' => 'Title article...',
                    'minlength' => 3,
                    'maxlength' => 80,
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'The title is required.']),
                    new Assert\Length(['min' => 3, 'max' => 80, 'minMessage' => 'The title must be at least {{limit}} characters.', 'maxMessage' => 'The title must not exceed {{limit}} characters.']),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ0-9?!_\-\s]{3,}$/',
                        'message' => 'The title can only contain letters, spaces, or hyphens.'
                    ]),
                ],

            ])
            // CONTENT ARTICLE
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'attr' => [
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black',
                    'rows' => 5,
                    'placeholder' => 'Write here your article',
                    'minlength' => 5,
                    'maxlength' => 4001,
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'The content is required.']),
                    new Assert\Length(['min' => 10, 'max' => 4000, 'minMessage' => 'The content must be at least {{limit}} characters.', 'maxMessage' => 'The content must not exceed {{limit}} characters.']),
                    new Assert\Regex([
                        'pattern' => '~^(?=.*[a-zA-ZÀ-ÿ])[a-zA-ZÀ-ÿ0-9 ,.?!;:"\'`()/\r\n\-]{3,2000}$~u',
                        'message' => 'The title can only contain letters, spaces, or hyphens.'
                    ]),

                ],
            ])
            // CATEGORY
            ->add('category', EntityType::class, [
                'empty_data' => '',
                'label' => 'Category',
                'placeholder' => 'Choose a category',
                'required' => true,
                'class' => BlogCategory::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray',
                ],
            ])

            // TAGS
            ->add('tags', EntityType::class, [
                'label' => 'Tags (choose one or more tag)',
                'class' => BlogTag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true, // CHECKBOXES
                'required' => false, //FALSE TO DRAFT, BUT VALIDATED MANUALLY TO PUBLISH
                'constraints' => [
                    new Assert\Count(['min' => 1, 'minMessage' => 'At least one tag must be selected.']),
                ],
            ])
            // UPLOAD IMAGE OF ARTICLE
            ->add('articleImageFile', VichImageType::class, [
                'required' => !$isEdit,
                'allow_delete' => true,
                'delete_label' => 'Delete image',
            ])
            ->add('status', HiddenType::class, [
                'mapped' => false, //NO MAPPED TO ENTITY
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogArticle::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}

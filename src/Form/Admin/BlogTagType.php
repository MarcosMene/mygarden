<?php

namespace App\Form\Admin;

use App\Entity\BlogTag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class BlogTagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // CHECK IF THE FORM IS FOR CREATING A NEW PRODUCT (NO EXISTING PRODUCT PASSED)

        $builder
            // NAME TAG
            ->add('name', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'class' => 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray-500',
                    'placeholder' => 'Tag name...',
                    'minlength' => 3,
                    'maxlength' => 16,
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'The tag is required.']),
                    new Assert\Length(['min' => 3, 'max' => 15, 'minMessage' => 'The tag must be at least {{limit}} characters.', 'maxMessage' => 'The tag must not exceed {{limit}} characters.']),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ0-9]{3,}+$/',
                        'message' => 'The tag can only contain letters, spaces, or hyphens.'
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogTag::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}

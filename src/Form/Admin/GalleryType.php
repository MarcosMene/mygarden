<?php

namespace App\Form\Admin;

use App\Entity\Gallery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class GalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // CHECK IF THE FORM IS FOR CREATING A NEW PRODUCT (NO EXISTING PRODUCT PASSED)
        $isEdit = $options['is_edit'];

        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'label' => 'Name of image',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex.Ariscalis mantras',
                    'minlength' => 3,
                    'maxlength' => 41,
                    'class' => 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black',
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gallery::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}
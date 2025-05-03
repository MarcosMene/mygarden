<?php

namespace App\Form\Admin;

use App\Entity\Posts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // CHECK IF THE FORM IS FOR CREATING A NEW PRODUCT (NO EXISTING PRODUCT PASSED)
        $isEdit = $options['is_edit'];

        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'label' => 'Name of post',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex. Manager',
                    'minlength' => 3,
                    'maxlength' => 51,
                    'class' => $isEdit ? 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}

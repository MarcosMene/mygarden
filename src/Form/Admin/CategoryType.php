<?php

namespace App\Form\Admin;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // CHECK IF THE FORM IS FOR CREATING A NEW PRODUCT (NO EXISTING PRODUCT PASSED)
        $isEdit = $options['is_edit'];

        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'label' => 'Name of category',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex.Ariscalis mantras',
                    'minlength' => 3,
                    'maxlength' => 41,
                    'class' => 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}
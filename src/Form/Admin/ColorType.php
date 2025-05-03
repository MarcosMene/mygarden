<?php

namespace App\Form\Admin;

use App\Entity\ColorProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // CHECK IF THE FORM IS FOR CREATING A NEW PRODUCT (NO EXISTING PRODUCT PASSED)
        $isEdit = $options['is_edit'];

        $builder
            ->add('color', TextType::class, [
                'empty_data' => '',
                'label' => 'Color of product',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex.violet',
                    'minlength' => 3,
                    'maxlength' => 41,
                    'class' => 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ColorProduct::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}
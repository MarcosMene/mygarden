<?php

namespace App\Form;

use App\Entity\ColorProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // Check if the form is for creating a new product (no existing product passed)
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
                    'class' => 'w-full rounded bg-white p-3 shadow shadow-gray-100 mt-2 appearance-none outline-none py-2 px-3 leading-4 text-black invalid:border-red-500  invalid:[&:not(:placeholder-shown):not(:focus)]:border-red peer',

                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ColorProduct::class,
            'is_edit' => false, // Pass whether the form is for creating or editing
        ]);
    }
}

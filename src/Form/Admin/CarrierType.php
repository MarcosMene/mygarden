<?php

namespace App\Form\Admin;

use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // CHECK IF THE FORM IS FOR CREATING A NEW CARRIER (NO EXISTING CARRIER PASSED)
        $isEdit = $options['is_edit'];

        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'label' => 'Name of carrier',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex. UPS',
                    'minlength' => 3,
                    'maxlength' => 26,
                    'class' => $isEdit ? 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray',
                ],
            ])
            ->add('description', TextType::class, [
                'empty_data' => '',
                'label' => 'Description of carrier',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex.This carrier...',
                    'minlength' => 20,
                    'maxlength' => 101,
                    'class' => $isEdit ? 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray',
                ],
            ])
            ->add('price', NumberType::class, [
                'empty_data' => '',
                'label' => 'Price of carrier',
                'required' => true,
                'attr' => [
                    'placeholder' => '100,00',
                    'minlength' => 2,
                    'maxlength' => 8,
                    'class' => $isEdit ? 'w-full rounded bg-white shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carrier::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}

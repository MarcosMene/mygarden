<?php

namespace App\Form\Admin;

use App\Entity\StoreSchedule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // CHECK IF THE FORM IS FOR CREATING A NEW PRODUCT (NO EXISTING PRODUCT PASSED)
        $isEdit = $options['is_edit'];

        $builder
            ->add('day', ChoiceType::class, [
                'empty_data' => '',
                'placeholder' => 'Choose a day',
                'label' => 'Day of week',
                'choices' => [
                    'Monday' => 'Monday',
                    'Tuesday' => 'Tuesday',
                    'Wednesday' => 'Wednesday',
                    'Thursday' => 'Thursday',
                    'Friday' => 'Friday',
                    'Saturday' => 'Saturday',
                    'Sunday' => 'Sunday',
                ],
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray'
                ],
                'multiple' => false,
                'required' => true,
            ])

            ->add('openTime', TimeType::class, [
                'empty_data' => '',
                'label' => 'Opening hours',
                'input'  => 'datetime',
                'widget' => 'choice',
                'hours' => [0, 9, 10, 11, 12],
                'minutes' => [0, 15, 30, 45],
                'placeholder' => [
                    'hour' => 'Hour',
                    'minute' => 'Minute',
                ],
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray'
                ],

            ])
            ->add('closeTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'choice',
                'label' => 'Closing hours',
                'hours' => [0, 13, 14, 15, 16, 17],
                'minutes' => [0, 15, 30, 45],
                'placeholder' => [
                    'hour' => 'Hour',
                    'minute' => 'Minute',
                ],
                'attr' => [
                    'class' => $isEdit ? 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-black' : 'w-full rounded bg-white   shadow shadow-gray-100 mt-2  py-2 px-3 text-gray'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StoreSchedule::class,
            'is_edit' => false, // PASS WHETHER THE FORM IS FOR CREATING OR EDITING
        ]);
    }
}

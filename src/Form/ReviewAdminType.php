<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Check if the form is for creating a new product (no existing product passed)
        $isEdit = $options['is_edit'];

        $builder->add('isApproved', CheckboxType::class, [
            'required' => false,
            'label' => 'Approved',
            'attr' => [
                'class' => 'peer sr-only', // Tailwind styling
            ],
            'label_attr' => [
                'class' => 'inline-flex relative items-center cursor-pointer',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
            'is_edit' => false, // Pass whether the form is for creating or editing

            //CSRF TOKEN
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'review_item',  // Unique token ID for the review form
        ]);
    }
}

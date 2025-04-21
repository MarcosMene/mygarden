<?php

namespace App\Form\Admin;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('isApproved', CheckboxType::class, [
            'required' => false,
            'label' => 'Approved',
            'attr' => [
                'class' => 'peer sr-only', // TAILWIND STYLING
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

            //CSRF TOKEN
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'review_item',  // UNIQUE TOKEN ID FOR THE REVIEW FORM
        ]);
    }
}

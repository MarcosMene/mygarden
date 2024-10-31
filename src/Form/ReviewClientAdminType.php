<?php

namespace App\Form;

use App\Entity\ReviewClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewClientAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

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
            'data_class' => ReviewClient::class,

            //CSRF TOKEN
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'review_item',  // Unique token ID for the review form
        ]);
    }
}

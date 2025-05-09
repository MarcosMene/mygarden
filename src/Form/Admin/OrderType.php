<?php

namespace App\Form\Admin;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses', EntityType::class, [
                'label' => 'Choose your delivery address',
                'label_html' => true,
                'required' => true,
                'class' => Address::class,
                'expanded' => true,
                'multiple' => false,
                'choices' => $options['addresses'],
            ])
            ->add('carriers', EntityType::class, [
                'label' => 'Choose your delivery company',
                'label_html' => true,
                'required' => true,
                'class' => Carrier::class,
                'expanded' => true,
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'addresses' => null
        ]);
    }
}

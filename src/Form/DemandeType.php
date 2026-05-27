<?php

namespace App\Form;

use App\Entity\Admin;
use App\Entity\Demande;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message')
            ->add('date_creation')
            ->add('statut')
            ->add('type')
            ->add('user_id', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('admin_id', EntityType::class, [
                'class' => Admin::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}

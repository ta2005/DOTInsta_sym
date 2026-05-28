<?php

namespace App\Form;

use App\Entity\Admin;
use App\Enum\DemandeEnum;
use App\Entity\Demande;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
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
            ->add('type',EnumType::class,[
                'class'=>DemandeEnum::class,
                'label'=>'Type de demande',
                'placeholder'=>'Choisir un type'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}

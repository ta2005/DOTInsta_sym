<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\Enseignement;
use App\Entity\Matiere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnseignementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('date_debut')
            ->add('professeur_id', EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => 'id',
            ])
            ->add('matiere_id', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enseignement::class,
        ]);
    }
}

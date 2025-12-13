<?php
// src/Form/OffreStageType.php

namespace App\Form;

use App\Entity\OffreStage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreStageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l\'offre',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: Stage Développeur Web'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du stage',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'Décrivez les missions, compétences requises, avantages...'
                ]
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('lettreMotivationRequise', CheckboxType::class, [
                'label' => 'Lettre de motivation requise',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffreStage::class,
        ]);
    }
}

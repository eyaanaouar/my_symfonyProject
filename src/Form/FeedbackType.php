<?php

// src/Form/FeedbackType.php

namespace App\Form;

use App\Entity\Feedback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', ChoiceType::class, [
                'choices' => [
                    '5 Étoiles' => 5,
                    '4 Étoiles' => 4,
                    '3 Étoiles' => 3,
                    '2 Étoiles' => 2,
                    '1 Étoile' => 1,
                ],
                'label' => 'Votre note',
                'attr' => ['class' => 'form-select']
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Votre avis sur le stage',
                'attr' => ['class' => 'form-control', 'rows' => 5]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feedback ::class,
        ]);
    }
}

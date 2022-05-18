<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Stage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCourt')
            ->add('nomLong')
            ->add('stages', EntityType::class,
                            ['class' => Stage::class,
                                'choice_label' => 'titre',
                                'multiple' => true,
                                'expanded' => true ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}

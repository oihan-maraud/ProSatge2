<?php

namespace App\Form;

use App\Entity\Stage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Formation;
use App\Entity\Entreprise;

class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule')
            ->add('description')
            ->add('dateDebut')
            ->add('duree')
            ->add('competencesRequises')
            ->add('experiencesRequises')
            ->add('email')
            ->add('entreprise', EntrepriseType::class)
            ->add('formation', EntityType::class, array(
              'class'=> Formation::class,
              'choice_label' => 'intitule',
              'multiple'=>true,
              'expanded'=>true,
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}

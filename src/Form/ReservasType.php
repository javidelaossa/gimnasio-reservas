<?php

namespace App\Form;

use App\Entity\Actividades;
use App\Entity\Reservas;
use App\Entity\Sala;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('actividad', EntityType::class, [
//                'class' => Actividades::class,
//                'choice_label' => 'nombre'
//            ])
            ->add('usuario', HiddenType::class)
            ->add('actividad', HiddenType::class)
            ->add('Crear', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservas::class,
        ]);
    }
}

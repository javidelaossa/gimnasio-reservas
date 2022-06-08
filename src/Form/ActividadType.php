<?php

namespace App\Form;

use App\Entity\Actividad;
use App\Entity\Sala;
use App\Entity\TipoAct;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActividadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', EntityType::class, [
                'class' => TipoAct::class,
                'choice_label' => 'nombre',
                'label' => 'Nombre actividad:'
            ])
            ->add('hinic', null, [
                'label' => 'Hora inicio:'
            ])
            ->add('hfin', null, [
                'label' => 'Hora fin:'
            ])
            ->add('sala', EntityType::class, [
                'class' => Sala::class,
                'choice_label' => 'nombre',
                'label' => 'Sala de realización:'
            ])
            ->add('monitor', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'nombre',
                'label' => 'Monitor: '
            ])
            ->add('Crear', SubmitType::class,
                array('attr' => array('class' => 'btn btn-primary')));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Actividad::class,
        ]);
    }
}

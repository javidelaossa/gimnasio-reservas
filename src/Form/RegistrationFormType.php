<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', null, [
                'attr' => array(
                    'placeholder' => 'Nombre:',
                    'class' => 'form-control input_user'
                ),
                'label' => false
            ])
            ->add('email', EmailType::class, [
                'attr' => array(
                    'placeholder' => 'Correo electronico:',
                    'class' => 'form-control input_user'
                ),
                'label' => false,
            ])
            ->add('imagen', FileType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Debes de aceptar las condiciones',
                    ]),
                ],
                'label' => 'He leído los terminos y los acepto'

            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                    'placeholder' => 'Contraseña:',
                    'class' => 'form-control input_user'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca una contraseña',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'El campo debe tener como mínimo {{ limit }} caracteres',
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('Crear', SubmitType::class, [
                'label' => 'Crear cuenta',
                'attr' => ['class' => 'btn login_btn text-center']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}

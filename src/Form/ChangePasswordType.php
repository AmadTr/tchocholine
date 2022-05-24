<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class ChangePasswordType extends AbstractType
{
  
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('password', PasswordType::class, [
                    'constraints' => [
                        new UserPassword(),
                    ],
                    'label' => 'Mot de passe actuel',
                    'attr' => [
                        'autocomplete' => 'off',
                    ],
                ])

                ->add('newPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuiller entrer votre mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    
                    'first_options'  => ['label' => 'Entrer le nouveau'],
                    'second_options' => ['label' => 'Répéter'],
                ]);
}
}
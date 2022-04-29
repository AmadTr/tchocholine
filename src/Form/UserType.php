<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('roles',ChoiceType::class,['choices'=>[
            'utilisateur' => "ROLE_USER",
            'administrateur' => "ROLE_ADMIN"
        ],
        'expanded'=> true,
        'multiple'=> true,
        'label'=>"Roles",
        'label_attr'=>[
            'class'=>'checkbox-inline'
        ]
        ])
        ->add('gender',ChoiceType::class,['choices'=>[
            'Madame' => "Mme",
            'Monsieur' => "M"
        ],
        'expanded'=> true,
        'multiple'=> false,
        'label'=>"Civilité",
        'label_attr'=>[
            'class'=>'radio-inline'
        ]
        ])
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('password', RepeatedType::class, [ 
                'type' => PasswordType::class, 
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            // ,
            'first_options'  => ['label' => 'mot de passe'],
            'second_options' => ['label' => 'Répéter le mot de passe'],
        ])
            ->add('adress')
            ->add('postCode')
            ->add('city')
            ->add('phone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

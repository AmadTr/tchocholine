<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'required' => true,
                'attr' => ['placeholder' => 'Votre email']
            ])
            ->add('lastName',TextType::class,[
                'required' => true,
                'attr' => ['placeholder' => 'Votre nom'],
                'label' => 'Nom'

            ])
            ->add('firstname',TextType::class,[
                'required' => true,
                'attr' => ['placeholder' => 'Votre prénom'],
                'label' => 'Prénom'
                ])

            ->add('adress',TextType::class,[
                'required' => true,
                'attr' => ['placeholder' => 'Votre adresse'],
                'label' => 'Adresse'
                ])

            ->add('postcode',IntegerType::class,[
                'required' => true,
                'attr' => ['placeholder' => 'Votre code postal'],
                'label' => 'Code postal',
                'constraints' => [
                  
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Votre code postal doit contenir {{ limit }} chiffres',
                        // max length allowed by Symfony for security reasons
                        'max' => 5,
                    ]),
                ],   
            ])

            ->add('city',TextType::class,[
                'required' => true,
                'attr' => ['placeholder' => 'Votre ville'],
                'label' => 'Ville'
                ])

            ->add('phone')

            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Madame' => "Mme",
                    'Monsieur' => "M",
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => "Civilité",
                'label_attr' => [
                    'class' => 'radio-inline'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => "Accepté nos Termes",
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

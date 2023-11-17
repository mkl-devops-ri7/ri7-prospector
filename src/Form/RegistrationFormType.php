<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700',
                ],
                'attr' => [
                    'class' => 'mt-1 w-full p-4 rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm focus:outline-teal-600 ', // Ajout de classes au champ lui-même
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'block  text-sm font-medium text-gray-700',
                ],
                'attr' => [
                    'class' => 'mt-1 p-4 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm focus:outline-teal-500', // Ajout de classes au champ lui-même
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700',
                ],
                'attr' => [
                    'class' => ' mt-1 p-4 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm focus:outline-teal-500', // Ajout de classes au champ lui-même
                ],
            ])

            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'flex',
                ],
                'first_options' => [
                    'label' => 'Mot de Passe',
                    'label_attr' => [
                        'class' => 'block text-sm font-medium text-gray-700 col-6 focus:outline-teal-500',
                    ],
                    'attr' => [
                        'class' => 'p-4 mt-1 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm focus:outline-teal-500',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation de mot de passe',
                    'label_attr' => [
                        'class' => 'block text-sm font-medium text-gray-700 col-6',
                    ],
                    'attr' => [
                        'class' => 'mt-1 p-4 w-full rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm focus:outline-teal-500',
                    ],
                ],
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
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['class' => 'mt-8 grid grid-cols-6 gap-6'],
        ]);
    }
}

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

class RegistrationForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  { 
    $builder
      ->add('username', null, ['label' => 'username'])
      ->add('email', EmailType::class, ['label' => 'email'])
      ->add('phone', TextType::class, ['label' => false, 'required' => false, 'mapped' => false, 'attr' => ['autocomplete' => 'off', 'class' => 'form-miel']]) // This is here to block bots
      ->add('plainPassword', RepeatedType::class, [
        // instead of being set onto the object directly,
        // this is read and encoded in the controller
        'type' => PasswordType::class,
        'first_options'  => ['label' => 'password'],
        'second_options' => ['label' => 'repeat.password'],
        'mapped' => false,
        'attr' => ['autocomplete' => 'new-password'],
        'constraints' => [
          new NotBlank([
            'message' => 'password.missing',
          ]),
          new Length([
            'min' => 6,
            'minMessage' => 'password.short',
            // max length allowed by Symfony for security reasons
            'max' => 4096,
          ]),
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
      'translation_domain' => 'app',
    ]);
  }
}

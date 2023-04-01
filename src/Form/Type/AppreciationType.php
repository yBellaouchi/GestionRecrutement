<?php

namespace App\Form\Type;

use App\Entity\State;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AppreciationType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      $builder
      ->remove('date')
      ->add('appreciation')
      ->remove('title')
      ->remove('themes')
      ->remove('resume')
      ->remove('state',EnumType::class,['class'=>State::class])
      ->remove('interlocutor')
      ->remove('candidate')
   ;
    }
    public function getParent(): string
    {
        return InterviewType::class;
    }
  
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        // ...
        'validation_groups' => ['appreciation'],
    ]);
}
}

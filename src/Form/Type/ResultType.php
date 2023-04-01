<?php

namespace App\Form\Type;


use App\Entity\State;
use App\Form\InterviewType as FormInterviewType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
         $builder
        ->remove('date')
        ->remove('title')
        ->remove('state',EnumType::class,['class'=>State::class])  
        ;
    }

   public function getParent():string
   {
      return FormInterviewType::class;
   }

   public function configureOptions(OptionsResolver $resolver): void
   {   
      $resolver->setDefaults([
        'validation_groups' => ['note','appreciation'],
      ]);
   }
}


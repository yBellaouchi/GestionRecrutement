<?php

namespace App\Form\Type;

use App\Entity\Interview;
use App\Entity\User;
use App\Entity\Candidate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class InterviewType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('interlocutor',EntityType::class,
        [
           'class'=>'App\Entity\user',
           'choice_label' => 'FullName',

        ])
        ->add('candidate',EntityType::class,
        [
           'class'=>'App\Entity\Candidate',
           'choice_label' => 'FullName',
         ])
          ->add('date',DateTimeType::class,['html5'=>false,'widget'=>'single_text'])
        //->add('date')
        ->add('title')
         ;
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Interview::class,
         
        ]);
    }
  
        
          
            
     
}

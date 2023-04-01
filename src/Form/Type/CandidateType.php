<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Candidate;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName')
            ->add('email')
            ->add('telephone')
            ->add('headshot', FileType::class, [
                'label' => 'headshot',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k', 
                        'mimeTypesMessage' => 'Please upload a valid Headshot'
                    ])
                ],
            ])
            ->add('resume', FileType::class, [
                'label' => 'resume',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    /*new File([
                        'maxSize' => '5024k', 
                        'mimeTypesMessage' => 'Please upload a valid Resume'
                    ])*/
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
}

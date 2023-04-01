<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Candidate;
use App\Form\Type\CandidateType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack ;

class CandidateManager
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private RequestStack $request,
        private FileUploader $fileUploader
    ) {
    }

    public function makeForm(Candidate $candidate): FormInterface
    {
        $form =$this->formFactory->create(CandidateType::class, $candidate);
        $form->handleRequest($this->request->getCurrentRequest());
        
        return $form;
    }
    
    public function uploadFiles(Candidate $candidate, FormInterface $form): void
    {
        $this->fileUploader->uploadHeadshot($candidate, $form);
        $this->fileUploader->uploadResume($candidate, $form);
    }
}
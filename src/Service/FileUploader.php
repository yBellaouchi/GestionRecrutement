<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Candidate;
use App\Interface\UploadableInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\FormInterface;

class FileUploader
{
    public function __construct(private string $targetDirectory, private SluggerInterface $slugger,private EntityManagerInterface $entityManager)
    {
    }

    public function uploadHeadshot(UploadableInterface $uploadable,FormInterface $form)
    {
        $headshotImage = $form->get('headshot')->getData();
        if ($headshotImage) {
            $fileName = $this->doUpload('headshots', $headshotImage);
           
            return $uploadable->setHeadshot($fileName);
        }
    }
    public function uploadResume(Candidate $candidate, FormInterface $form)
    {
        $resume = $form->get('resume')->getData();
        if ($resume) {
            $fileName = $this->doUpload('resumes', $resume);
          
            return $candidate->setResume($fileName);
        }
    }

    private function getUniqueFileName(UploadedFile $file): string
    {
        $originalFilename = \pathinfo($file->getClientOriginalName(), \PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);

        return \sprintf('%s-%s.%s', $safeFilename, uniqid(), $file->guessExtension());
    }

    private function doUpload(string $target, UploadedFile $uploader): string
    {
        $fileName = $this->getUniqueFileName($uploader);
        $uploader->move(\sprintf('%s/%s', $this->targetDirectory, $target), $fileName);
                                           
        return $fileName;
    }
}

<?php

declare(strict_types = 1);


namespace App\Controller;

use App\Entity\Interview;
use App\Form\Type\InterviewTestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    
    #[Route('/list', name: 'app_default')]
    public function list(): Response
    {
        
        return $this->render('default/list.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    #[Route('/interview/{id}/theme', name: 'app_default')]
    public function theme(Interview $interview): Response
    {
        $form=$this->createForm(InterviewTestType::class,$interview);
        return $this->renderForm('default/theme.html.twig', [
            
            'form'=>$form
            
        ]);
    }
}

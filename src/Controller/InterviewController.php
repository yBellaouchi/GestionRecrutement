<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Interview;
use App\Entity\State;
use App\Entity\Theme;
use App\Entity\User;
use App\Form\Type\AppreciationType;
use App\Form\Type\InterviewType;
use App\Form\Type\InterviewThemesType;
use App\Form\Type\StateType;
use App\Repository\InterviewRepository;
use App\Service\MailService;
use App\Service\ThemesManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/interview')]
class InterviewController extends AbstractController
{
    public function __construct(private ThemesManager $themeManager)
    {
    }

    #[Route('/', name: 'app_interview_index', methods: ['GET'])]
    public function index(InterviewRepository $interviewRepository ): Response
    {
        if ($interviewRepository->isInterlocutor()) {
            $interviews=$interviewRepository-> findInterview($this->getUser());
        } else {
            $interviews= $interviewRepository->findAll();
        }
        $interviews= $interviewRepository->findAll();
        return $this->render('interview/index.html.twig', [
            'interviews' => $interviews,
             ]);
    }
     #[Route('/new', name: 'app_interview_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InterviewRepository $interviewRepository, MailService $mail): Response
    {
        $interview = new Interview();
        $form = $this->createForm(InterviewType::class, $interview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $interview->setState(State::Nouveau);
            $interview->setAppreciation(false);
            $interviewRepository->saveInterview($interview, true);
            $mail->sendemail($interview,
            $interview->getCandidate(),
            'mail/mailCandidate.html.twig',
            'interview',);

            return $this->redirectToRoute('app_interview_index');
        }
        return $this->renderForm('interview/new.html.twig', [
              'interview' => $interview,
              'form' => $form,
               
        ]);
    }

    #[Route('/{id}', name: 'app_interview_show', methods: ['GET'])]
    public function show(Interview $interview): Response
    {   
        return $this->renderForm('interview/show.html.twig', [
            'interview' => $interview,
        ]);
    }
    
    #[Route('/{id}/themes', name: 'app_interview_themes')]
    public function addThemes(Interview $interview, Request $request): Response
    {
        $form=$this->createForm(InterviewThemesType::class, $interview);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $level = $form['level_requested']->getData();
            $this->themeManager->saveInterview($interview, $level);
            return $this->redirectToRoute('app_interview_index');
        }
         return $this->renderForm('interview/themes.html.twig', [
            'interview' => $interview,
            'form'=>$form
        ]);
    }

    #[Route('/{id}/edit', name: 'app_interview_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Interview $interview,
        InterviewRepository $interviewRepository,
        MailService $mail 
    ): Response {
        $form = $this->createForm(InterviewType::class, $interview);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $interviewRepository->saveInterview($interview, true);
          
            $mail->sendemail($interview,
            $interview->getInterlocutor(),
            'mail/mailEditInterview.html.twig',
            'changement date');
             return $this->redirectToRoute('app_interview_index');
        }
        return $this->renderForm('interview/edit.html.twig', [
            'interview' => $interview,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit-state', name: 'app_interview_edit_state', methods: ['GET', 'POST'])]
    public function editState(
        Request $request,
        Interview $interview,
        InterviewRepository $interviewRepository,
    ): Response {
        $form = $this->createForm(StateType::class, $interview);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $interviewRepository->saveInterview($interview, true);
            return $this->redirectToRoute('app_interview_index');
        }
        
        return $this->renderForm('interview/edit.html.twig', [
            'interview' => $interview,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edit-appreciation', name: 'app_interview_edit_appreciation', methods: ['GET', 'POST'])]
    public function editAppreciation(
        Request $request,
        Interview $interview,
        InterviewRepository $interviewRepository
    ): Response {
        $form = $this->createForm(AppreciationType::class, $interview);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $interviewRepository->saveInterview($interview, true);
            return $this->redirectToRoute('app_interview_index');
        }
        
        return $this->renderForm('interview/edit.html.twig', [
            'interview' => $interview,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_interview_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Interview $interview,
        InterviewRepository $interviewRepository
    ): Response {
        if (
            $this->isCsrfTokenValid('delete'.$interview->getId(), $request->request->get('_token'))) {
            $interviewRepository->remove($interview, true);
        }
        return $this->redirectToRoute('app_interview_index');
    }

    #[Route('/add-result/{id}', name: 'app_interview_result')]
    public function add_result(Request $request, Interview $interview, InterviewRepository $interviewRepository)
    {
        $form = $this->createForm(ResultType::class, $interview);
        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {
            $interviewRepository->saveInterview($interview, true);
            return $this->redirectToRoute('app_interview_show', ['id'=>$interview->getId()]);
        }
        return $this->renderForm('interview/add_result.html.twig', [
            'interview' => $interview,
            'form' => $form,
        ]);
    }



}       
  


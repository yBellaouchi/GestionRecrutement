<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Candidate;
use App\Form\Type\LevelType;
use App\Repository\CandidateRepository;
use App\Service\CandidateManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/candidate')]
class CandidateController extends AbstractController
{
    public function __construct(private CandidateRepository $candidateRepository, 
    private CandidateManager $candidateManager,
    private Security $security)
    {}

    #[Route('/', name: 'app_candidate_index', methods: ['GET'])]
    public function index(): Response
    {
       return $this->render('candidate/index.html.twig', [
            'candidates' => $this->candidateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_candidate_new', methods: ['GET', 'POST'])]
    public function new(CandidateManager $candidateManager): Response
    {
        $candidate = new Candidate();
        $form = $candidateManager->makeForm($candidate);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $this->candidateManager->uploadFiles($candidate,$form);
            $this->candidateRepository->saveCandidate($candidate, true);

            return $this->redirectToRoute('app_candidate_index');
        }

        return $this->renderForm('candidate/new.html.twig', [
            'candidate' => $candidate,
            'form' => $form,
        ]);
    }
    #[Route('/add-level/{id}', name: 'app_add_level')]
    public function addLevel(Candidate $candidate,Request $request): Response
    {
        $form = $this->createForm(LevelType::class, $candidate);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->candidateRepository->saveCandidate($candidate, false);
             return $this->redirectToRoute('app_candidate_show', ['id'=>$candidate->getId()]);
        }
        
        return $this->renderForm('candidate/add_level.html.twig', [
            'candidate' => $candidate,
            'fullName' => $candidate->getFullName(),
            'form' => $form,
        ]);
    }
    
    #[Route('/show-resume/{id}', name: 'app_candidate_resume')]
    public function showResume(Candidate $candidate, ParameterBagInterface $params): Response
    {
        $resumeName = $candidate->getResume();
        $targetDirectory = $params->get('app.uploads_directory');
        $response = new BinaryFileResponse($targetDirectory.'/resumes/'.$resumeName);
        $response->setContentDisposition(HeaderUtils::DISPOSITION_INLINE);
        
        return $response;
      
    }
   
    #[Route('/{id}', name: 'app_candidate_show', methods: ['GET'])]
    public function show(Candidate $candidate): Response
    {
            return $this->render('candidate/show.html.twig', [
            'candidate' => $candidate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_candidate_edit', methods: ['GET', 'POST'])]
    public function edit( Candidate $candidate): Response
    { 
        
        $form = $this->candidateManager->makeForm($candidate);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->candidateManager->uploadFiles($candidate,$form);
            $this->candidateRepository->saveCandidate($candidate, false);
            return $this->redirectToRoute('app_candidate_index');
        }

        return $this->renderForm('candidate/edit.html.twig', [
            'candidate' => $candidate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_candidate_delete', methods: ['POST'])]
    public function delete(Request $request, Candidate $candidate): Response
    {
        
        // if ($this->isCsrfTokenValid('delete'.$candidate->getId(), $request->request->get('_token'))) {
            $this->candidateRepository->remove($candidate, true);
        // }
        return $this->redirectToRoute('app_candidate_index');
    }
}

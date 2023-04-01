<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\Register;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    public function __construct(private FileUploader $fileUploader)
    {}
   
    #[Route('/', name: 'app_user_index', methods: ['GET','POST'])]
    public function index(UserRepository $userRepository): Response
    {

         if( $this->getUser() && $this->getUser()->getRoles()==["ROLE_RH"]){
                 $users =$userRepository->findInterlocutors("INTERLOCUTOR");
          } 
         else {
                return $this->redirectToRoute('app_candidate_index');
              } 

              return $this->render('user/index.html.twig', [
                'users' => $users,
            ]); 
     }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Register $register): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
        //     /** @var UploadedFile $headshotFile */
        //   $this->fileUploader->uploadHeadshot( $user,$form);
          if($form['role']->getData() === 'Interlocutor') {
            $user->setRoles(['ROLE_INTERLOCUTOR']);
        } else {
            $user->setRoles(['ROLE_RH']);
        }
          $register->doRegister($user, $form);
                
          return $this->redirectToRoute('app_user_index');
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
      
        if ($form->isSubmitted() && $form->isValid()) {
            if($form['role']->getData() === 'Interlocutor') {
                $user->setRoles(['ROLE_INTERLOCUTOR']);
            } else {
                $user->setRoles(['ROLE_RH']);
            }
            $this->fileUploader->uploadHeadshot($user, $form);
            $userRepository->saveUser($user, true);

            return $this->redirectToRoute('app_user_index');
        }
        
        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_user_delete')]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
     
        //  if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        //  }

        return $this->redirectToRoute('app_user_index');
    }
}

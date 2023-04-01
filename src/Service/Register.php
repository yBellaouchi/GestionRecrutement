<?php

declare(strict_types = 1);
namespace App\Service;

use Symfony\Component\Form\FormInterface;
use App\Entity\User;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class Register
{
    public function __construct(
        private RequestStack $requestStack,
        private UserPasswordHasherInterface $userPasswordHasher,
        private FileUploader $fileUploader,
        private EntityManagerInterface $entityManager,
        private UserAuthenticatorInterface $userAuthenticator,
        private UserAuthenticator  $authenticator
    ) {

    }
    
    public function doRegister(User $user,FormInterface  $form): void
    {
        // dd($user,$form);
        $user->setPassword($this->userPasswordHasher->hashPassword(
            $user,
            $form->get('password')->getData()
        ));
        $this->fileUploader->uploadHeadshot($user, $form);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function doAutheticate($user): ?Response
    {
        return $this->userAuthenticator->authenticateUser(
            $user,
            $this->authenticator,
            $this->requestStack->getCurrentRequest(),
        );
    }
}

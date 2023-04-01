<?php

namespace App\DataFixtures;

use App\Entity\Candidate;
use App\Entity\Interview;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $entityManager): void
    {
        $candidate=new Candidate();
        $candidate->setFullName('younes')->setEmail('younes@test.com')->setTelephone('083738393399');
        $entityManager->persist($candidate);
        $user=new User();
        $user->setFullName('issam')->setEmail('issam@test.com')->setTelephone('038383937317')->setPassword('000000');
        $entityManager->persist($user);
       
        $interview=new Interview();
        $interview->setCandidate($candidate)->setInterlocutor($user)->setDate(new \DateTime("01-01-2021"))->setTitle('robotique');
        $entityManager->persist($interview);
        $entityManager->flush();
        $entityManager->flush();
    }
}

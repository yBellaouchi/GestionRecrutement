<?php

namespace App\tests\Service;

use App\Entity\Candidate;
use App\Entity\Interview;
use App\Entity\Level;
use App\Entity\Theme;
use App\Repository\InterviewRepository;
use App\Service\ThemesManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ThemesManagerTest extends KernelTestCase
{

    private $serviceContainer;
    private  $themesManager;
    private $manager;
    

    public function setUp():void
    {
            self::bootKernel();
            $this->serviceContainer = static::getContainer();
            $this->themesManager=$this->serviceContainer->get(ThemesManager::class);
            $this->manager=$this->serviceContainer->get('doctrine.orm.entity_manager');
    } 
     
     /**
     * @dataProvider  dataProviderJunior
     */
    public function testForJunior($interview,$expectedNote,$expectedCandidateLevel,$expectedAppreciation): void
    {
        $interviewRepo = $this->serviceContainer->get(InterviewRepository::class);
        $interview=$interviewRepo->find(1);
           /** @var Theme $theme */
           foreach($interview->getThemes() as $theme){
            $interview->removeTheme($theme);
        }
        $theme1=new Theme();
        $theme1->setSubject('ia')->setNoteTheme(10)->setSatisfaction(70);
        $this->manager->persist($theme1);
        $theme2=new Theme();
        $theme2->setSubject('big data')->setNoteTheme(8)->setSatisfaction(50);
        $this->manager->persist($theme2);

        $interview->addTheme($theme1)->addTheme($theme2);
        $this->themesManager->saveInterview($interview,Level::junior);
        
        $this->assertEquals($interview->getNote(),$expectedNote);
        $this->assertEquals($interview->isAppreciation(),$expectedCandidateLevel);
        $this->assertEquals($interview->getCandidate()->getLevel(),$expectedAppreciation);
    }

    public function dataProviderJunior(): array
    {
          /** @var Interview $tinterview */
           return [
            [$interview,9,true,Level::junior],
        ];
    }
     /**
     * @dataProvider  dataProviderOpperationnel
     */
    public function testForOpperationnel($interview,$expectedNote,$expectedCandidateLevel,$expectedAppreciation): void
    {
        $interviewRepo = $this->serviceContainer->get(InterviewRepository::class);
        $interview=$interviewRepo->find(1);
        /** @var Theme $theme */
        foreach($interview->getThemes() as $theme){
            $interview->removeTheme($theme);
        }
        $theme1=new Theme();
        $theme1->setSubject('ia')->setNoteTheme(10)->setSatisfaction(70);
        $this->manager->persist($theme1);
        $theme2=new Theme();
        $theme2->setSubject('big data')->setNoteTheme(12)->setSatisfaction(50);
        $this->manager->persist($theme2);

        $interview->addTheme($theme1)->addTheme($theme2);
        $this->themesManager->saveInterview($interview,Level::operationnel);
        
        $this->assertEquals($interview->getNote(),$expectedNote);
        $this->assertEquals($interview->isAppreciation(),$expectedCandidateLevel);
        $this->assertEquals($interview->getCandidate()->getLevel(),$expectedAppreciation);
    }
 
     /**
     * @dataProvider  dataProviderConfirm
     */
    public function testForConfirm($interview,$expectedNote,$expectedCandidateLevel,$expectedAppreciation): void
    {
        $interviewRepo = $this->serviceContainer->get(InterviewRepository::class);
        $interview=$interviewRepo->find(1);
         /** @var Theme $theme */
         foreach($interview->getThemes() as $theme){
            $interview->removeTheme($theme);
        }
        $theme1=new Theme();
        $theme1->setSubject('ia')->setNoteTheme(12)->setSatisfaction(70);
        $this->manager->persist($theme1);
        $theme2=new Theme();
        $theme2->setSubject('big data')->setNoteTheme(15)->setSatisfaction(50);
        $this->manager->persist($theme2);

        $interview->addTheme($theme1)->addTheme($theme2);
        $this->themesManager->saveInterview($interview,Level::confirme);
        
        $this->assertEquals($interview->getNote(),$expectedNote);
        $this->assertEquals($interview->isAppreciation(),$expectedCandidateLevel);
        $this->assertEquals($interview->getCandidate()->getLevel(),$expectedAppreciation);
    }

    public function dataProviderConfirm(): array
    {
          /** @var Interview $tinterview */
           return [
            [$interview,13.5,true,Level::confirme],
        ];
    }
    public function dataProviderOpperationnel(): array
    {
          /** @var Interview $tinterview */
           return [
            [$interview,11,true,Level::operationnel],
        ];
    }
     /**
     * @dataProvider  dataProviderSenior
     */
    public function testForSenior($interview,$expectedNote,$expectedCandidateLevel,$expectedAppreciation): void
    {
        $interviewRepo = $this->serviceContainer->get(InterviewRepository::class);
        $interview=$interviewRepo->find(1);
         /** @var Theme $theme */
         foreach($interview->getThemes() as $theme){
            $interview->removeTheme($theme);
        }
        
        $theme1=new Theme();
        $theme1->setSubject('ia')->setNoteTheme(18)->setSatisfaction(70);
        $this->manager->persist($theme1);
        $theme2=new Theme();
        $theme2->setSubject('big data')->setNoteTheme(15)->setSatisfaction(50);
        $this->manager->persist($theme2);

        $interview->addTheme($theme1)->addTheme($theme2);
        $this->themesManager->saveInterview($interview,Level::expert);
        
        $this->assertEquals($interview->getNote(),$expectedNote);
        $this->assertEquals($interview->isAppreciation(),$expectedCandidateLevel);
        $this->assertEquals($interview->getCandidate()->getLevel(),$expectedAppreciation);
    }

    public function dataProviderSenior(): array
    {
         /** @var Interview $tinterview */
           return [
            [$interview,16.5,false,Level::senior],
        ];
    }
   
      /**
     * @dataProvider  dataProviderExpert
     */
    public function testForExpert($interview,$expectedNote,$expectedCandidateLevel,$expectedAppreciation): void
    {
        $interviewRepo = $this->serviceContainer->get(InterviewRepository::class);
        $interview=$interviewRepo->find(1);
         /** @var Theme $theme */
         foreach($interview->getThemes() as $theme){
            $interview->removeTheme($theme);
        }
        $theme1=new Theme();
        $theme1->setSubject('ia')->setNoteTheme(18)->setSatisfaction(70);
        $this->manager->persist($theme1);
        $theme2=new Theme();
        $theme2->setSubject('big data')->setNoteTheme(20)->setSatisfaction(50);
        $this->manager->persist($theme2);

        $interview->addTheme($theme1)->addTheme($theme2);
        $this->themesManager->saveInterview($interview,Level::expert);
        
        $this->assertEquals($interview->getNote(),$expectedNote);
        $this->assertEquals($interview->isAppreciation(),$expectedCandidateLevel);
        $this->assertEquals($interview->getCandidate()->getLevel(),$expectedAppreciation);
    }

    public function dataProviderExpert(): array
    {
          /** @var Interview $tinterview */
           return [
            [$interview,19,true,Level::expert],
        ];
    }
    
   

}

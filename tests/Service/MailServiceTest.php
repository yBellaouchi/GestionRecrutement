<?php

namespace App\tests\Service;


use App\Repository\InterviewRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mailer\MailerInterface;
use App\Service\MailService;
use Symfony\Component\HttpClient\Exception\TransportException;

class MailServiceTest extends KernelTestCase
{
    private $serviceContainer;
    private $interviewRepo;
   
    public function setUp():void
    {
            self::bootKernel();
            $this->serviceContainer = static::getContainer();
            $this->mailService=$this->serviceContainer->get(MailService::class);
            $this->interviewRepo = $this->serviceContainer->get(InterviewRepository::class);
    } 
    
    /**
     * Unit testing sending email with no errors.
     *
     *
     *
     *@dataProvider  dataProvider
     * @param string $toAddress
     * @param string $fromAddress
     * @return void
     */
    public function testSendMailOkstring ($fromAddress,string $toAddress): void
    {
        $mock = $this->getMockBuilder(MailerInterface::class)
            ->onlyMethods(['send'])
            ->disableOriginalConstructor()
            ->getMock();
          
        $mailer = new MailService($mock,$fromAddress);
        $mock->expects($this->once())
        ->method('send');
       
        $interview=$this->interviewRepo->find(1);      
          $sent = $mailer->sendEmail($interview,$interview->getInterlocutor(),'mail/mailCandidate.html.twig','some dummy subject');
          $this->assertTrue($sent);
    }

    /**
     * Unit testing sending email with some error.
     *
     * @dataProvider dataProvider
     *
     * @param string $toAddress
     * @param string $fromAddress
     *
     * @return void
     */
    public function testSendMailWithError(string $toAddress, string $fromAddress): void
    {
        $mock = $this->getMockBuilder(MailerInterface::class)
            ->onlyMethods(['send'])
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('send')
            ->willThrowException(new TransportException('Something went wrong'));
        $mailer = new MailService($mock, $fromAddress);
        $mock->expects($this->once())
            ->method('send');
            $interview=$this->interviewRepo->find(1); 
        $sent = $mailer->sendEmail($interview,$interview->getInterlocutor(),'mail/mailCandidate.html.twig','some dummy subject');
        $this->assertFalse($sent);
    }


    public function dataProvider()
    {
        return [
            ['rh@gmail.com', 'issam@test.com']
        ];
    }

       // public function testSendMailOk
}

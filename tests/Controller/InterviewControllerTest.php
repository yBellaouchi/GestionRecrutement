<?php 
namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class InterviewControllerTest extends WebTestCase
{

    public function testNew(): void                          
    {
        $client = static::createClient();
        $client->request('GET', '/interview/new');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h3', 'Interview');
    }
    public function testEdit(): void      
    {
        $client = static::createClient();
        $client->request('GET', 'interview/1/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h3', 'Interview');
    }



    public function testdisplayLogin() : void 
    {
        $client = static::createClient();
         $client->request('GET','/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseStatusCodeSame(200);
    //    $this->assertSelectorTextContains('h3', 'Candidate');

    }
}
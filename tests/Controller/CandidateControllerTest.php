<?php 
namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CandidateControllerTest extends WebTestCase
{

    public function testIndexPage(){
    $client=static::createClient();
    $client->request('GET','/candidate/new');
    $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
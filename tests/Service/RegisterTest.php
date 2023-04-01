<?php
namespace App\tests\Service;

use App\Entity\User;
use App\Form\Type\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\Register;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\SecurityBundle\Security\UserAuthenticator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegisterTest extends KernelTestCase
{
    private $serviceContainer;
    private $container;
    private Register $registerService;
    private UserRepository $userRepository;
    private FormFactoryInterface $formFactory;
    private RequestStack $requestStack;
   
    public function setUp():void
    {
        self::bootKernel();
        
        $this->serviceContainer = static::getContainer();
    } 

    public function testDoRegister(): void
    {
        $mockRequestStack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockUploader = $this->getMockBuilder(FileUploader::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['uploadHeadshot'])
            ->getMock();

        $userPasswordHasher =  $this->serviceContainer->get('security.user_password_hasher');
        $entityManager = $this->serviceContainer->get('doctrine.orm.entity_manager');
     
        $userAuthenticator = $this->getMockBuilder(UserAuthenticatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
                    
        $authenticator = $this->getMockBuilder(UserAuthenticator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $form = $this->getMockBuilder('Symfony\Component\Form\FormFactory')
            ->disableOriginalConstructor()
            ->addMethods(['get'])
            ->getMock();

        $formField = $this->getMockBuilder('Symfony\Component\Form\FormInterface')
        ->disableOriginalConstructor()
        ->onlyMethods(['getData'])
        ->getMock();

        $formField->method('getData')
        ->willReturn('wissal123');

        $form->method('get')
            ->willReturn($formField);

        $mockUploader
            ->expects($this->once())
            ->method('uploadHeadshot');

        $user = new User();
        $user->setEmail('wissal@gmail.com')
            ->setRoles([])
            ->setFullName('Wissal')
            ->setTelephone('06111111111');

        $register = new Register(
            $mockRequestStack, 
            $userPasswordHasher, 
            $mockUploader, 
            $entityManager, 
            $userAuthenticator, 
            $authenticator
        );
        $register->doRegister($user, $form);
    }
  
    public function DoAuthenticate(){
        $formData = [
            // 'fullName'=> 'amiin@gmail.com',
            'email'=> 'amiin@gmail.com',
            'password'=>'amiin123',
            // 'telephone'=> '0670988888',            
           ];
        $user=new User();
        $form= $this->formFactory->create(RegistrationFormType::class,$user);
        
      $this->registerService->doAutheticate($user);

        $this->assertEquals($this->getUser(),$user);

    }  
}
<?php
declare(strict_types=1);
namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailSender
{
    public function __construct( private MailerInterface $mailer,private ParameterBagInterface $params)
    {
        
    }

    public function sendMail(Address $appEmail,Address $to,$subject,$twig):Response
    {
       // $targetDirectory = $this->params->get('app.uploads_directory');
        $email = (new TemplatedEmail())
            ->from($appEmail)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($twig)
        //    ->attachFromPath( $targetDirectory.'/resumes/'.$file)
            ->context([
                'date'=>date_create(),
                'number'=>rand(5,2555),
                
                
            ]);
            try {
                $this->mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                // some error prevented the email sending; display an
                // error message or try to resend the message
            }
        return new Response('Email sent');
        }
}

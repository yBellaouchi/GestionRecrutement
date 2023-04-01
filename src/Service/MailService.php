<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Interview;
use App\Interface\RecipientInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpClient\Exception\TransportException;

 class MailService 
{
    public function __construct(private MailerInterface $mailer, private string $from ) {}

    public function sendEmail(Interview $interview, RecipientInterface $recepient, $htmlTemplate,$subject)
    {
        try {
            $email = (new TemplatedEmail())
         ->from($this->from)
         ->to (new Address($recepient->getEmail(),$recepient->getFullName()))
         ->subject($subject)
         ->htmlTemplate($htmlTemplate)
         ->context([
                 'date' => ($interview->getDate()->format('Y-m-d H:i:s')),
                 'candidateName' =>($interview->getCandidate()->getFullName()),
                 'title' =>($interview->getTitle()),
                 'InterlocutorName'=>($interview->getInterlocutor()->getFullName())
                 ]); 
        $this->mailer->send($email);
        return true;

            //code...
        } catch (\Symfony\Component\Mailer\Exception\TransportException $th) {
            //throw $th;
            return false;
        }
       
    }
   
}
 
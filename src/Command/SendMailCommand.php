<?php

namespace App\Command;

use App\Repository\InterviewRepository;
use App\Service\MailSender;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mime\Address;

#[AsCommand(
    name: 'app:notify:upcoming-interviewss',
    description: 'Command help to send email',
)]
class SendMailCommand extends Command
{
    protected static $defaultName='send-mail';
    public function __construct(
        private MailSender $mailerService,
        private InterviewRepository $interviewRepository
       )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
     { 
        $interviews= $this->interviewRepository->findAll();
        foreach ($interviews as $interview) {
        if ($this->interviewRepository->isReadyToSend($interview))
            {
        $this->mailerService->sendMail(new Address('rh@gmail.com'),
        new Address( $interview->getInterlocutor()->getEmail()),
           'demande', 'mail/index.html.twig');
            }
           
         }
        return Command::SUCCESS;    
    }
}

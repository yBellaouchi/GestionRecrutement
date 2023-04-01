<?php 

declare(strict_types=1);

namespace App\Command;

use App\Repository\InterviewRepository;
use App\Service\MailService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:notify:upcoming-interviews',
    description: 'Send a email to interlocutor',
)]
class EmailCommand extends Command
{
    public function __construct(private MailService $email, private InterviewRepository $interviewRepository)
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

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
    $interviews= $this->interviewRepository->listDailyInterviews();
    foreach ($interviews as $interview) {
        $this->email->sendEmail($interview,
         $interview->getInterlocutor(),
       'mail/mailInterlocutor.html.twig',
       'interview');
            }
            
        return Command::SUCCESS;

    }
}

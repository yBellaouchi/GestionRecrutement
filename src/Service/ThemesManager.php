<?php

declare (strict_types=1);

namespace App\Service;

use App\Entity\Interview;
use App\Entity\Level;
use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;

class ThemesManager
{
    public function __construct(private EntityManagerInterface $manager) 
    {
     
    }

    /**
     * Persists the given theme to the database.
     *
     * @param Interview $interview
     */
    public function saveInterview(Interview $interview, $level): void
    {
        if (null === $interview->getId()) {
            $this->manager->persist($interview);
        }
        $note = $temoin = 0;

        /** @var Theme $theme */
        foreach ($interview->getThemes() as $theme) {
            $theme->setInterview($interview);
            $note = $note + $theme->getNoteTheme();
            $temoin++;
            $this->manager->persist($theme);
        }
        
        $noteFinal = $note/$temoin;
        $interview->setNote($noteFinal);

        switch ($noteFinal) {
            case $noteFinal <= 10:
                $interview->getCandidate()->setLevel(Level::junior);
                break;
            case ($noteFinal > 10 && $noteFinal <= 12):
                $interview->getCandidate()->setLevel(Level::operationnel);
                break;
            case ($noteFinal > 12 && $noteFinal <= 15):
                $interview->getCandidate()->setLevel(Level::confirme);
                break;
            case ($noteFinal > 15 && $noteFinal <= 18):
                $interview->getCandidate()->setLevel(Level::senior);
                break;
            case $noteFinal > 18:
                $interview->getCandidate()->setLevel(Level::expert);
                break;
        }
        $interview->setAppreciation(($noteFinal > $this->getNoteLevelMin($level)));
        $this->manager->flush();
    }

    private function getNoteLevelMin($level): int
    {
        return match($level) {
            Level::junior => 8,
            Level::operationnel => 10,
            Level::confirme => 12,
            Level::senior =>  15,
            Level::expert => 18,
            default => 0
        };
    }

    public function youness($level) 
    {
        return $this->getNoteLevelMin($level);
    }
}

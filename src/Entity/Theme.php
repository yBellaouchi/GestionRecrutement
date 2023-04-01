<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $subject;

    #[ORM\Column(type: 'float', length: 255)]
    private $noteTheme;


    #[ORM\Column(type: 'float',nullable:true)]
    private $satisfaction;

    #[ORM\ManyToOne(targetEntity: Interview::class, inversedBy: 'themes')]
    private $interview;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoteTheme(): ?float
    
    {
        return $this->noteTheme;
    }

    public function setNoteTheme(float $NoteTheme): self
    {
        $this->noteTheme = $NoteTheme;

        return $this;
    }

    public function getSatisfaction(): ?float
    {
        return $this->satisfaction;
    }

    public function setSatisfaction(float $satisfaction): self
    {
        $this->satisfaction = $satisfaction;

        return $this;
    }

    public function getInterview(): ?Interview
    {
        return $this->interview;
    }

    public function setInterview(?Interview $interview): self
    {
        $this->interview = $interview;

        return $this;
    }
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }
}

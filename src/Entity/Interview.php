<?php

namespace App\Entity;

use App\Entity\State;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Repository\InterviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterviewRepository::class)]
class Interview
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'float', nullable: true)]
    private $note;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $appreciation;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $title;

    #[ORM\OneToMany(mappedBy: 'interview', targetEntity: Theme::class)]
    private $themes;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $interlocutor;

    #[ORM\ManyToOne(targetEntity: Candidate::class, inversedBy: 'interviews')]
    private $candidate;
 
    
    #[ORM\Column(type: 'string', length: 255, nullable: true,enumType:State::class)]
    private $state;

    public function __construct()
    {
        $this->themes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function isAppreciation(): ?bool
    {
        return $this->appreciation;
    }

    public function setAppreciation(bool $appreciation): self
    {
        $this->appreciation = $appreciation;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Theme>
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    public function addTheme(Theme $theme): self
    {
        if (!$this->themes->contains($theme)) {
            $this->themes[] = $theme;
            $theme->setInterview($this);
        }

        return $this;
    }

    public function removeTheme(Theme $theme): self
    {
        if ($this->themes->removeElement($theme)) {
            // set the owning side to null (unless already changed)
            if ($theme->getInterview() === $this) {
                $theme->setInterview(null);
            }
        }

        return $this;
    }

    public function getInterlocutor(): ?User
    {
        return $this->interlocutor;
    }

    public function setInterlocutor(?User $interlocutor): self
    {
        $this->interlocutor = $interlocutor;

        return $this;
    }

    public function getCandidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function setCandidate(?Candidate $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getState(): mixed
    {
        return $this->state;
    }

    public function setState(mixed $state): self

    {
        $this->state = $state;

        return $this;
    }
}

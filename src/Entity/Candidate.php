<?php

namespace App\Entity;

use App\Interface\RecipientInterface;
use App\Interface\UploadableInterface;
use App\Repository\CandidateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CandidateRepository::class)]    
class Candidate implements UploadableInterface,RecipientInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    
    #[ORM\Column(type: 'string', length: 255)]
    private $fullName;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $telephone;

       #[ORM\Column(type: 'string',nullable: true)]
    private $headshot;
   
    #[ORM\Column(type: 'string',nullable: true)]
   
    private $resume;
    #[ORM\OneToMany(mappedBy: 'candidate', targetEntity: Interview::class)]
    private $Interviews;

    #[ORM\Column(type: 'string', length: 255, nullable: true, enumType: Level::class)]
    private $level;

    public function __construct()
    {
        $this->Interviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $FullName): self
    {
        $this->fullName = $FullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $Email): self
    {
        $this->email = $Email;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $Telephone): self
    {
        $this->telephone = $Telephone;

        return $this;
    }

    
    /**
     * @return Collection<int, Interview>
     */
    public function getInterviews(): Collection
    {
        return $this->Interviews;
    }

    public function addInterview(Interview $interview): self
    {
        if (!$this->Interviews->contains($interview)) {
            $this->Interviews[] = $interview;
            $interview->setCandidate($this);
        }

        return $this;
    }

    public function removeInterview(Interview $interview): self
    {
        if ($this->Interviews->removeElement($interview)) {
            // set the owning side to null (unless already changed)
            if ($interview->getCandidate() === $this) {
                $interview->setCandidate(null);
            }
        }

        return $this;
    }

    public function getLevel(): mixed
    {
        return $this->level;
    }

    public function setLevel(mixed $Level): self
    {
        $this->level = $Level;

        return $this;
    }
    public function setHeadshot(string $file )
    {
        $this->headshot = $file;
    }

    public function getHeadshot()       
    {
        return $this->headshot;
    }

    public function setResume($resume )
    {
        $this->resume = $resume;
    }

    public function getResume()       
    {
        return $this->resume;
    }
}

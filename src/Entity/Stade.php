<?php

namespace App\Entity;

use App\Repository\StadeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Entity(repositoryClass=StadeRepository::class)
 */
class Stade
{
    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    public function getDeletedAt() { return $this->deletedAt; }

    public function setDeletedAt($deletedAt): void  { $this->deletedAt = $deletedAt; }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    /**
     * @Gedmo\SortablePosition
     * @var integer
     * @Gedmo\Versioned
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity=Convention::class, mappedBy="stade")
     */
    private $conventions;

    /**
     * @ORM\OneToMany(targetEntity=ConventionStade::class, mappedBy="stade")
     */
    private $conventionStades;

    /**
     * @ORM\OneToMany(targetEntity=ConventionSuiviExecution::class, mappedBy="stade")
     */
    private $conventionSuiviExecutions;

    public function __construct()
    {
        $this->conventions = new ArrayCollection();
        $this->conventionStades = new ArrayCollection();
        $this->conventionSuiviExecutions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Convention[]
     */
    public function getConventions(): Collection
    {
        return $this->conventions;
    }

    public function addConvention(Convention $convention): self
    {
        if (!$this->conventions->contains($convention)) {
            $this->conventions[] = $convention;
            $convention->setStade($this);
        }

        return $this;
    }

    public function removeConvention(Convention $convention): self
    {
        if ($this->conventions->removeElement($convention)) {
            // set the owning side to null (unless already changed)
            if ($convention->getStade() === $this) {
                $convention->setStade(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConventionStade[]
     */
    public function getConventionStades(): Collection
    {
        return $this->conventionStades;
    }

    public function addConventionStade(ConventionStade $conventionStade): self
    {
        if (!$this->conventionStades->contains($conventionStade)) {
            $this->conventionStades[] = $conventionStade;
            $conventionStade->setStade($this);
        }

        return $this;
    }

    public function removeConventionStade(ConventionStade $conventionStade): self
    {
        if ($this->conventionStades->removeElement($conventionStade)) {
            // set the owning side to null (unless already changed)
            if ($conventionStade->getStade() === $this) {
                $conventionStade->setStade(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConventionSuiviExecution[]
     */
    public function getConventionSuiviExecutions(): Collection
    {
        return $this->conventionSuiviExecutions;
    }

    public function addConventionSuiviExecution(ConventionSuiviExecution $conventionSuiviExecution): self
    {
        if (!$this->conventionSuiviExecutions->contains($conventionSuiviExecution)) {
            $this->conventionSuiviExecutions[] = $conventionSuiviExecution;
            $conventionSuiviExecution->setStade($this);
        }

        return $this;
    }

    public function removeConventionSuiviExecution(ConventionSuiviExecution $conventionSuiviExecution): self
    {
        if ($this->conventionSuiviExecutions->removeElement($conventionSuiviExecution)) {
            // set the owning side to null (unless already changed)
            if ($conventionSuiviExecution->getStade() === $this) {
                $conventionSuiviExecution->setStade(null);
            }
        }

        return $this;
    }
    
    /**
     * Set position
     * @param integer $position
     * @return Concurrent
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}

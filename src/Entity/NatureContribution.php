<?php

namespace App\Entity;

use App\Repository\NatureContributionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Entity(repositoryClass=NatureContributionRepository::class)
 */
class NatureContribution
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
     * @ORM\OneToMany(targetEntity=ConventionContribution::class, mappedBy="natureContribution")
     */
    private $conventionContributions;

    /**
     * @ORM\OneToMany(targetEntity=ConventionEngagement::class, mappedBy="natureContribution")
     */
    private $conventionEngagements;

    public function __construct()
    {
        $this->conventionContributions = new ArrayCollection();
        $this->conventionEngagements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): ?string
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
     * @return Collection|ConventionContribution[]
     */
    public function getConventionContributions(): Collection
    {
        return $this->conventionContributions;
    }

    public function addConventionContribution(ConventionContribution $conventionContribution): self
    {
        if (!$this->conventionContributions->contains($conventionContribution)) {
            $this->conventionContributions[] = $conventionContribution;
            $conventionContribution->setNatureContribution($this);
        }

        return $this;
    }

    public function removeConventionContribution(ConventionContribution $conventionContribution): self
    {
        if ($this->conventionContributions->removeElement($conventionContribution)) {
            // set the owning side to null (unless already changed)
            if ($conventionContribution->getNatureContribution() === $this) {
                $conventionContribution->setNatureContribution(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConventionEngagement[]
     */
    public function getConventionEngagements(): Collection
    {
        return $this->conventionEngagements;
    }

    public function addConventionEngagement(ConventionEngagement $conventionEngagement): self
    {
        if (!$this->conventionEngagements->contains($conventionEngagement)) {
            $this->conventionEngagements[] = $conventionEngagement;
            $conventionEngagement->setNatureContribution($this);
        }

        return $this;
    }

    public function removeConventionEngagement(ConventionEngagement $conventionEngagement): self
    {
        if ($this->conventionEngagements->removeElement($conventionEngagement)) {
            // set the owning side to null (unless already changed)
            if ($conventionEngagement->getNatureContribution() === $this) {
                $conventionEngagement->setNatureContribution(null);
            }
        }

        return $this;
    }
}

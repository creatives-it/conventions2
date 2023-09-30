<?php

namespace App\Entity;

use App\Repository\OrganeGouvernanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Entity(repositoryClass=OrganeGouvernanceRepository::class)
 */
class OrganeGouvernance
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
     * @ORM\ManyToMany(targetEntity=ConventionSuiviExecution::class, mappedBy="organeGouvernances")
     */
    private $conventionSuiviExecutions;

    /**
     * @ORM\ManyToMany(targetEntity=Contact::class, inversedBy="organeGouvernances")
     */
    private $membres;

    /**
     * @ORM\ManyToMany(targetEntity=Convention::class, mappedBy="organeGouvernances")
     */
    private $conventions;



    public function __construct()
    {
        $this->conventionSuiviExecutions = new ArrayCollection();
        $this->membres = new ArrayCollection();
        $this->conventions = new ArrayCollection();
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
            $conventionSuiviExecution->addOrganeGouvernance($this);
        }

        return $this;
    }

    public function removeConventionSuiviExecution(ConventionSuiviExecution $conventionSuiviExecution): self
    {
        if ($this->conventionSuiviExecutions->removeElement($conventionSuiviExecution)) {
            $conventionSuiviExecution->removeOrganeGouvernance($this);
        }

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(Contact $membre): self
    {
        if (!$this->membres->contains($membre)) {
            $this->membres[] = $membre;
        }

        return $this;
    }

    public function removeMembre(Contact $membre): self
    {
        $this->membres->removeElement($membre);

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
            $convention->addOrganeGouvernance($this);
        }

        return $this;
    }

    public function removeConvention(Convention $convention): self
    {
        if ($this->conventions->removeElement($convention)) {
            $convention->removeOrganeGouvernance($this);
        }

        return $this;
    }


}

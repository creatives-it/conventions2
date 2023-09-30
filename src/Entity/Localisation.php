<?php

namespace App\Entity;

use App\Repository\LocalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Entity(repositoryClass=LocalisationRepository::class)
 */
class Localisation
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
     * @ORM\ManyToMany(targetEntity=Convention::class, mappedBy="localisations")
     */
    private $conventions;

    /**
     * @ORM\OneToMany(targetEntity=Convention::class, mappedBy="localisation")
     */
    private $conventionUns;

    /**
     * @ORM\OneToMany(targetEntity=ConventionRepartitionLocalisation::class, mappedBy="localisation")
     */
    private $conventionRepartitionLocalisations;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $mapKey;

    public function __construct()
    {
        $this->conventions = new ArrayCollection();
        $this->conventionUns = new ArrayCollection();
        $this->conventionRepartitionLocalisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): ?string
    {
        return $this->name;
    }

    /**
     * montant engage toutes conventions confondues
     */
    public function getMontantConventions()
    {
        $rows = $this->conventionRepartitionLocalisations;
        $return = 0;
        if (!($rows->isEmpty())) {
            foreach ($rows as $row) {
                if (!empty($row->getMontant())) {
                    $return += $row->getMontant();
                }
            }
        }

        return $return;
    }

    /**
     * Nombre de conventions
     */
    public function getNombreConventions()
    {
        $conventions = $this->conventionUns->filter(function(Convention $convention) {
            return (!$convention->getIsAvenant()) ;
        });
        return count($conventions);
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
    public function getConventionUns(): Collection
    {
        return $this->conventionUns;
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
            $convention->setLocalisation($this);
        }

        return $this;
    }

    public function removeConvention(Convention $convention): self
    {
        if ($this->conventions->removeElement($convention)) {
            // set the owning side to null (unless already changed)
            if ($convention->getLocalisation() === $this) {
                $convention->setLocalisation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConventionRepartitionLocalisation[]
     */
    public function getConventionRepartitionLocalisations(): Collection
    {
        return $this->conventionRepartitionLocalisations;
    }

    public function addConventionRepartitionLocalisation(ConventionRepartitionLocalisation $conventionRepartitionLocalisation): self
    {
        if (!$this->conventionRepartitionLocalisations->contains($conventionRepartitionLocalisation)) {
            $this->conventionRepartitionLocalisations[] = $conventionRepartitionLocalisation;
            $conventionRepartitionLocalisation->setLocalisation($this);
        }

        return $this;
    }

    public function removeConventionRepartitionLocalisation(ConventionRepartitionLocalisation $conventionRepartitionLocalisation): self
    {
        if ($this->conventionRepartitionLocalisations->removeElement($conventionRepartitionLocalisation)) {
            // set the owning side to null (unless already changed)
            if ($conventionRepartitionLocalisation->getLocalisation() === $this) {
                $conventionRepartitionLocalisation->setLocalisation(null);
            }
        }

        return $this;
    }

    public function getMapKey(): ?string
    {
        return $this->mapKey;
    }

    public function setMapKey(?string $mapKey): self
    {
        $this->mapKey = $mapKey;

        return $this;
    }

}

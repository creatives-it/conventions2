<?php

namespace App\Entity;

use App\Repository\ConventionEngagementRegionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConventionEngagementRegionRepository::class)
 */
class ConventionEngagementRegion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Convention::class, inversedBy="conventionEngagementRegions")
     */
    private $convention;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $engagementProgramme;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $engagementProgrammeExcedentAnneePrecedante;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $engagementNonProgramme;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $engagementRealise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConvention(): ?Convention
    {
        return $this->convention;
    }

    public function setConvention(?Convention $convention): self
    {
        $this->convention = $convention;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getEngagementProgramme(): ?string
    {
        return $this->engagementProgramme;
    }

    public function setEngagementProgramme(string $engagementProgramme): self
    {
        $this->engagementProgramme = $engagementProgramme;

        return $this;
    }

    public function getEngagementProgrammeExcedentAnneePrecedante(): ?string
    {
        return $this->engagementProgrammeExcedentAnneePrecedante;
    }

    public function setEngagementProgrammeExcedentAnneePrecedante(string $engagementProgrammeExcedentAnneePrecedante): self
    {
        $this->engagementProgrammeExcedentAnneePrecedante = $engagementProgrammeExcedentAnneePrecedante;

        return $this;
    }

    public function getEngagementNonProgramme(): ?string
    {
        return $this->engagementNonProgramme;
    }

    public function setEngagementNonProgramme(string $engagementNonProgramme): self
    {
        $this->engagementNonProgramme = $engagementNonProgramme;

        return $this;
    }

    public function getEngagementRealise(): ?string
    {
        return $this->engagementRealise;
    }

    public function setEngagementRealise(string $engagementRealise): self
    {
        $this->engagementRealise = $engagementRealise;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ConventionEngagementRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ConventionEngagementRepository::class)
 */
class ConventionEngagement
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Convention::class, inversedBy="conventionEngagements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $convention;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity=NatureContribution::class, inversedBy="conventionEngagements")
     */
    private $natureContribution;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=0, nullable=true)
     */
    private $montantProgramme;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=0, nullable=true)
     */
    private $montantRealise;

    /**
     * @ORM\Column(type="string", length=550, nullable=true)
     */
    private $observation;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $intitule;

    /**
     * @ORM\ManyToOne(targetEntity=Partenaire::class, inversedBy="conventionEngagements")
     */
    private $partenaire;

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

    public function setAnnee(?int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getNatureContribution(): ?NatureContribution
    {
        return $this->natureContribution;
    }

    public function setNatureContribution(?NatureContribution $natureContribution): self
    {
        $this->natureContribution = $natureContribution;

        return $this;
    }

    public function getMontantProgramme(): ?string
    {
        return $this->montantProgramme;
    }

    public function setMontantProgramme(?string $montantProgramme): self
    {
        $this->montantProgramme = $montantProgramme;

        return $this;
    }

    public function getMontantRealise(): ?string
    {
        return $this->montantRealise;
    }

    public function setMontantRealise(string $montantRealise): self
    {
        $this->montantRealise = $montantRealise;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): self
    {
        $this->observation = $observation;

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(?string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ConventionContributionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ConventionContributionRepository::class)
 */
class ConventionContribution
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Partenaire::class, inversedBy="conventionContributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $partenaire;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $contribution;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datePrevue;

    /**
     * @ORM\Column(type="string", length=550, nullable=true)
     */
    private $observation;

    /**
     * @ORM\ManyToOne(targetEntity=NatureContribution::class, inversedBy="conventionContributions")
     */
    private $natureContribution;

    /**
     * @ORM\ManyToOne(targetEntity=Convention::class, inversedBy="conventionContributions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $convention;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee;

    public function __construct()
    {
        $this->conventionContributions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getContribution(): ?string
    {
        return $this->contribution;
    }

    public function setContribution(?string $contribution): self
    {
        $this->contribution = $contribution;

        return $this;
    }

    public function getDatePrevue(): ?\DateTimeInterface
    {
        return $this->datePrevue;
    }

    public function setDatePrevue(?\DateTimeInterface $datePrevue): self
    {
        $this->datePrevue = $datePrevue;

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

    public function getNatureContribution(): ?NatureContribution
    {
        return $this->natureContribution;
    }

    public function setNatureContribution(?NatureContribution $natureContribution): self
    {
        $this->natureContribution = $natureContribution;

        return $this;
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
}

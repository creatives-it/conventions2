<?php

namespace App\Entity;

use App\Repository\ConventionRepartitionLocalisationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConventionRepartitionLocalisationRepository::class)
 */
class ConventionRepartitionLocalisation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Convention::class, inversedBy="conventionRepartitionLocalisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $convention;

    /**
     * @ORM\ManyToOne(targetEntity=Localisation::class, inversedBy="conventionRepartitionLocalisations")
     */
    private $localisation;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2, nullable=true)
     */
    private $montant;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $pourcent;

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

    public function getLocalisation(): ?Localisation
    {
        return $this->localisation;
    }

    public function setLocalisation(?Localisation $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(?string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getPourcent(): ?string
    {
        return $this->pourcent;
    }

    public function setPourcent(?string $pourcent): self
    {
        $this->pourcent = $pourcent;

        return $this;
    }
}

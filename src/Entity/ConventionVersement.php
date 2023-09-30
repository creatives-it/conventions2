<?php

namespace App\Entity;

use App\Repository\ConventionVersementRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ConventionVersementRepository::class)
 */
class ConventionVersement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Partenaire::class, inversedBy="conventionVersements")
     */
    private $partenaire;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=550, nullable=true)
     */
    private $intitule;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateVersement;

    /**
     * @ORM\ManyToOne(targetEntity=Convention::class, inversedBy="conventionVersements")
     */
    private $convention;

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

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(?string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getDateVersement(): ?\DateTimeInterface
    {
        return $this->dateVersement;
    }

    public function setDateVersement(?\DateTimeInterface $dateVersement): self
    {
        $this->dateVersement = $dateVersement;

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
}

<?php

namespace App\Entity;

use App\Repository\ConventionSignatureRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ConventionSignatureRepository::class)
 */
class ConventionSignature
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Convention::class, inversedBy="conventionSignatures")
     */
    private $convention;

    /**
     * @ORM\ManyToOne(targetEntity=Partenaire::class, inversedBy="conventionSignatures")
     */
    private $partenaire;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateTransmissionPourSignature;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeroEnvoiDepart;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rappel1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rappel2;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateCourrierArrive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeroCourrierArrive;

    /**
     * @ORM\Column(type="string", length=550, nullable=true)
     */
    private $observations;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSignee;


    /**
     * delai ecoule entre la date transmission et aujourdhui/dateCourrierArrivee
     */
    public function getDelaiConsommePourSignature()
    {
        $date1 = $this->getDateTransmissionPourSignature();
        $date2 = $this->getDateCourrierArrive();
        if ((null !== $date1) and (null === $date2)) {
            $now = new \Datetime();
            $diff =  $date1->diff($now);
            $delai   = intval($diff->days);
        } elseif ((null !== $date1) and (null !== $date2)) {
            $diff =  $date1->diff($date2);
            $delai   = intval($diff->days);
        } else {
            $delai = "";
        }
        return $delai;
    }

    /**
     * delai ecoule depuis transmission si convention non signee
     */
    public function getDelaiAttenteSignature()
    {
        if ($this->getIsSignee()) return null;
        return $this->getDelaiConsommePourSignature();
    }

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

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    public function getDateTransmissionPourSignature(): ?\DateTimeInterface
    {
        return $this->dateTransmissionPourSignature;
    }

    public function setDateTransmissionPourSignature(?\DateTimeInterface $dateTransmissionPourSignature): self
    {
        $this->dateTransmissionPourSignature = $dateTransmissionPourSignature;

        return $this;
    }

    public function getNumeroEnvoiDepart(): ?string
    {
        return $this->numeroEnvoiDepart;
    }

    public function setNumeroEnvoiDepart(?string $numeroEnvoiDepart): self
    {
        $this->numeroEnvoiDepart = $numeroEnvoiDepart;

        return $this;
    }

    public function getRappel1(): ?string
    {
        return $this->rappel1;
    }

    public function setRappel1(?string $rappel1): self
    {
        $this->rappel1 = $rappel1;

        return $this;
    }

    public function getRappel2(): ?string
    {
        return $this->rappel2;
    }

    public function setRappel2(?string $rappel2): self
    {
        $this->rappel2 = $rappel2;

        return $this;
    }

    public function getDateCourrierArrive(): ?\DateTimeInterface
    {
        return $this->dateCourrierArrive;
    }

    public function setDateCourrierArrive(?\DateTimeInterface $dateCourrierArrive): self
    {
        $this->dateCourrierArrive = $dateCourrierArrive;

        return $this;
    }

    public function getNumeroCourrierArrive(): ?string
    {
        return $this->numeroCourrierArrive;
    }

    public function setNumeroCourrierArrive(?string $numeroCourrierArrive): self
    {
        $this->numeroCourrierArrive = $numeroCourrierArrive;

        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations): self
    {
        $this->observations = $observations;

        return $this;
    }

    public function getIsSignee(): ?bool
    {
        return $this->isSignee;
    }

    public function setIsSignee(bool $isSignee): self
    {
        $this->isSignee = $isSignee;

        return $this;
    }
}

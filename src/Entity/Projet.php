<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Entity(repositoryClass=ProjetRepository::class)
 */
class Projet
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
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=550)
     */
    private $objet;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=0, nullable=true)
     */
    private $montantInvestissement;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=0, nullable=true)
     */
    private $contributionRegion;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=0, nullable=true)
     */
    private $contributionPartenaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $duree;

    /**
     * @ORM\ManyToOne(targetEntity=Convention::class, inversedBy="projets")
     */
    private $convention;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDemarrage;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateAchevement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tauxAvancement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anneeProgrammation;

    /**
     * @ORM\ManyToOne(targetEntity=ProjetNature::class, inversedBy="projets")
     */
    private $nature;

    /**
     * @ORM\ManyToOne(targetEntity=ProjetPhase::class, inversedBy="projets")
     */
    private $phase;

    /**
     * @ORM\ManyToOne(targetEntity=Axe::class, inversedBy="projets")
     */
    private $axe;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPdr;

    /**
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(name="locality", type="string", length=255, nullable=true)
     */
    protected $locality;

    /**
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    protected $country;

    /**
     * @var float     Latitude of the position
     *
     * @ORM\Column(name="lat", type="float", nullable=true)
     */
    protected $lat;

    /**
     * @var float     Longitude of the position
     *
     * @ORM\Column(name="lng", type="float", nullable=true)
     */
    protected $lng;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): ?string
    {
        return $this->objet;
    }

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     */
    public function setLat(float $lat): void
    {
        if (is_string($lat)) {
            $lat = floatval($lat);
        }
        $this->lat = $lat;
    }

    /**
     * @return float
     */
    public function getLng(): float
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     */
    public function setLng(float $lng): void
    {
        if (is_string($lng)) {
            $lng = floatval($lng);
        }
        $this->lng = $lng;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @param mixed $locality
     */
    public function setLocality($locality): void
    {
        $this->locality = $locality;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMontantInvestissement(): ?string
    {
        return $this->montantInvestissement;
    }

    public function setMontantInvestissement(?string $montantInvestissement): self
    {
        $this->montantInvestissement = $montantInvestissement;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): self
    {
        $this->duree = $duree;

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

    public function getDateDemarrage(): ?\DateTimeInterface
    {
        return $this->dateDemarrage;
    }

    public function setDateDemarrage(?\DateTimeInterface $dateDemarrage): self
    {
        $this->dateDemarrage = $dateDemarrage;

        return $this;
    }

    public function getDateAchevement(): ?\DateTimeInterface
    {
        return $this->dateAchevement;
    }

    public function setDateAchevement(?\DateTimeInterface $dateAchevement): self
    {
        $this->dateAchevement = $dateAchevement;

        return $this;
    }

    public function getTauxAvancement(): ?int
    {
        return $this->tauxAvancement;
    }

    public function setTauxAvancement(?int $tauxAvancement): self
    {
        $this->tauxAvancement = $tauxAvancement;

        return $this;
    }

    public function getAnneeProgrammation(): ?int
    {
        return $this->anneeProgrammation;
    }

    public function setAnneeProgrammation(?int $anneeProgrammation): self
    {
        $this->anneeProgrammation = $anneeProgrammation;

        return $this;
    }

    public function getPhase(): ?ProjetPhase
    {
        return $this->phase;
    }

    public function setPhase(?ProjetPhase $phase): self
    {
        $this->phase = $phase;

        return $this;
    }

    public function getAxe(): ?Axe
    {
        return $this->axe;
    }

    public function setAxe(?Axe $axe): self
    {
        $this->axe = $axe;

        return $this;
    }

    public function getIsPdr(): ?bool
    {
        return $this->isPdr;
    }

    public function setIsPdr(?bool $isPdr): self
    {
        $this->isPdr = $isPdr;

        return $this;
    }

    public function getContributionRegion(): ?string
    {
        return $this->contributionRegion;
    }

    public function setContributionRegion(?string $contributionRegion): self
    {
        $this->contributionRegion = $contributionRegion;

        return $this;
    }

    public function getContributionPartenaire(): ?string
    {
        return $this->contributionPartenaire;
    }

    public function setContributionPartenaire(?string $contributionPartenaire): self
    {
        $this->contributionPartenaire = $contributionPartenaire;

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

    public function getNature(): ?ProjetNature
    {
        return $this->nature;
    }

    public function setNature(?ProjetNature $nature): self
    {
        $this->nature = $nature;

        return $this;
    }
}

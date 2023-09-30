<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
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
    private $month;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer")
     */
    private $annee;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=TypeSession::class, inversedBy="sessions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeSession;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=Convention::class, mappedBy="sessionApprobation")
     */
    private $conventionApprobations;

    /**
     * @ORM\ManyToOne(targetEntity=Mandat::class, inversedBy="sessions")
     */
    private $mandat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lieuSession;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbrePresents;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenu;

    /**
     * @ORM\ManyToMany(targetEntity=Convention::class, inversedBy="sessions")
     */
    private $conventions;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $conventionsTexte;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observations;

    /**
     * @ORM\OneToMany(targetEntity=Arrete::class, mappedBy="session", cascade={"persist"}, orphanRemoval=true)
     */
    private $arretes;

    /**
     * @var \DateTime $updatedAt
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    public function getCreatedAt(): \DateTime { return $this->createdAt; }
    public function getUpdatedAt(): \DateTime { return $this->updatedAt; }


    /**
     * @Vich\UploadableField(mapping="divers_fichiers", fileNameProperty="nomFichier1", size="tailleFichier1", mimeType="mimeTypeFichier1", originalName="nomOrigineFichier1")
     * @var File
     */
    private $fichier1;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $nomFichier1;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    private $tailleFichier1;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $mimeTypeFichier1;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $nomOrigineFichier1;


    public function setFichier1(File $fichier1 = null)  {  $this->fichier1 = $fichier1;  if ($fichier1) {  $this->updatedAt = new \DateTimeImmutable();  } return $this;  }
    public function getFichier1()  {  return $this->fichier1;   }
    public function setNomFichier1($nomFichier1)  {  $this->nomFichier1 = $nomFichier1;  return $this;  }
    public function getNomFichier1()   {  return $this->nomFichier1;   }
    public function getTailleFichier1()  {  return $this->tailleFichier1;   }
    public function setTailleFichier1($tailleFichier1)  { $this->tailleFichier1 = $tailleFichier1;  return $this;    }
    public function getMimeTypeFichier1() { return $this->mimeTypeFichier1;  }
    public function setMimeTypeFichier1($mimeTypeFichier1) { $this->mimeTypeFichier1 = $mimeTypeFichier1;  return $this;  }
    public function getNomOrigineFichier1() { return $this->nomOrigineFichier1; }
    public function setNomOrigineFichier1($nomOrigineFichier1)    { $this->nomOrigineFichier1 = $nomOrigineFichier1;  return $this; }



    /**
     * @Vich\UploadableField(mapping="divers_fichiers", fileNameProperty="nomFichier2", size="tailleFichier2", mimeType="mimeTypeFichier2", originalName="nomOrigineFichier2")
     * @var File
     */
    private $fichier2;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $nomFichier2;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    private $tailleFichier2;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $mimeTypeFichier2;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $nomOrigineFichier2;

    public function setFichier2(File $fichier2 = null)  {  $this->fichier2 = $fichier2;  if ($fichier2) {  $this->updatedAt = new \DateTimeImmutable();  } return $this;  }
    public function getFichier2()  {  return $this->fichier2;   }
    public function setNomFichier2($nomFichier2)  {  $this->nomFichier2 = $nomFichier2;  return $this;  }
    public function getNomFichier2()   {  return $this->nomFichier2;   }
    public function getTailleFichier2()  {  return $this->tailleFichier2;   }
    public function setTailleFichier2($tailleFichier2)  { $this->tailleFichier2 = $tailleFichier2;  return $this;    }
    public function getMimeTypeFichier2() { return $this->mimeTypeFichier2;  }
    public function setMimeTypeFichier2($mimeTypeFichier2) { $this->mimeTypeFichier2 = $mimeTypeFichier2;  return $this;  }
    public function getNomOrigineFichier2() { return $this->nomOrigineFichier2; }
    public function setNomOrigineFichier2($nomOrigineFichier2)    { $this->nomOrigineFichier2 = $nomOrigineFichier2;  return $this; }



    /**
     * @Vich\UploadableField(mapping="divers_fichiers", fileNameProperty="nomFichier3", size="tailleFichier3", mimeType="mimeTypeFichier3", originalName="nomOrigineFichier3")
     * @var File
     */
    private $fichier3;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $nomFichier3;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    private $tailleFichier3;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $mimeTypeFichier3;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $nomOrigineFichier3;

    public function setFichier3(File $fichier3 = null)  {  $this->fichier3 = $fichier3;  if ($fichier3) {  $this->updatedAt = new \DateTimeImmutable();  } return $this;  }
    public function getFichier3()  {  return $this->fichier3;   }
    public function setNomFichier3($nomFichier3)  {  $this->nomFichier3 = $nomFichier3;  return $this;  }
    public function getNomFichier3()   {  return $this->nomFichier3;   }
    public function getTailleFichier3()  {  return $this->tailleFichier3;   }
    public function setTailleFichier3($tailleFichier3)  { $this->tailleFichier3 = $tailleFichier3;  return $this;    }
    public function getMimeTypeFichier3() { return $this->mimeTypeFichier3;  }
    public function setMimeTypeFichier3($mimeTypeFichier3) { $this->mimeTypeFichier3 = $mimeTypeFichier3;  return $this;  }
    public function getNomOrigineFichier3() { return $this->nomOrigineFichier3; }
    public function setNomOrigineFichier3($nomOrigineFichier3)    { $this->nomOrigineFichier3 = $nomOrigineFichier3;  return $this; }



    public function __construct()
    {
        $this->conventionApprobations = new ArrayCollection();
        $this->conventions = new ArrayCollection();
        $this->arretes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return 'دورة'.' '.$this->month.' '.$this->annee.' '.$this->getTypeSession()->getName();
    }

    public function __toString(): ?string
    {
        return $this->getName();
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;

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

    public function getTypeSession(): ?TypeSession
    {
        return $this->typeSession;
    }

    public function setTypeSession(?TypeSession $typeSession): self
    {
        $this->typeSession = $typeSession;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Convention[]
     */
    public function getConventionApprobations(): Collection
    {
        return $this->conventionApprobations;
    }

    public function addConventionApprobation(Convention $conventionApprobation): self
    {
        if (!$this->conventionApprobations->contains($conventionApprobation)) {
            $this->conventionApprobations[] = $conventionApprobation;
            $conventionApprobation->setSessionApprobation($this);
        }

        return $this;
    }

    public function removeConventionApprobation(Convention $conventionApprobation): self
    {
        if ($this->conventionApprobations->removeElement($conventionApprobation)) {
            // set the owning side to null (unless already changed)
            if ($conventionApprobation->getSessionApprobation() === $this) {
                $conventionApprobation->setSessionApprobation(null);
            }
        }

        return $this;
    }

    public function getMandat(): ?Mandat
    {
        return $this->mandat;
    }

    public function setMandat(?Mandat $mandat): self
    {
        $this->mandat = $mandat;

        return $this;
    }

    public function getLieuSession(): ?string
    {
        return $this->lieuSession;
    }

    public function setLieuSession(?string $lieuSession): self
    {
        $this->lieuSession = $lieuSession;

        return $this;
    }

    public function getNbrePresents(): ?int
    {
        return $this->nbrePresents;
    }

    public function setNbrePresents(?int $nbrePresents): self
    {
        $this->nbrePresents = $nbrePresents;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

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
        }

        return $this;
    }

    public function removeConvention(Convention $convention): self
    {
        $this->conventions->removeElement($convention);

        return $this;
    }

    public function getConventionsTexte(): ?string
    {
        return $this->conventionsTexte;
    }

    public function setConventionsTexte(?string $conventionsTexte): self
    {
        $this->conventionsTexte = $conventionsTexte;

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

    /**
     * @return Collection|Arrete[]
     */
    public function getArretes(): Collection
    {
        return $this->arretes;
    }

    public function addArrete(Arrete $arrete): self
    {
        if (!$this->arretes->contains($arrete)) {
            $this->arretes[] = $arrete;
            $arrete->setSession($this);
        }

        return $this;
    }

    public function removeArrete(Arrete $arrete): self
    {
        if ($this->arretes->removeElement($arrete)) {
            // set the owning side to null (unless already changed)
            if ($arrete->getSession() === $this) {
                $arrete->setSession(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ArreteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=ArreteRepository::class)
 */
class Arrete
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
     * @ORM\Column(type="string", length=255)
     */
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="arretes")
     */
    private $session;

    /**
     * @ORM\ManyToOne(targetEntity=SonataMediaMedia::class, inversedBy="arretes")
     */
    private $media;


    /**
     * @var \DateTime $updatedAt
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    public function getCreatedAt(): \DateTime { return $this->createdAt; }
    public function getUpdatedAt(): \DateTime { return $this->updatedAt; }


    /**
     * @Vich\UploadableField(mapping="divers_fichiers", fileNameProperty="nomFichier", size="tailleFichier", mimeType="mimeTypeFichier", originalName="nomOrigineFichier")
     * @var File
     */
    private $fichier;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $nomFichier;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    private $tailleFichier;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $mimeTypeFichier;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $nomOrigineFichier;

    /**
     * @ORM\Column(type="string", length=550, nullable=true)
     */
    private $objetArrete;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenu;


    public function setFichier(File $fichier = null)  {  $this->fichier = $fichier;  if ($fichier) {  $this->updatedAt = new \DateTimeImmutable();  } return $this;  }
    public function getFichier()  {  return $this->fichier;   }
    public function setNomFichier($nomFichier)  {  $this->nomFichier = $nomFichier;  return $this;  }
    public function getNomFichier()   {  return $this->nomFichier;   }
    public function getTailleFichier()  {  return $this->tailleFichier;   }
    public function setTailleFichier($tailleFichier)  { $this->tailleFichier = $tailleFichier;  return $this;    }
    public function getMimeTypeFichier() { return $this->mimeTypeFichier;  }
    public function setMimeTypeFichier($mimeTypeFichier) { $this->mimeTypeFichier = $mimeTypeFichier;  return $this;  }
    public function getNomOrigineFichier() { return $this->nomOrigineFichier; }
    public function setNomOrigineFichier($nomOrigineFichier)    { $this->nomOrigineFichier = $nomOrigineFichier;  return $this; }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getMedia(): ?SonataMediaMedia
    {
        return $this->media;
    }

    public function setMedia(?SonataMediaMedia $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getObjetArrete(): ?string
    {
        return $this->objetArrete;
    }

    public function setObjetArrete(?string $objetArrete): self
    {
        $this->objetArrete = $objetArrete;

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
}

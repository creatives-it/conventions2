<?php

namespace App\Entity;

use App\Repository\ConventionDocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ConventionDocumentRepository::class)
 */
class ConventionDocument
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Convention::class, inversedBy="conventionDocuments")
     */
    private $convention;

    /**
     * @ORM\ManyToOne(targetEntity=SonataMediaMedia::class, inversedBy="conventionDocuments")
     */
    private $media;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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

    public function getMedia(): ?SonataMediaMedia
    {
        return $this->media;
    }

    public function setMedia(?SonataMediaMedia $media): self
    {
        $this->media = $media;

        return $this;
    }
}

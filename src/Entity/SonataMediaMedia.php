<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sonata\MediaBundle\Entity\BaseMedia;

/**
 * @ORM\Entity
 * @ORM\Table(name="media__media")
 * @ORM\HasLifecycleCallbacks
 */
class SonataMediaMedia extends BaseMedia
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups(groups={"sonata_api_read", "sonata_api_write", "sonata_search"})
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\SonataMediaGalleryHasMedia",
     *     mappedBy="media", cascade={"persist"}, orphanRemoval=false
     * )
     *
     * @var SonataMediaGalleryHasMedia[]
     */
    protected $galleryHasMedias;

    /**
     * Fix annotations if you use classification-bundle.
     *
     * // ORM\ManyToOne(
     *     targetEntity="App\Entity\SonataClassificationCategory",
     *     cascade={"persist"}
     * )
     * // ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var SonataClassificationCategory
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity=ConventionDocument::class, mappedBy="media")
     */
    private $conventionDocuments;

    /**
     * @ORM\ManyToMany(targetEntity=ConventionSuiviExecution::class, mappedBy="medias")
     */
    private $conventionSuiviExecutions;

    /**
     * @ORM\OneToMany(targetEntity=Arrete::class, mappedBy="media")
     */
    private $arretes;


    public function __construct()
    {
        $this->conventionDocuments = new ArrayCollection();
        $this->conventionSuiviExecutions = new ArrayCollection();
        $this->arretes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        parent::prePersist();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(): void
    {
        parent::preUpdate();
    }

    /**
     * @return Collection|ConventionDocument[]
     */
    public function getConventionDocuments(): Collection
    {
        return $this->conventionDocuments;
    }

    public function addConventionDocument(ConventionDocument $conventionDocument): self
    {
        if (!$this->conventionDocuments->contains($conventionDocument)) {
            $this->conventionDocuments[] = $conventionDocument;
            $conventionDocument->setMedia($this);
        }

        return $this;
    }

    public function removeConventionDocument(ConventionDocument $conventionDocument): self
    {
        if ($this->conventionDocuments->removeElement($conventionDocument)) {
            // set the owning side to null (unless already changed)
            if ($conventionDocument->getMedia() === $this) {
                $conventionDocument->setMedia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConventionSuiviExecution[]
     */
    public function getConventionSuiviExecutions(): Collection
    {
        return $this->conventionSuiviExecutions;
    }

    public function addConventionSuiviExecution(ConventionSuiviExecution $conventionSuiviExecution): self
    {
        if (!$this->conventionSuiviExecutions->contains($conventionSuiviExecution)) {
            $this->conventionSuiviExecutions[] = $conventionSuiviExecution;
            $conventionSuiviExecution->addMedia($this);
        }

        return $this;
    }

    public function removeConventionSuiviExecution(ConventionSuiviExecution $conventionSuiviExecution): self
    {
        if ($this->conventionSuiviExecutions->removeElement($conventionSuiviExecution)) {
            $conventionSuiviExecution->removeMedia($this);
        }

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
            $arrete->setMedia($this);
        }

        return $this;
    }

    public function removeArrete(Arrete $arrete): self
    {
        if ($this->arretes->removeElement($arrete)) {
            // set the owning side to null (unless already changed)
            if ($arrete->getMedia() === $this) {
                $arrete->setMedia(null);
            }
        }

        return $this;
    }

}

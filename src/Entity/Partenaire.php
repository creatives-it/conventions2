<?php

namespace App\Entity;

use App\Repository\PartenaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Gedmo\Loggable
 * @Vich\Uploadable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @UniqueEntity(
 *     fields={"name", "deletedAt"},
 *     ignoreNull=false,
 *     errorPath="name",
 *     message="Un partenaire du même nom est déjà enregistré"
 * )
 * @ORM\Entity(repositoryClass=PartenaireRepository::class)
 */
class Partenaire
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Convention::class, mappedBy="maitreOuvrage")
     */
    private $conventionMaitreOuvrages;

    /**
     * @ORM\OneToMany(targetEntity=ConventionContribution::class, mappedBy="partenaire", orphanRemoval=true)
     */
    private $conventionContributions;

    /**
     * @ORM\OneToMany(targetEntity=ConventionVersement::class, mappedBy="partenaire")
     */
    private $conventionVersements;

    /**
     * @ORM\OneToMany(targetEntity=Convention::class, mappedBy="maitreOuvrage2")
     */
    private $maitreOuvrages2;

    /**
     * @ORM\ManyToMany(targetEntity=Convention::class, mappedBy="partieContractantes")
     */
    private $conventionPartieContractantes;

    /**
     * @ORM\OneToMany(targetEntity=ConventionEngagement::class, mappedBy="partenaire")
     */
    private $conventionEngagements;

    /**
     * @ORM\OneToMany(targetEntity=ConventionSignature::class, mappedBy="partenaire")
     */
    private $conventionSignatures;



    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;
    /**
     * @Vich\UploadableField(mapping="divers_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;
    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function setImageFile(File $image = null) { $this->imageFile = $image;  if ($image) {  $this->updatedAt = new \DateTime('now');  }    }
    public function getImageFile()  { return $this->imageFile;    }
    public function setImage($image) { $this->image = $image;    }
    public function getImage() { return $this->image;    }
    public function getUpdatedAt(): \DateTime  { return $this->updatedAt;    }
    public function setUpdatedAt(\DateTime $updatedAt): void { $this->updatedAt = $updatedAt;    }


    public function __construct()
    {
        $this->conventionMaitreOuvrages = new ArrayCollection();
        $this->conventionContributions = new ArrayCollection();
        $this->conventionVersements = new ArrayCollection();
        $this->maitreOuvrages2 = new ArrayCollection();
        $this->conventionPartieContractantes = new ArrayCollection();
        $this->conventionEngagements = new ArrayCollection();
        $this->conventionSignatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Convention[]
     */
    public function getConventionMaitreOuvrages(): Collection
    {
        return $this->conventionMaitreOuvrages;
    }

    public function addConventionMaitreOuvrage(Convention $conventionMaitreOuvrage): self
    {
        if (!$this->conventionMaitreOuvrages->contains($conventionMaitreOuvrage)) {
            $this->conventionMaitreOuvrages[] = $conventionMaitreOuvrage;
            $conventionMaitreOuvrage->setMaitreOuvrage($this);
        }

        return $this;
    }

    public function removeConventionMaitreOuvrage(Convention $conventionMaitreOuvrage): self
    {
        if ($this->conventionMaitreOuvrages->removeElement($conventionMaitreOuvrage)) {
            // set the owning side to null (unless already changed)
            if ($conventionMaitreOuvrage->getMaitreOuvrage() === $this) {
                $conventionMaitreOuvrage->setMaitreOuvrage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConventionContribution[]
     */
    public function getConventionContributions(): Collection
    {
        return $this->conventionContributions;
    }

    public function addConventionContribution(ConventionContribution $conventionContribution): self
    {
        if (!$this->conventionContributions->contains($conventionContribution)) {
            $this->conventionContributions[] = $conventionContribution;
            $conventionContribution->setPartenaire($this);
        }

        return $this;
    }

    public function removeConventionContribution(ConventionContribution $conventionContribution): self
    {
        if ($this->conventionContributions->removeElement($conventionContribution)) {
            // set the owning side to null (unless already changed)
            if ($conventionContribution->getPartenaire() === $this) {
                $conventionContribution->setPartenaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConventionVersement[]
     */
    public function getConventionVersements(): Collection
    {
        return $this->conventionVersements;
    }

    public function addConventionVersement(ConventionVersement $conventionVersement): self
    {
        if (!$this->conventionVersements->contains($conventionVersement)) {
            $this->conventionVersements[] = $conventionVersement;
            $conventionVersement->setPartenaire($this);
        }

        return $this;
    }

    public function removeConventionVersement(ConventionVersement $conventionVersement): self
    {
        if ($this->conventionVersements->removeElement($conventionVersement)) {
            // set the owning side to null (unless already changed)
            if ($conventionVersement->getPartenaire() === $this) {
                $conventionVersement->setPartenaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Convention[]
     */
    public function getMaitreOuvrages2(): Collection
    {
        return $this->maitreOuvrages2;
    }

    public function addMaitreOuvrages2(Convention $maitreOuvrages2): self
    {
        if (!$this->maitreOuvrages2->contains($maitreOuvrages2)) {
            $this->maitreOuvrages2[] = $maitreOuvrages2;
            $maitreOuvrages2->setMaitreOuvrage2($this);
        }

        return $this;
    }

    public function removeMaitreOuvrages2(Convention $maitreOuvrages2): self
    {
        if ($this->maitreOuvrages2->removeElement($maitreOuvrages2)) {
            // set the owning side to null (unless already changed)
            if ($maitreOuvrages2->getMaitreOuvrage2() === $this) {
                $maitreOuvrages2->setMaitreOuvrage2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Convention[]
     */
    public function getConventionPartieContractantes(): Collection
    {
        return $this->conventionPartieContractantes;
    }

    public function addConventionPartieContractante(Convention $conventionPartieContractante): self
    {
        if (!$this->conventionPartieContractantes->contains($conventionPartieContractante)) {
            $this->conventionPartieContractantes[] = $conventionPartieContractante;
            $conventionPartieContractante->addPartieContractante($this);
        }

        return $this;
    }

    public function removeConventionPartieContractante(Convention $conventionPartieContractante): self
    {
        if ($this->conventionPartieContractantes->removeElement($conventionPartieContractante)) {
            $conventionPartieContractante->removePartieContractante($this);
        }

        return $this;
    }

    /**
     * @return Collection|ConventionEngagement[]
     */
    public function getConventionEngagements(): Collection
    {
        return $this->conventionEngagements;
    }

    public function addConventionEngagement(ConventionEngagement $conventionEngagement): self
    {
        if (!$this->conventionEngagements->contains($conventionEngagement)) {
            $this->conventionEngagements[] = $conventionEngagement;
            $conventionEngagement->setPartenaire($this);
        }

        return $this;
    }

    public function removeConventionEngagement(ConventionEngagement $conventionEngagement): self
    {
        if ($this->conventionEngagements->removeElement($conventionEngagement)) {
            // set the owning side to null (unless already changed)
            if ($conventionEngagement->getPartenaire() === $this) {
                $conventionEngagement->setPartenaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConventionSignature[]
     */
    public function getConventionSignatures(): Collection
    {
        return $this->conventionSignatures;
    }

    public function addConventionSignature(ConventionSignature $conventionSignature): self
    {
        if (!$this->conventionSignatures->contains($conventionSignature)) {
            $this->conventionSignatures[] = $conventionSignature;
            $conventionSignature->setPartenaire($this);
        }

        return $this;
    }

    public function removeConventionSignature(ConventionSignature $conventionSignature): self
    {
        if ($this->conventionSignatures->removeElement($conventionSignature)) {
            // set the owning side to null (unless already changed)
            if ($conventionSignature->getPartenaire() === $this) {
                $conventionSignature->setPartenaire(null);
            }
        }

        return $this;
    }
}

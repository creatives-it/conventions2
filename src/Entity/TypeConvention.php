<?php

namespace App\Entity;

use App\Repository\TypeConventionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Entity(repositoryClass=TypeConventionRepository::class)
 */
class TypeConvention
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
     * @ORM\OneToMany(targetEntity=Convention::class, mappedBy="typeConvention")
     */
    private $conventions;

    public function __construct()
    {
        $this->conventions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): ?string
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
    public function getConventions(): Collection
    {
        return $this->conventions;
    }

    public function addConvention(Convention $convention): self
    {
        if (!$this->conventions->contains($convention)) {
            $this->conventions[] = $convention;
            $convention->setTypeConvention($this);
        }

        return $this;
    }

    public function removeConvention(Convention $convention): self
    {
        if ($this->conventions->removeElement($convention)) {
            // set the owning side to null (unless already changed)
            if ($convention->getTypeConvention() === $this) {
                $convention->setTypeConvention(null);
            }
        }

        return $this;
    }
}

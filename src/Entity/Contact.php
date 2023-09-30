<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
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
    private $nom;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gsm1;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gsm2;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $whatsapp;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity=OrganeGouvernance::class, mappedBy="membres")
     */
    private $organeGouvernances;

    public function __construct()
    {
        $this->organeGouvernances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): ?string
    {
        return $this->nom;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGsm1()
    {
        return $this->gsm1;
    }

    /**
     * @param mixed $gsm1
     */
    public function setGsm1($gsm1): void
    {
        $this->gsm1 = $gsm1;
    }

    /**
     * @return mixed
     */
    public function getGsm2()
    {
        return $this->gsm2;
    }

    /**
     * @param mixed $gsm2
     */
    public function setGsm2($gsm2): void
    {
        $this->gsm2 = $gsm2;
    }


    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getWhatsapp(): ?string
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(?string $whatsapp): self
    {
        $this->whatsapp = $whatsapp;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|OrganeGouvernance[]
     */
    public function getOrganeGouvernances(): Collection
    {
        return $this->organeGouvernances;
    }

    public function addOrganeGouvernance(OrganeGouvernance $organeGouvernance): self
    {
        if (!$this->organeGouvernances->contains($organeGouvernance)) {
            $this->organeGouvernances[] = $organeGouvernance;
            $organeGouvernance->addMembre($this);
        }

        return $this;
    }

    public function removeOrganeGouvernance(OrganeGouvernance $organeGouvernance): self
    {
        if ($this->organeGouvernances->removeElement($organeGouvernance)) {
            $organeGouvernance->removeMembre($this);
        }

        return $this;
    }
}

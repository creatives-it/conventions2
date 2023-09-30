<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use App\Repository\EntiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Tree(type="nested")
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Entity(repositoryClass=EntiteRepository::class)
 */
class Entite
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
     * @ORM\OneToMany(targetEntity=Convention::class, mappedBy="entiteSuiviExecution")
     */
    private $conventionSuiviExecutions;

    /**
     * @ORM\ManyToMany(targetEntity=Convention::class, mappedBy="entiteConsultantes")
     */
    private $conventionsConsultantes;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="entiteConsultantes")
     */
    private $userConsultantes;

    /**
     * @ORM\ManyToOne(targetEntity=TypeEntite::class, inversedBy="entites")
     */
    private $typeEntite;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;
    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;
    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;
    /**
     * @Gedmo\TreeRoot
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=Entite::class)
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;
    /**
     * @Gedmo\TreeParent
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=Entite::class, inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;
    /**
     * @ORM\OneToMany(targetEntity=Entite::class, mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    public function getRoot() { return $this->root;   }
    public function setParent(Entite $parent = null) { $this->parent = $parent; }
    public function getParent() {  return $this->parent;  }
    public function getChildren() {  return $this->children;  }



    public function __construct()
    {
        $this->conventionSuiviExecutions = new ArrayCollection();
        $this->conventionsConsultantes = new ArrayCollection();
        $this->userConsultantes = new ArrayCollection();
    }

    public function getAllAscendants(?Entite $node = null, $parents = []): array
    {
        if (!$node) {
            $node = $this;
        }
        if (null !== $this->getParent()) {
            $parent = $this->getParent();
            $parents[] = $parent;
            return $parent->getAllAscendants($node, $parents);
        }
        return $parents;
    }
    public function getAllDescendants(): Collection
    {
        $descendants = [];
        if (!$this->getChildren()->isEmpty()) {
            $children = $this->getChildren();
            $descendants[] = $children;
            foreach ($children as $child) {
                if (!$child->getChildren()->isEmpty()) {
                    $descendants[] =  $child->getChildren();
                    foreach ($child->getChildren() as $child2) {
                        if (!$child2->getChildren()->isEmpty()) {
                            $descendants[] =  $child2->getChildren();
                            foreach ($child2->getChildren() as $child3) {
                                if (!$child3->getChildren()->isEmpty()) {
                                    $descendants[] =  $child3->getChildren();
                                }
                            }
                        }
                    }
                }
            }

        }
        $xDescendants = new ArrayCollection();
        foreach ($descendants as $descendant) {
            foreach ($descendant as $d) {
                $xDescendants[] = $d;
                //array_push($xDescendants, $d);
            }
        }
        return $xDescendants;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        $c = '';
        /*switch ($this->lvl):
            case 0: $c = '';break;
            case 1: $c = '--';break;
            case 2: $c = '----';break;
            case 3: $c = '------';break;
            case 4: $c = '--------';break;
            case 5: $c = '----------';break;
            case 6: $c = '------------';break;
        endswitch;*/
        return $c.$this->name;
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
    public function getConventionSuiviExecutions(): Collection
    {
        return $this->conventionSuiviExecutions;
    }

    public function addConventionSuiviExecution(Convention $conventionSuiviExecution): self
    {
        if (!$this->conventionSuiviExecutions->contains($conventionSuiviExecution)) {
            $this->conventionSuiviExecutions[] = $conventionSuiviExecution;
            $conventionSuiviExecution->setEntiteSuiviExecution($this);
        }

        return $this;
    }

    public function removeConventionSuiviExecution(Convention $conventionSuiviExecution): self
    {
        if ($this->conventionSuiviExecutions->removeElement($conventionSuiviExecution)) {
            // set the owning side to null (unless already changed)
            if ($conventionSuiviExecution->getEntiteSuiviExecution() === $this) {
                $conventionSuiviExecution->setEntiteSuiviExecution(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Convention[]
     */
    public function getConventionsConsultantes(): Collection
    {
        return $this->conventionsConsultantes;
    }

    public function addConventionsConsultante(Convention $conventionsConsultante): self
    {
        if (!$this->conventionsConsultantes->contains($conventionsConsultante)) {
            $this->conventionsConsultantes[] = $conventionsConsultante;
            $conventionsConsultante->addEntiteConsultante($this);
        }

        return $this;
    }

    public function removeConventionsConsultante(Convention $conventionsConsultante): self
    {
        if ($this->conventionsConsultantes->removeElement($conventionsConsultante)) {
            $conventionsConsultante->removeEntiteConsultante($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserConsultantes(): Collection
    {
        return $this->userConsultantes;
    }

    public function addUserConsultante(User $userConsultante): self
    {
        if (!$this->userConsultantes->contains($userConsultante)) {
            $this->userConsultantes[] = $userConsultante;
            $userConsultante->addEntiteConsultante($this);
        }

        return $this;
    }

    public function removeUserConsultante(User $userConsultante): self
    {
        if ($this->userConsultantes->removeElement($userConsultante)) {
            $userConsultante->removeEntiteConsultante($this);
        }

        return $this;
    }

    public function getTypeEntite(): ?TypeEntite
    {
        return $this->typeEntite;
    }

    public function setTypeEntite(?TypeEntite $typeEntite): self
    {
        $this->typeEntite = $typeEntite;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLvl()
    {
        return $this->lvl;
    }

}

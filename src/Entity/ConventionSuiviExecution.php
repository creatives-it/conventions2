<?php

namespace App\Entity;

use App\Repository\ConventionSuiviExecutionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ConventionSuiviExecutionRepository::class)
 */
class ConventionSuiviExecution
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Convention::class, inversedBy="conventionSuiviExecutions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $convention;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=550, nullable=true)
     */
    private $decisions;

    /**
     * @ORM\ManyToOne(targetEntity=Stade::class, inversedBy="conventionSuiviExecutions")
     */
    private $stade;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tauxAvancement;

    /**
     * @ORM\Column(type="string", length=550, nullable=true)
     */
    private $observation;

    /**
     * @ORM\ManyToMany(targetEntity=OrganeGouvernance::class, inversedBy="conventionSuiviExecutions")
     */
    private $organeGouvernances;

    /**
     * @ORM\ManyToMany(targetEntity=SonataMediaMedia::class, inversedBy="conventionSuiviExecutions")
     */
    private $medias;

    public function __construct()
    {
        $this->organeGouvernances = new ArrayCollection();
        $this->medias = new ArrayCollection();
    }

    public function getTitle() {
        return 'إجتماع '.$this->getOrganeGouvernancesString();
        return 'إجتماع اللجنة';
    }


    public function getOrganeGouvernancesString()
    {
        foreach ($this->organeGouvernances as $organeGouvernance):
            $return[] = $organeGouvernance->getName();
        endforeach;
        return implode(" و ",$return);
    }

    public function getEndDate()
    {
        return null;
        //return $this->getDate();
        //return $this->getDate()->add(new \DateInterval("PT0H"));
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDecisions(): ?string
    {
        return $this->decisions;
    }

    public function setDecisions(?string $decisions): self
    {
        $this->decisions = $decisions;

        return $this;
    }

    public function getStade(): ?Stade
    {
        return $this->stade;
    }

    public function setStade(?Stade $stade): self
    {
        $this->stade = $stade;

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

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): self
    {
        $this->observation = $observation;

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
        }

        return $this;
    }

    public function removeOrganeGouvernance(OrganeGouvernance $organeGouvernance): self
    {
        $this->organeGouvernances->removeElement($organeGouvernance);

        return $this;
    }

    /**
     * @return Collection|SonataMediaMedia[]
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(SonataMediaMedia $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
        }

        return $this;
    }

    public function removeMedia(SonataMediaMedia $media): self
    {
        $this->medias->removeElement($media);

        return $this;
    }
}

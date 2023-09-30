<?php
namespace App\Application\Sonata\UserBundle\Entity;
use App\Entity\Contact;
use App\Entity\Convention;
use App\Entity\ConventionLecture;
use App\Entity\ConventionSuivi;
use App\Entity\Entite;
use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="fos_user_user")
 * @ORM\Entity()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $poste;


    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $autresInfos;


    /**
     * @ORM\ManyToMany(targetEntity=Entite::class, inversedBy="usersConsultantes")
     */
    private $entiteConsultantes;

    /**
     * @ORM\OneToMany(targetEntity=ConventionLecture::class, mappedBy="readBy")
     */
    private $conventionLectures;

    /**
     * @ORM\OneToMany(targetEntity=ConventionSuivi::class, mappedBy="user")
     */
    private $conventionSuivis;


    public function __construct()
    {
        parent::__construct();
        $this->entiteConsultantes = new ArrayCollection();
        $this->conventionLectures = new ArrayCollection();
    }

    public function __toString()
    {
        if (parent::getFirstname() != null) {
            return parent::getFullname();
        } else {
            return parent::getUsername();
        }
    }


    /**
     * @return Collection|Entite[]
     */
    public function getEntiteConsultantes(): Collection
    {
        return $this->entiteConsultantes;
    }

    public function addEntiteConsultante(Entite $entiteConsultante): self
    {
        if (!$this->entiteConsultantes->contains($entiteConsultante)) {
            $this->entiteConsultantes[] = $entiteConsultante;
        }

        return $this;
    }

    public function removeEntiteConsultante(Entite $entiteConsultante): self
    {
        $this->entiteConsultantes->removeElement($entiteConsultante);

        return $this;
    }



    /**
     * @return bool
     */
    public function isSuivi(Convention $convention)
    {
        $conventionSuivis = $this->getConventionSuivis();
        $return = false;
        foreach ($conventionSuivis as $conventionSuivi):
            if ($conventionSuivi->getConvention() == $convention) $return = true;
        endforeach;

        return $return;
    }

    /**
     * @return bool
    */
    public function isRead(Convention $convention)
    {
        $conventionLectures = $this->getConventionLectures();
        $return = false;
        foreach ($conventionLectures as $conventionLecture):
            if ($conventionLecture->getConvention() == $convention) $return = true;
        endforeach;

        return $return;
    }

    public function getContactLast()
    {
        /*$rows = $this->contacts->filter(function(Contact $contact) {
            return ($contact->getEnabled()) ;
        }) ;
        $iterator = $rows->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getId() < $b->getId()) ? -1 : 1;
        });
        $return = new \Doctrine\Common\Collections\ArrayCollection(iterator_to_array($iterator));
        return $return->last();*/
    }

    /**
     * Get id.
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAutresInfos()
    {
        return $this->autresInfos;
    }

    /**
     * @param mixed $autresInfos
     */
    public function setAutresInfos($autresInfos): void
    {
        $this->autresInfos = $autresInfos;
    }

    /**
     * @return mixed
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * @param mixed $poste
     */
    public function setPoste($poste): void
    {
        $this->poste = $poste;
    }


    /**
     * @return Collection|ConventionLecture[]
     */
    public function getConventionLectures(): Collection
    {
        return $this->conventionLectures;
    }

    public function addConventionLecture(ConventionLecture $conventionLecture): self
    {
        if (!$this->conventionLectures->contains($conventionLecture)) {
            $this->conventionLectures[] = $conventionLecture;
            $conventionLecture->setReadBy($this);
        }

        return $this;
    }

    public function removeConventionLecture(ConventionLecture $conventionLecture): self
    {
        if ($this->conventionLectures->removeElement($conventionLecture)) {
            // set the owning side to null (unless already changed)
            if ($conventionLecture->getReadBy() === $this) {
                $conventionLecture->setReadBy(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|ConventionSuivi[]
     */
    public function getConventionSuivis(): Collection
    {
        return $this->conventionSuivis;
    }

    public function addConventionSuivi(ConventionSuivi $conventionSuivi): self
    {
        if (!$this->conventionSuivis->contains($conventionSuivi)) {
            $this->conventionSuivis[] = $conventionSuivi;
            $conventionSuivi->setUser($this);
        }

        return $this;
    }

    public function removeConventionSuivi(ConventionSuivi $conventionSuivi): self
    {
        if ($this->conventionSuivis->removeElement($conventionSuivi)) {
            // set the owning side to null (unless already changed)
            if ($conventionSuivi->getUser() === $this) {
                $conventionSuivi->setUser(null);
            }
        }

        return $this;
    }
}

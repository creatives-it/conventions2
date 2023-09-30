<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use App\Repository\ConventionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=ConventionRepository::class)
 */
class Convention
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numero;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=TypeConvention::class, inversedBy="conventions")
     */
    private $typeConvention;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=500)
     */
    private $objetConvention;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=NatureConvention::class, inversedBy="conventions")
     */
    private $natureConvention;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="decimal", precision=15, scale=2, nullable=true)
     */
    private $montantConvention;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateSignature;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $duree;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDeliberation;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateSessionApprobation;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="boolean")
     */
    private $vise = 1;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateVisa;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=Secteur::class, inversedBy="conventions")
     */
    private $secteur;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $localisation1;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=Partenaire::class, inversedBy="conventionMaitreOuvrages")
     */
    private $maitreOuvrage;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=Stade::class, inversedBy="conventions")
     * @ORM\OrderBy({"position"="ASC"})
     */
    private $stade;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true)
     */
    private $modaliteFinancement;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="boolean")
     */
    private $active = 1;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="boolean")
     */
    private $isAvenant = 0;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=Convention::class, inversedBy="avenants")
     */
    private $avenantA;

    /**
     * @ORM\OneToMany(targetEntity=Convention::class, mappedBy="avenantA")
     */
    private $avenants;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true)
     */
    private $observations;


    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=Partenaire::class, inversedBy="maitreOuvrages2")
     */
    private $maitreOuvrage2;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeroAn;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=Entite::class, inversedBy="conventionSuiviExecutions")
     */
    private $entiteSuiviExecution;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateSession;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSignee;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $organesSuivi;

    /**
     * @ORM\ManyToMany(targetEntity=Partenaire::class, inversedBy="conventionPartieContractantes")
     */
    private $partieContractantes;

    /**
     * @ORM\ManyToMany(targetEntity=Thematique::class, inversedBy="conventions")
     */
    private $thematiques;

    /**
     * @ORM\ManyToMany(targetEntity=OrganeGouvernance::class, inversedBy="conventions")
     */
    private $organeGouvernances;

    /**
     * @ORM\ManyToMany(targetEntity=Localisation::class, inversedBy="convention2s")
     */
    private $localisations;

    /**
     * @ORM\ManyToMany(targetEntity=DocumentPlanification::class, inversedBy="conventions")
     */
    private $documentPlanifications;


    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true)
     */
    private $observation1;


    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=StatutConvention::class, inversedBy="conventions")
     */
    private $statutConvention;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     */
    private $num2;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true)
     */
    private $consistance;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true)
     */
    private $objectifsConvention;


    /**
     * @ORM\OneToMany(targetEntity=ConventionDocument::class, mappedBy="convention", cascade={"persist"}, orphanRemoval=true)
     */
    private $conventionDocuments;

    /**
     * @ORM\OneToMany(targetEntity=ConventionVersement::class, mappedBy="convention", cascade={"persist"}, orphanRemoval=true)
     */
    private $conventionVersements;

    /**
     * @ORM\OneToMany(targetEntity=ConventionEngagement::class, mappedBy="convention", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"partenaire" = "ASC","annee" = "ASC"})
     */
    private $conventionEngagements;

    /**
     * @ORM\OneToMany(targetEntity=ConventionContribution::class, mappedBy="convention", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"partenaire" = "ASC","annee" = "ASC"})
     */
    private $conventionContributions;

    /**
     * @ORM\OneToMany(targetEntity=ConventionSignature::class, mappedBy="convention", cascade={"persist"}, orphanRemoval=true)
     */
    private $conventionSignatures;

    /**
     * @ORM\OneToMany(targetEntity=ConventionStade::class, mappedBy="convention", cascade={"persist"}, orphanRemoval=true)
     */
    private $conventionStades;

    /**
     * @ORM\OneToMany(targetEntity=ConventionSuiviExecution::class, mappedBy="convention", cascade={"persist"}, orphanRemoval=true)
     */
    private $conventionSuiviExecutions;

    /**
     * @ORM\OneToMany(targetEntity=ConventionEngagementRegion::class, mappedBy="convention", cascade={"persist"}, orphanRemoval=true)
     */
    private $conventionEngagementRegions;



    /**
     * @var \DateTime $createdAt
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    public function getCreatedAt(): \DateTime { return $this->createdAt; }
    public function getUpdatedAt(): \DateTime { return $this->updatedAt; }

    /**
     * @var User $createdBy
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    private $createdBy;
    /**
     * @var User $updatedBy
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     */
    private $updatedBy;
    public function getCreatedBy()  { return $this->createdBy; }
    public function getUpdatedBy()  { return $this->updatedBy; }


    /**
     * @Vich\UploadableField(mapping="document_signe_convention", fileNameProperty="nomFichier", size="tailleFichier", mimeType="mimeTypeFichier", originalName="nomOrigineFichier")
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
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $fichier
     * @return Convention
     */
    public function setFichier(File $fichier = null)
    {
        $this->fichier = $fichier;
        if ($fichier) {
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    /**
     * @return File|null
     */
    public function getFichier()
    {
        return $this->fichier;
    }

    /**
     * Set nomFichier
     *
     * @param string $nomFichier
     * @return Convention
     */
    public function setNomFichier($nomFichier)
    {
        $this->nomFichier = $nomFichier;

        return $this;
    }

    /**
     * Get nomFichier
     *
     * @return string
     */
    public function getNomFichier()
    {
        return $this->nomFichier;
    }

    /**
     * @return integer
     */
    public function getTailleFichier()
    {
        return $this->tailleFichier;
    }

    /**
     * @param integer $tailleFichier
     * @return Convention
     */
    public function setTailleFichier($tailleFichier)
    {
        $this->tailleFichier = $tailleFichier;

        return $this;
    }

    /**
     * @return string
     */
    public function getMimeTypeFichier()
    {
        return $this->mimeTypeFichier;
    }

    /**
     * @param string $mimeTypeFichier
     */
    public function setMimeTypeFichier($mimeTypeFichier)
    {
        $this->mimeTypeFichier = $mimeTypeFichier;

        return $this;
    }

    /**
     * @return string
     */
    public function getNomOrigineFichier()
    {
        return $this->nomOrigineFichier;
    }

    /**
     * @param string $nomOrigineFichier
     */
    public function setNomOrigineFichier($nomOrigineFichier)
    {
        $this->nomOrigineFichier = $nomOrigineFichier;

        return $this;
    }




    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeroDecision;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateReceptionConvention;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreExemplaireOriginaux;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateTransmissionEntiteSuiviExecution;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $receptionneePar;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateTransmissionPourVisa;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true)
     */
    private $observationsVisa;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=Localisation::class, inversedBy="conventions")
     */
    private $localisation; // mis a jour automatiquement a chaque sauvegarde

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=DomaineCompetence::class, inversedBy="conventions")
     */
    private $domaineCompetence;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="conventionApprobations")
     */
    private $sessionApprobation;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anneeEngagement1;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anneeEngagement2;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="boolean")
     */
    private $archived = 0;

    /**
     * @ORM\OneToMany(targetEntity=Projet::class, mappedBy="convention")
     */
    private $projets;

    /**
     * @ORM\ManyToMany(targetEntity=Session::class, mappedBy="conventions")
     */
    private $sessions;

    /**
     * @ORM\OneToMany(targetEntity=ConventionRepartitionLocalisation::class, mappedBy="convention",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $conventionRepartitionLocalisations;

    /**
     * @ORM\ManyToMany(targetEntity=Entite::class, inversedBy="conventionsConsultantes")
     */
    private $entiteConsultantes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $emplacement;

    /**
     * @ORM\OneToMany(targetEntity=ConventionLecture::class, mappedBy="convention")
     */
    private $conventionLectures;

    /**
     * @ORM\OneToMany(targetEntity=ConventionSuivi::class, mappedBy="convention")
     */
    private $conventionSuivis;

    /**
     * @ORM\ManyToOne(targetEntity=StadeElaboration::class, inversedBy="conventions")
     * @ORM\OrderBy({"position"="ASC"})
     */
    private $stadeElaboration;

    /**
     * @ORM\ManyToOne(targetEntity=StadeExecution::class, inversedBy="conventions")
     * @ORM\OrderBy({"position"="ASC"})
     */
    private $stadeExecution;


    public function __construct()
    {
        $this->avenants = new ArrayCollection();
        $this->conventionDocuments = new ArrayCollection();
        $this->thematiques = new ArrayCollection();
        $this->conventionVersements = new ArrayCollection();
        $this->partieContractantes = new ArrayCollection();
        $this->conventionEngagements = new ArrayCollection();
        $this->conventionSignatures = new ArrayCollection();
        $this->conventionContributions = new ArrayCollection();
        $this->localisations = new ArrayCollection();
        $this->conventionStades = new ArrayCollection();
        $this->conventionSuiviExecutions = new ArrayCollection();
        $this->documentPlanifications = new ArrayCollection();
        $this->organeGouvernances = new ArrayCollection();
        $this->projets = new ArrayCollection();
        $this->conventionEngagementRegions = new ArrayCollection();
        $this->sessions = new ArrayCollection();
        $this->conventionRepartitionLocalisations = new ArrayCollection();
        $this->entiteConsultantes = new ArrayCollection();
        $this->conventionLectures = new ArrayCollection();
    }
    

    public function __toString(): string
    {
        $return = $this->getId()." ".$this->objetConvention;
        if (!empty($this->getNumeroAn())) $return .= " (".$this->getNumeroAn().")";
        return $return;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param $user
     * @return bool
     */
    public function isReadBy(User $user)
    {
        $conventionLectures = $this->conventionLectures->filter(function(ConventionLecture $conventionLecture) use ($user) {
            return ($conventionLecture->getReadBy() == $user) ;
        });
        if (!$conventionLectures->isEmpty()) return true;
        return false;
    }

    /**
     */
    public function getMontantEngagementGlobalRegion()
    {
        $conventionEngagements = $this->conventionEngagements->filter(function(ConventionEngagement $conventionEngagement) {
            if ($conventionEngagement->getPartenaire()) {
                return ($conventionEngagement->getPartenaire()->getId() === 1);
            }
            return false;
        });
        if ($this->conventionEngagements->isEmpty()) return null;
        $return = 0;
        foreach ($conventionEngagements as $conventionEngagement):
            $return += $conventionEngagement->getMontantProgramme();
        endforeach;
        return $return;
        return count($conventionEngagements);
    }

    /**
     */
    public function getMontantEngagementAnnuelRegion($annee)
    {
        $conventionEngagements = $this->conventionEngagements->filter(function(ConventionEngagement $conventionEngagement) use ($annee) {
            if ($conventionEngagement->getPartenaire()) {
                return (($conventionEngagement->getAnnee() === $annee) and ($conventionEngagement->getPartenaire()->getId() === 1));
            }
            return false;
        });
        if ($this->conventionEngagements->isEmpty()) return null;
        $return = 0;
        foreach ($conventionEngagements as $conventionEngagement):
            $return += $conventionEngagement->getMontantProgramme();
        endforeach;
        return $return;
    }

    /**
     */
    public function getMontantContributionAnnuelRegion($annee)
    {
        $conventionContributions = $this->conventionContributions->filter(function(ConventionContribution $conventionContribution) use ($annee) {
            if ($conventionContribution->getPartenaire()) {
                return (($conventionContribution->getAnnee() === $annee) and ($conventionContribution->getPartenaire()->getId() === 1));
            }
            return false;
        });
        if ($this->conventionContributions->isEmpty()) {return null;}
        $return = 0;
        foreach ($conventionContributions as $conventionContribution):
            $return += $conventionContribution->getMontant();
        endforeach;
        return $return;
    }


    public function getLocalisation(): ?Localisation
    {
        return $this->localisation;
    }

    public function setLocalisation(?Localisation $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }/**/

    /**
     */
    public function getEstSigneeParPartenaires()
    {
        $conventionSignatures = $this->conventionSignatures->filter(function(ConventionSignature $conventionSignature) {
            return (!$conventionSignature->getIsSignee());
        });
        if ($this->conventionSignatures->isEmpty()) return null;
        return ($conventionSignatures->isEmpty()) ? 1 : 0;
    }

    /**
     * delais ecoules pour chaque partenaire pour signature
     */
    public function getDelaisConsommesPourSignaturePartenaires()
    {
        $conventionSignatures = $this->conventionSignatures->filter(function(ConventionSignature $conventionSignature) {
            return (!$conventionSignature->getIsSignee());
        });
        $delais = [];
        foreach ($conventionSignatures as $conventionSignature):
            if ($conventionSignature->getDelaiConsommePourSignature() > 0)
                $delais[] = $conventionSignature->getDelaiConsommePourSignature();
        endforeach;

        return $delais;
    }

    /**
     * delai moyen pour tous les partenaires pour signature
     */
    public function getDelaiMoyenConsommePourSignaturePartenaires()
    {
        $delais = $this->getDelaisConsommesPourSignaturePartenaires();
        $delai = (!empty($delais)) ? ceil(array_sum($delais)/count($delais)) : null;
        return $delai;
    }

    /**
     * delai ecoule depuis transmission si convention non visee
     */
    public function getDelaiAttenteVisa()
    {
        if ($this->getEstVisee()) return null;
        return $this->getDelaiConsommePourVisa();
    }

    /**
     * delai ecoule entre la date transmission et aujourdhui/dateVisa
     */
    public function getDelaiConsommePourVisa()
    {
        $date1 = $this->getDateTransmissionPourVisa();
        $date2 = $this->getDateVisa();
        if ((null !== $date1) and (null === $date2)) {
            $now = new \Datetime();
            $diff =  $date1->diff($now);
            $delai   = intval($diff->days);
        } elseif ((null !== $date1) && (null !== $date2)) {
            $diff =  $date1->diff($date2);
            $delai   = intval($diff->days);
        } else {
            $delai = "";
        }

        return $delai;
    }

    /**
     */
    public function getEstVisee()
    {
        if ($this->vise) return 1;
        if (!empty($this->getDateVisa())) return 1;
        if (!empty($this->getDateTransmissionPourVisa())) return 0;

    }

    public function getEndDateSignature()
    {
        return null;
        //return $this->getDate();
        return $this->getDateSignature()->add(new \DateInterval("PT0H"));
    }

    public function getEngagementsProgrammesPartenairesAsTable(): ?array
    {
        $array = $this->getEngagementsProgrammesPartenaires();
        $return = [];
        $return['entete']['titre'] = 'السنة';
        $return['entete']['valeurs'] = $this->getEngagementsAnnees();
        foreach ($array as $key=>$value):
            $return[$key]['titre']= $key;
            $return[$key]['valeurs']= $value;
        endforeach;
        return $return;
    }
    public function getEngagementsProgrammesPartenaires(): ?array
    {
        $rows = $this->getConventionEngagements();
        $annees = $this->getEngagementsAnnees();
        $partenaires = $this->getPartenairesEngages();
        $return = $array = [];
        foreach ($annees as $annee):
            foreach ($partenaires as $partenaire):
                $array[$partenaire][$annee] = 0;
            endforeach;
        endforeach;
        foreach ($rows as $row):
            $array[$row->getPartenaire()->getName()][$row->getAnnee()]= $row->getMontantProgramme()/1000000;
        endforeach;
        $return = $array;
        return $return;
    }

    public function getEngagementsProgrammesPartenairesDecroissant(): ?array
    {
        $annees = $this->getEngagementsAnneesDecroissant();
        $partenaires = $this->getPartenairesEngages();
        $array = [];
        foreach ($annees as $annee):
            foreach ($partenaires as $partenaire):
                $array[$partenaire][$annee] = 0;
            endforeach;
        endforeach;
        $rows = $this->getConventionEngagements();
        foreach ($rows as $row):
            $array[$row->getPartenaire()->getName()][$row->getAnnee()]= $row->getMontantProgramme()/1000000;
        endforeach;

        return $array;
    }

    public function getEngagementsProgrammesExecutesPartenairesDecroissant(): ?array
    {
        $annees = $this->getEngagementsAnneesDecroissant();
        $partenaires = $this->getPartenairesEngagesOuContribuants();
        $array = [];
        foreach ($annees as $annee):
            foreach ($partenaires as $partenaire):
                $array[$partenaire][$annee]['engage'] = 0;
                $array[$partenaire][$annee]['execute'] = 0;
                $array[$partenaire]['total']['engage'] = 0;
                $array[$partenaire]['total']['execute'] = 0;
            endforeach;
        endforeach;
        $rows = $this->getConventionEngagements();
        foreach ($rows as $row):
            $array[$row->getPartenaire()->getName()][$row->getAnnee()]['engage'] = $row->getMontantProgramme()/1000000;
            if (!isset($array[$row->getPartenaire()->getName()]['total'])) { $array[$row->getPartenaire()->getName()]['total']['engage'] = 0; }
            $array[$row->getPartenaire()->getName()]['total']['engage'] += $row->getMontantProgramme()/1000000;
        endforeach;
        
        $rows2 = $this->getConventionContributions();
        foreach ($rows2 as $row2):
            $array[$row2->getPartenaire()->getName()][$row2->getAnnee()]['execute'] = $row2->getMontant()/1000000;
            if (!isset($array[$row2->getPartenaire()->getName()]['total'])) { $array[$row2->getPartenaire()->getName()]['total']['execute'] = 0; }
            $array[$row2->getPartenaire()->getName()]['total']['execute'] += $row2->getMontant()/1000000;
        endforeach;
        return $array;
    }

    public function getPartenairesEngages(): ?array
    {
        $rows = $this->getConventionEngagements();
        $array = [];
        foreach ($rows as $row):
            $array[$row->getPartenaire()->getId()] = $row->getPartenaire()->getName();
        endforeach;
        /*foreach ($this->getConventionContributions() as $row):
            $array[$row->getPartenaire()->getId()] = $row->getPartenaire()->getName();
        endforeach;*/
        $return= array_unique($array);
        asort($return);
        return $return;
    }

    public function getPartenairesEngagesOuContribuants(): ?array
    {
        $rows = $this->getConventionEngagements();
        $array = [];
        foreach ($rows as $row):
            $array[$row->getPartenaire()->getId()] = $row->getPartenaire()->getName();
        endforeach;
        foreach ($this->getConventionContributions() as $row2):
            $array[$row2->getPartenaire()->getId()] = $row2->getPartenaire()->getName();
        endforeach;/**/
        $return= array_unique($array);
        asort($return);
        return $return;
    }

    public function getEngagementsAnnees(): ?array
    {
        $rows = $this->getConventionEngagements();
        $array = [];
        foreach ($rows as $row):
            $array[$row->getAnnee()] = $row->getAnnee();
        endforeach;
        foreach ($this->getConventionContributions() as $row):
            $array[$row->getAnnee()] = $row->getAnnee();
        endforeach;
        $return= array_unique($array);
        asort($return);
        return $return;
    }

    public function getEngagementsAnneesDecroissant(): ?array
    {
        $return = $this->getEngagementsAnnees();
        arsort($return);
        return $return;
    }

    public function getPartieContractantesIds(): ?array
    {
        $array = [];
        foreach ($this->getPartieContractantes() as $row):
            $array[] = $row->getId();
        endforeach;
        return $array;
    }

    public function getPartieContractantesString(): ?string
    {
        $rows = $this->getPartieContractantes()->toArray();
        return implode(" - ",$rows );
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDateSessionApprobation(): ?\DateTimeInterface
    {
        return (!empty($this->getSessionApprobation())) ? $this->getSessionApprobation()->getDate() : null;
    }
/*
    public function setDateSessionApprobation(?\DateTimeInterface $dateSessionApprobation): self
    {
        $this->dateSessionApprobation = $dateSessionApprobation;

        return $this;
    }*/

    public function getVise(): ?bool
    {
        return $this->vise;
    }

    public function setVise(bool $vise): self
    {
        $this->vise = $vise;

        return $this;
    }

    public function getDateVisa(): ?\DateTimeInterface
    {
        return $this->dateVisa;
    }

    public function setDateVisa(?\DateTimeInterface $dateVisa): self
    {
        $this->dateVisa = $dateVisa;

        return $this;
    }

    public function getSecteur(): ?Secteur
    {
        return $this->secteur;
    }

    public function setSecteur(?Secteur $secteur): self
    {
        $this->secteur = $secteur;

        return $this;
    }

    public function getLocalisation1(): ?string
    {
        return $this->localisation1;
    }

    public function setLocalisation1(string $localisation1): self
    {
        $this->localisation1 = $localisation1;

        return $this;
    }

    public function getObjetConvention(): ?string
    {
        return $this->objetConvention;
    }

    public function setObjetConvention(string $objetConvention): self
    {
        $this->objetConvention = $objetConvention;

        return $this;
    }

    public function getMontantConvention(): ?string
    {
        return $this->montantConvention;
    }

    public function setMontantConvention(?string $montantConvention): self
    {
        $this->montantConvention = $montantConvention;

        return $this;
    }

    public function getMaitreOuvrage(): ?Partenaire
    {
        return $this->maitreOuvrage;
    }

    public function setMaitreOuvrage(?Partenaire $maitreOuvrage): self
    {
        $this->maitreOuvrage = $maitreOuvrage;

        return $this;
    }

    public function getModaliteFinancement(): ?string
    {
        return $this->modaliteFinancement;
    }

    public function setModaliteFinancement(?string $modaliteFinancement): self
    {
        $this->modaliteFinancement = $modaliteFinancement;

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

    public function getDateSignature(): ?\DateTimeInterface
    {
        return $this->dateSignature;
    }

    public function setDateSignature(?\DateTimeInterface $dateSignature): self
    {
        $this->dateSignature = $dateSignature;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateDeliberation(): ?\DateTimeInterface
    {
        return $this->dateDeliberation;
    }

    public function setDateDeliberation(?\DateTimeInterface $dateDeliberation): self
    {
        $this->dateDeliberation = $dateDeliberation;

        return $this;
    }

    public function getTypeConvention(): ?TypeConvention
    {
        return $this->typeConvention;
    }

    public function setTypeConvention(?TypeConvention $typeConvention): self
    {
        $this->typeConvention = $typeConvention;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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

    public function getIsAvenant(): ?bool
    {
        return $this->isAvenant;
    }

    public function setIsAvenant(bool $isAvenant): self
    {
        $this->isAvenant = $isAvenant;

        return $this;
    }

    public function getAvenantA(): ?self
    {
        return $this->avenantA;
    }

    public function setAvenantA(?self $avenantA): self
    {
        $this->avenantA = $avenantA;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getAvenants(): Collection
    {
        return $this->avenants;
    }

    public function addAvenant(self $avenant): self
    {
        if (!$this->avenants->contains($avenant)) {
            $this->avenants[] = $avenant;
            $avenant->setAvenantA($this);
        }

        return $this;
    }

    public function removeAvenant(self $avenant): self
    {
        if ($this->avenants->removeElement($avenant)) {
            // set the owning side to null (unless already changed)
            if ($avenant->getAvenantA() === $this) {
                $avenant->setAvenantA(null);
            }
        }

        return $this;
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
            $conventionDocument->setConvention($this);
        }

        return $this;
    }

    public function removeConventionDocument(ConventionDocument $conventionDocument): self
    {
        if ($this->conventionDocuments->removeElement($conventionDocument)) {
            // set the owning side to null (unless already changed)
            if ($conventionDocument->getConvention() === $this) {
                $conventionDocument->setConvention(null);
            }
        }

        return $this;
    }

    public function getNatureConvention(): ?NatureConvention
    {
        return $this->natureConvention;
    }

    public function setNatureConvention(?NatureConvention $natureConvention): self
    {
        $this->natureConvention = $natureConvention;

        return $this;
    }

    /**
     * @return Collection|Thematique[]
     */
    public function getThematiques(): Collection
    {
        return $this->thematiques;
    }

    public function addThematique(Thematique $thematique): self
    {
        if (!$this->thematiques->contains($thematique)) {
            $this->thematiques[] = $thematique;
        }

        return $this;
    }

    public function removeThematique(Thematique $thematique): self
    {
        $this->thematiques->removeElement($thematique);

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
            $conventionVersement->setConvention($this);
        }

        return $this;
    }

    public function removeConventionVersement(ConventionVersement $conventionVersement): self
    {
        if ($this->conventionVersements->removeElement($conventionVersement)) {
            // set the owning side to null (unless already changed)
            if ($conventionVersement->getConvention() === $this) {
                $conventionVersement->setConvention(null);
            }
        }

        return $this;
    }

    public function getMaitreOuvrage2(): ?Partenaire
    {
        return $this->maitreOuvrage2;
    }

    public function setMaitreOuvrage2(?Partenaire $maitreOuvrage2): self
    {
        $this->maitreOuvrage2 = $maitreOuvrage2;

        return $this;
    }

    public function getNumeroAn(): ?string
    {
        return $this->numeroAn;
    }

    public function setNumeroAn(?string $numeroAn): self
    {
        $this->numeroAn = $numeroAn;

        return $this;
    }

    public function getEntiteSuiviExecution(): ?Entite
    {
        return $this->entiteSuiviExecution;
    }

    public function setEntiteSuiviExecution(?Entite $entiteSuiviExecution): self
    {
        $this->entiteSuiviExecution = $entiteSuiviExecution;

        return $this;
    }

    public function getDateSession(): ?\DateTimeInterface
    {
        return $this->dateSession;
    }

    public function setDateSession(?\DateTimeInterface $dateSession): self
    {
        $this->dateSession = $dateSession;

        return $this;
    }

    public function getIsSignee(): ?bool
    {
        return $this->isSignee;
    }

    public function setIsSignee(?bool $isSignee): self
    {
        $this->isSignee = $isSignee;

        return $this;
    }

    public function getOrganesSuivi(): ?string
    {
        return $this->organesSuivi;
    }

    public function setOrganesSuivi(?string $organesSuivi): self
    {
        $this->organesSuivi = $organesSuivi;

        return $this;
    }

    /**
     * @return Collection|Partenaire[]
     */
    public function getPartieContractantes(): Collection
    {
        return $this->partieContractantes;
    }

    public function addPartieContractante(Partenaire $partieContractante): self
    {
        if (!$this->partieContractantes->contains($partieContractante)) {
            $this->partieContractantes[] = $partieContractante;
        }

        return $this;
    }

    public function removePartieContractante(Partenaire $partieContractante): self
    {
        $this->partieContractantes->removeElement($partieContractante);

        return $this;
    }

    public function getObservation1(): ?string
    {
        return $this->observation1;
    }

    public function setObservation1(?string $observation1): self
    {
        $this->observation1 = $observation1;

        return $this;
    }

    public function getStatutConvention(): ?StatutConvention
    {
        return $this->statutConvention;
    }

    public function setStatutConvention(?StatutConvention $statutConvention): self
    {
        $this->statutConvention = $statutConvention;

        return $this;
    }

    public function getNum2(): ?int
    {
        return $this->num2;
    }

    public function setNum2(?int $num2): self
    {
        $this->num2 = $num2;

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
            $conventionEngagement->setConvention($this);
        }

        return $this;
    }

    public function removeConventionEngagement(ConventionEngagement $conventionEngagement): self
    {
        if ($this->conventionEngagements->removeElement($conventionEngagement)) {
            // set the owning side to null (unless already changed)
            if ($conventionEngagement->getConvention() === $this) {
                $conventionEngagement->setConvention(null);
            }
        }

        return $this;
    }

    public function getConsistance(): ?string
    {
        return $this->consistance;
    }

    public function setConsistance(?string $consistance): self
    {
        $this->consistance = $consistance;

        return $this;
    }

    public function getObjectifsConvention(): ?string
    {
        return $this->objectifsConvention;
    }

    public function setObjectifsConvention(?string $objectifsConvention): self
    {
        $this->objectifsConvention = $objectifsConvention;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getConventionContributions(): Collection
    {
        return $this->conventionContributions;
    }

    public function addConventionContribution(ConventionContribution $conventionContribution): self
    {
        if (!$this->conventionContributions->contains($conventionContribution)) {
            $this->conventionContributions[] = $conventionContribution;
            $conventionContribution->setConvention($this);
        }

        return $this;
    }

    public function removeConventionContribution(ConventionContribution $conventionContribution): self
    {
        if ($this->conventionContributions->removeElement($conventionContribution)) {
            // set the owning side to null (unless already changed)
            if ($conventionContribution->getConvention() === $this) {
                $conventionContribution->setConvention(null);
            }
        }

        return $this;
    }

    public function getNumeroDecision(): ?string
    {
        return $this->numeroDecision;
    }

    public function setNumeroDecision(?string $numeroDecision): self
    {
        $this->numeroDecision = $numeroDecision;

        return $this;
    }

    public function getDateReceptionConvention(): ?\DateTimeInterface
    {
        return $this->dateReceptionConvention;
    }

    public function setDateReceptionConvention(?\DateTimeInterface $dateReceptionConvention): self
    {
        $this->dateReceptionConvention = $dateReceptionConvention;

        return $this;
    }

    public function getNombreExemplaireOriginaux(): ?int
    {
        return $this->nombreExemplaireOriginaux;
    }

    public function setNombreExemplaireOriginaux(?int $nombreExemplaireOriginaux): self
    {
        $this->nombreExemplaireOriginaux = $nombreExemplaireOriginaux;

        return $this;
    }

    public function getDateTransmissionEntiteSuiviExecution(): ?\DateTimeInterface
    {
        return $this->dateTransmissionEntiteSuiviExecution;
    }

    public function setDateTransmissionEntiteSuiviExecution(?\DateTimeInterface $dateTransmissionEntiteSuiviExecution): self
    {
        $this->dateTransmissionEntiteSuiviExecution = $dateTransmissionEntiteSuiviExecution;

        return $this;
    }

    public function getReceptionneePar(): ?string
    {
        return $this->receptionneePar;
    }

    public function setReceptionneePar(?string $receptionneePar): self
    {
        $this->receptionneePar = $receptionneePar;

        return $this;
    }

    public function getDateTransmissionPourVisa(): ?\DateTimeInterface
    {
        return $this->dateTransmissionPourVisa;
    }

    public function setDateTransmissionPourVisa(?\DateTimeInterface $dateTransmissionPourVisa): self
    {
        $this->dateTransmissionPourVisa = $dateTransmissionPourVisa;

        return $this;
    }

    public function getObservationsVisa(): ?string
    {
        return $this->observationsVisa;
    }

    public function setObservationsVisa(?string $observationsVisa): self
    {
        $this->observationsVisa = $observationsVisa;

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
            $conventionSignature->setConvention($this);
        }

        return $this;
    }

    public function removeConventionSignature(ConventionSignature $conventionSignature): self
    {
        if ($this->conventionSignatures->removeElement($conventionSignature)) {
            // set the owning side to null (unless already changed)
            if ($conventionSignature->getConvention() === $this) {
                $conventionSignature->setConvention(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Localisation[]
     */
    public function getLocalisations(): Collection
    {
        return $this->localisations;
    }

    public function addLocalisation(Localisation $localisation): self
    {
        if (!$this->localisations->contains($localisation)) {
            $this->localisations[] = $localisation;
        }

        return $this;
    }

    public function removeLocalisation(Localisation $localisation): self
    {
        $this->localisations->removeElement($localisation);

        return $this;
    }

    /**
     * @return Collection|ConventionStade[]
     */
    public function getConventionStades(): Collection
    {
        return $this->conventionStades;
    }

    public function addConventionStade(ConventionStade $conventionStade): self
    {
        if (!$this->conventionStades->contains($conventionStade)) {
            $this->conventionStades[] = $conventionStade;
            $conventionStade->setConvention($this);
        }

        return $this;
    }

    public function removeConventionStade(ConventionStade $conventionStade): self
    {
        if ($this->conventionStades->removeElement($conventionStade)) {
            // set the owning side to null (unless already changed)
            if ($conventionStade->getConvention() === $this) {
                $conventionStade->setConvention(null);
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
            $conventionSuiviExecution->setConvention($this);
        }

        return $this;
    }

    public function removeConventionSuiviExecution(ConventionSuiviExecution $conventionSuiviExecution): self
    {
        if ($this->conventionSuiviExecutions->removeElement($conventionSuiviExecution)) {
            // set the owning side to null (unless already changed)
            if ($conventionSuiviExecution->getConvention() === $this) {
                $conventionSuiviExecution->setConvention(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DocumentPlanification[]
     */
    public function getDocumentPlanifications(): Collection
    {
        return $this->documentPlanifications;
    }

    public function addDocumentPlanification(DocumentPlanification $documentPlanification): self
    {
        if (!$this->documentPlanifications->contains($documentPlanification)) {
            $this->documentPlanifications[] = $documentPlanification;
        }

        return $this;
    }

    public function removeDocumentPlanification(DocumentPlanification $documentPlanification): self
    {
        $this->documentPlanifications->removeElement($documentPlanification);

        return $this;
    }

    public function getDomaineCompetence(): ?DomaineCompetence
    {
        return $this->domaineCompetence;
    }

    public function setDomaineCompetence(?DomaineCompetence $domaineCompetence): self
    {
        $this->domaineCompetence = $domaineCompetence;

        return $this;
    }

    public function getSessionApprobation(): ?Session
    {
        return $this->sessionApprobation;
    }

    public function setSessionApprobation(?Session $sessionApprobation): self
    {
        $this->sessionApprobation = $sessionApprobation;

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

    public function getAnneeEngagement1(): ?int
    {
        return $this->anneeEngagement1;
    }

    public function setAnneeEngagement1(?int $anneeEngagement1): self
    {
        $this->anneeEngagement1 = $anneeEngagement1;

        return $this;
    }

    public function getAnneeEngagement2(): ?int
    {
        return $this->anneeEngagement2;
    }

    public function setAnneeEngagement2(?int $anneeEngagement2): self
    {
        $this->anneeEngagement2 = $anneeEngagement2;

        return $this;
    }

    public function getArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @return Collection|Projet[]
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): self
    {
        if (!$this->projets->contains($projet)) {
            $this->projets[] = $projet;
            $projet->setConvention($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getConvention() === $this) {
                $projet->setConvention(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConventionEngagementRegion[]
     */
    public function getConventionEngagementRegions(): Collection
    {
        return $this->conventionEngagementRegions;
    }

    public function addConventionEngagementRegion(ConventionEngagementRegion $conventionEngagementRegion): self
    {
        if (!$this->conventionEngagementRegions->contains($conventionEngagementRegion)) {
            $this->conventionEngagementRegions[] = $conventionEngagementRegion;
            $conventionEngagementRegion->setConvention($this);
        }

        return $this;
    }

    public function removeConventionEngagementRegion(ConventionEngagementRegion $conventionEngagementRegion): self
    {
        if ($this->conventionEngagementRegions->removeElement($conventionEngagementRegion)) {
            // set the owning side to null (unless already changed)
            if ($conventionEngagementRegion->getConvention() === $this) {
                $conventionEngagementRegion->setConvention(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Session[]
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->addConvention($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->sessions->removeElement($session)) {
            $session->removeConvention($this);
        }

        return $this;
    }

    /**
     * @return Collection|ConventionRepartitionLocalisation[]
     */
    public function getConventionRepartitionLocalisations(): Collection
    {
        return $this->conventionRepartitionLocalisations;
    }

    public function addConventionRepartitionLocalisation(ConventionRepartitionLocalisation $conventionRepartitionLocalisation): self
    {
        if (!$this->conventionRepartitionLocalisations->contains($conventionRepartitionLocalisation)) {
            $this->conventionRepartitionLocalisations[] = $conventionRepartitionLocalisation;
            $conventionRepartitionLocalisation->setConvention($this);
        }

        return $this;
    }

    public function removeConventionRepartitionLocalisation(ConventionRepartitionLocalisation $conventionRepartitionLocalisation): self
    {
        if ($this->conventionRepartitionLocalisations->removeElement($conventionRepartitionLocalisation)) {
            // set the owning side to null (unless already changed)
            if ($conventionRepartitionLocalisation->getConvention() === $this) {
                $conventionRepartitionLocalisation->setConvention(null);
            }
        }

        return $this;
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

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(?string $emplacement): self
    {
        $this->emplacement = $emplacement;

        return $this;
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
            $conventionLecture->setConvention($this);
        }

        return $this;
    }

    public function removeConventionLecture(ConventionLecture $conventionLecture): self
    {
        if ($this->conventionLectures->removeElement($conventionLecture)) {
            // set the owning side to null (unless already changed)
            if ($conventionLecture->getConvention() === $this) {
                $conventionLecture->setConvention(null);
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
            $conventionSuivi->setConvention($this);
        }

        return $this;
    }

    public function removeConventionSuivi(ConventionSuivi $conventionSuivi): self
    {
        if ($this->conventionSuivis->removeElement($conventionSuivi)) {
            // set the owning side to null (unless already changed)
            if ($conventionSuivi->getConvention() === $this) {
                $conventionSuivi->setConvention(null);
            }
        }

        return $this;
    }

    public function getStadeElaboration(): ?StadeElaboration
    {
        return $this->stadeElaboration;
    }

    public function setStadeElaboration(?StadeElaboration $stadeElaboration): self
    {
        $this->stadeElaboration = $stadeElaboration;

        return $this;
    }

    public function getStadeExecution(): ?StadeExecution
    {
        return $this->stadeExecution;
    }

    public function setStadeExecution(?StadeExecution $stadeExecution): self
    {
        $this->stadeExecution = $stadeExecution;

        return $this;
    }



}

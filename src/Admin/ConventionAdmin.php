<?php

declare(strict_types=1);

namespace App\Admin;

use App\Controller\DefaultController;
use App\Entity\Convention;
use App\Entity\ConventionContribution;
use App\Entity\ConventionEngagement;
use App\Entity\ConventionSignature;
use App\Entity\DocumentPlanification;
use App\Entity\Localisation;
use App\Entity\Session;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Type\DatePickerType;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;


final class ConventionAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    public function __construct($code, $class, $baseControllerName, DefaultController $defaultController) {
        parent::__construct($code, $class, $baseControllerName);
        $this->defaultController = $defaultController;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('view', $this->getRouterIdParameter().'/view')
            ->add('fiche', $this->getRouterIdParameter().'/fiche')
            ->add('suivre', $this->getRouterIdParameter().'/suivre')
            ->add('annuler_suivi', $this->getRouterIdParameter().'/annuler_suivi')
        ;
        //$collection->remove('export');
    }

/*
    public function configure()
    {
        // This disables softdelete for this entity.
        $filters = $this->getModelManager()->getEntityManager($this->getClass())->getFilters();
        if (array_key_exists('softdeleteable', $filters->getEnabledFilters())) {
            $filters->disable('softdeleteable');
        }
    }
*/
    protected function configureBatchActions($actions): array
    {
        unset($actions['delete']);
        if ($this->hasRoute('edit') && $this->hasAccess('edit')) {
            $actions['archiver'] = ['ask_confirmation' => true];
            $actions['desarchiver'] = ['ask_confirmation' => true];
        }
        return $actions;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('numero')
            ->add('objetConvention', null, ['show_filter' => true])
            ->add('montantConvention')/**/
            ->add('partieContractantes', 'doctrine_orm_callback', ['show_filter' => true,
                'callback' => function($queryBuilder, $alias, $field, $value) {
                    if (empty($value['value'])) {
                        return;
                    }
                    $andwhere = $params = []; $i = 0;
                    foreach ($value['value'] as $item)  {
                        $andwhere[] = ':item'.$i.' MEMBER OF '.$alias.'.'.$field;
                        $params['item'.$i] = $item;
                        $i++;
                    }
                    $andwhere2 = implode(' and ', $andwhere);
                    $queryBuilder->andWhere($andwhere2)->setParameters($params);

                    //dd($value);
                    //dd($queryBuilder->getQuery()->getSql());
                    //dd($queryBuilder->getDql());
                    return true;
                }],
                EntityType::class,
                [
                    'class' => 'App\Entity\Partenaire',
                    'multiple' => true
                ]
                )
            ->add('dateSignature', DateFilter::class, [],DatePickerType::class,
                    [   'datepicker_use_button' => false,
                        'dp_use_current'    => false,
                        'format'            => 'dd/MM/yyyy',
                    ])
            ->add('domaineCompetence')
            ->add('sessionApprobation',null,[],null,[
                'multiple'=> true
            ])
            ->add('vise')
            ->add('dateVisa', DateFilter::class, [],DatePickerType::class,
                    [   'datepicker_use_button' => false,
                        'dp_use_current'    => false,
                        'format'            => 'dd/MM/yyyy',
                        //'dp_days_of_week_disabled'  => [0, 6],
                    ])
            ->add('localisations')
            ->add('modaliteFinancement')
            //->add('active')
            ->add('archived', null, ['show_filter' => false])
            ->add('isAvenant')
            ->add('numeroAn')
            ->add('isSignee')
            ->add('organesSuivi')
            ->add('consistance')
            ->add('objectifsConvention')
            ->add('typeConvention',null,[],null,[
                'multiple'=> true
            ])
            ->add('natureConvention',null,[],null,[
                'multiple'=> true
            ])
            ->add('secteur',null,[],null,[
                'multiple'=> true
            ])
            ->add('maitreOuvrage',null,[],null,[
                'multiple'=> true
            ])
            ->add('stade',null,[],null,[
                'multiple'=> true
            ])
            ->add('stadeElaboration',null,[],null,[
                'multiple'=> true
            ])
            ->add('stadeExecution',null,[],null,[
                'multiple'=> true
            ])
            ->add('thematiques')
            ->add('entiteSuiviExecution',null,[],null,[
                'multiple'=> true
            ])
            ->add('statutConvention',null,[],null,[
                'multiple'=> true
            ])
            /**/


            /*//->add('observations')
            ->add('dateDeliberation', DateFilter::class, [],DatePickerType::class,
                                [   'datepicker_use_button' => false,
                                    'dp_use_current'    => false,
                                    'format'            => 'dd/MM/yyyy',
                                    //'dp_days_of_week_disabled'  => [0, 6],
                                ])
                        ->add('dateSessionApprobation', DateFilter::class, [],DatePickerType::class,
                                [   'datepicker_use_button' => false,
                                    'dp_use_current'    => false,
                                    'format'            => 'dd/MM/yyyy',
                                    //'dp_days_of_week_disabled'  => [0, 6],
                                ])*/
            /*->add('dateSession', DateFilter::class, [],DatePickerType::class,
                    [   'datepicker_use_button' => false,
                        'dp_use_current'    => false,
                        'format'            => 'dd/MM/yyyy',
                        //'dp_days_of_week_disabled'  => [0, 6],
                    ])*/
        ;
    }


    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $authorization_checker = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        if (!($authorization_checker->isGranted('ROLE_ADMIN')) and !($authorization_checker->isGranted('ROLE_CONV_SUIVI_FINANCIER'))) {
            //$user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
            $entites = $this->defaultController->entiteaconsulters(true);
            $query->join('o.entiteConsultantes','e','WITH','e.id IN (:es) ')->setParameter('es', $entites);
        }/**/
        //dd($entites);
        //dd($query->execute());
        return $query;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('numero', null, [
                'editable' => true,
                'header_style' => 'width: 5%; text-align: center',
                'row_align' => 'center'
            ])
            //->add('partieContractantes')
            //->add('numeroAn', null, ['editable' => true])
            ->add('objetConvention', null, ['editable' => true])
            ->add('entiteConsultantes', null, ['editable' => true])
            ->add('montantConvention', null, ['template' => 'bundles/SonataAdminBundle/fields/list_montant.html.twig'])
            ->add('montantEngagementGlobalRegion', null, ['template' => 'bundles/SonataAdminBundle/fields/list_montant.html.twig'])
            ->add('dateSessionApprobation', null, ['editable' => true])
            ->add('numeroDecision', null, [
                'editable' => true,
                'header_style' => 'text-align: center',
                'row_align' => 'center'
            ])
            ->add('secteur', null, [
                'editable' => true,
                'header_style' => 'text-align: center',
                'row_align' => 'center'
            ])
            ->add('maitreOuvrage', null, ['editable' => true])
            ->add('estSigneeParPartenaires', null, [
                'template' => 'bundles/SonataAdminBundle/fields/list_boolean.html.twig',
                'header_style' => 'text-align: center',
                'row_align' => 'center'
            ])
            //->add('delaisConsommesPourSignaturePartenaires', null, ['template' => 'bundles/SonataAdminBundle/fields/list_dc2type.html.twig'])
            //->add('delaiConsommePourVisa', null, ['template' => 'bundles/SonataAdminBundle/fields/list_jours2mois.html.twig'])
            //->add('delaiAttenteVisa', null, ['template' => 'bundles/SonataAdminBundle/fields/list_jours2mois.html.twig'])
            //->add('archived', null, ['editable' => true])
            ->add('stade', null, [
                'editable' => true,
                'header_style' => 'text-align: center',
                'row_align' => 'center'
            ])
            ->add('stadeElaboration', null, [
                'editable' => true,
                'header_style' => 'text-align: center',
                'row_align' => 'center'
            ])
            ->add('stadeExecution', null, [
                'editable' => true,
                'header_style' => 'text-align: center',
                'row_align' => 'center'
            ])
            ->add('nomFichier', null, [
                'header_class' =>'col-md-1',
                'label'=> 'Convention',
                'label_icon' => 'fa fa-file-pdf-o',
                'template' => 'bundles/SonataAdminBundle/fields/list_vich_fichier_pdf.html.twig'
            ])
//            ->add('entiteSuiviExecution', null, ['editable' => true])
//            ->add('statutConvention', null, ['editable' => true])
            ->add('_action', null, [
                'actions' => [
                    'view' => ['template' => 'CRUD/list__action_view.html.twig'],
                    'fiche' => ['template' => 'CRUD/list__action_fiche_docx.html.twig'],
                    //'show' => [],
                    'edit' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $authorization_checker = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        if (!$authorization_checker->isGranted('ROLE_CONV_ELABORATION')) {
            $elabor = false; } else { $elabor = true; }
        if (!$authorization_checker->isGranted('ROLE_CONV_SUIVI_FINANCIER')) {
            $suiviFin = false; } else { $suiviFin = true; }
        if (!$authorization_checker->isGranted('ROLE_CONV_SUIVI_EXECUTION')) {
            $suiviExe = false; } else { $suiviExe = true; }
        if (!$authorization_checker->isGranted('ROLE_CONV_CONSULTATION')) {
            $consult = false; } else { $consult = true; }

    if ($elabor) {
        $formMapper
        ->tab('Renseignements')
            ->with('Identification', ['class' => 'col-md-6'])
                ->add('numero', null, ['required'=> false])
                ->add('numeroAn', null, ['required'=> false])
                ->add('objetConvention', null, ['required'=> false])
            //->add('consistance', null, ['required'=> false])
                ->add('consistance', SimpleFormatterType::class, [
                    'required' => false,
                    'format' => 'richhtml',
                    'attr' => ['class' => 'ckeditor']
                ])/**/
                ->add('partieContractantes', null, ['required'=> false])
                ->add('montantConvention', null, ['required'=> false])
                ->add('localisations', null, ['required'=> false])
                ->add('typeConvention', null, ['required'=> false])
                ->add('natureConvention', null, ['required'=> false])
                ->add('secteur', null, ['required'=> false])
                ->add('documentPlanifications', ModelType::class, [
                    'class' => DocumentPlanification::class,
                    'multiple' => true,
                    'required'=> false
                ])
                ->add('domaineCompetence', null, ['required'=> false])
                ->add('duree', null, ['required'=> false])
                ->add('sessionApprobation', ModelType::class, [
                    'class' => Session::class,
                    //'btn_add' => true,
                    'multiple' => false,
                    'required'=> false
                ])
                ->add('numeroDecision', null, ['required'=> false])
                ->add('dateReceptionConvention', DatePickerType::class, [
                    'required'=> false, 'dp_side_by_side' => true, 'datepicker_use_button' => false,'dp_use_current'    => false,
                ])
                ->add('nombreExemplaireOriginaux', null, ['required'=> false])
            ->end()
            ->with('conventionRepartitionLocalisations', ['class' => 'col-md-6'])
                ->add('conventionRepartitionLocalisations', CollectionType::class, ['by_reference' => false,'label'=>false,], ['edit' => 'inline','inline' => 'table',])
            ->end()

            ->with('Document Signe', ['class' => 'col-md-6'])
                ->add('fichier', VichFileType::class, [
                    'label' => false,
                    'required'=> false,
                    'data_class' => null,
                ])
            ->end()
            ->with('Avenant', ['class' => 'col-md-6'])
                ->add('isAvenant', null, ['required'=> false])
                ->add('avenantA', null, ['required'=> false])
            ->end()
            ->with('Visa', ['class' => 'col-md-6'])
                ->add('vise', null, ['required'=> false])
                ->add('dateTransmissionPourVisa', DatePickerType::class, [
                    'required'=> false, 'dp_side_by_side' => true, 'datepicker_use_button' => false,'dp_use_current'    => false,
                ])
                ->add('dateVisa', DatePickerType::class, [
                    'required'=> false, 'dp_side_by_side' => true, 'datepicker_use_button' => false,'dp_use_current'    => false,
                ])
                ->add('observationsVisa', null, ['required'=> false])

            ->end()
            ->with('Suivi', ['class' => 'col-md-6'])
                ->add('entiteConsultantes', null, ['required'=> false])
                ->add('maitreOuvrage', null, ['required'=> false])
                ->add('entiteSuiviExecution', null, ['required'=> false])
                ->add('dateTransmissionEntiteSuiviExecution', DatePickerType::class, [
                    'required'=> false, 'dp_side_by_side' => true, 'datepicker_use_button' => false,'dp_use_current'    => false,
                ])
                ->add('receptionneePar', null, ['required'=> false])
                //->add('organesSuivi', null, ['required'=> false])
                ->add('organeGouvernances', null, ['required'=> false])
            ->end()
            ->with('Autres infos', ['class' => 'col-md-6'])
                ->add('emplacement', null, ['required'=> false])
            //->add('objectifsConvention', null, ['required'=> false])
                ->add('objectifsConvention', SimpleFormatterType::class, [
                    'required' => false,
                    'format' => 'richhtml',
                    'attr' => ['class' => 'ckeditor']
                ])/**/
                ->add('thematiques', null, ['required'=> false])
            //->add('modaliteFinancement', null, ['required'=> false])
                ->add('modaliteFinancement', SimpleFormatterType::class, [
                    'required' => false,
                    'format' => 'richhtml',
                    'attr' => ['class' => 'ckeditor']
                ])/**/
                //->add('observations', null, ['required'=> false])
                ->add('observations', SimpleFormatterType::class, [
                    'label' => 'Observation2',
                    'required' => false,
                    'format' => 'richhtml',
                    'attr' => ['class' => 'ckeditor']
                ])/**/
            ->end()
            ->with('Statut', ['class' => 'col-md-6'])
                ->add('stade', null, ['required'=> false, 'sortable' => 'stade.name',])
                ->add('stadeElaboration', null, ['required'=> false])
                ->add('stadeExecution', null, ['required'=> false])
                ->add('statutConvention', null, ['required'=> false])
                //->add('observation1', null, ['required'=> false])
                ->add('observation1', SimpleFormatterType::class, [
                    'required' => false,
                    'format' => 'richhtml',
                    'attr' => ['class' => 'ckeditor']
                ])/**/
            ->end()
        ->end()

    ->tab('Signature')
        ->with('Signature', ['class' => 'col-md-6'])
            ->add('isSignee', null, ['required'=> false])
            ->add('dateSignature', DatePickerType::class, [
                'required'=> false, 'dp_side_by_side' => true, 'datepicker_use_button' => false,'dp_use_current'    => false,
            ])
        ->end()
        ->with('conventionSignatures', ['class' => 'col-md-12'])
            ->add('conventionSignatures', CollectionType::class, ['by_reference' => false,'label'=>false,], ['edit' => 'inline','inline' => 'table',])
        ->end()
    ->end()
    ->tab('conventionDocuments')
        ->with('conventionDocuments', ['class' => 'col-md-12'])
            ->add('conventionDocuments', CollectionType::class, ['by_reference' => false,'label'=>false,], ['edit' => 'inline','inline' => 'table',])
        ->end()
    ->end()
    ->tab('conventionEngagements')
        ->with('conventionEngagements', ['class' => 'col-md-12'])
            ->add('conventionEngagements', CollectionType::class, ['by_reference' => false,'label'=>false,], ['edit' => 'inline','inline' => 'table',])
        ->end()
        ->with('Annees', ['class' => 'col-md-4'])
            ->add('anneeEngagement1', null, ['required'=> false])
            ->add('anneeEngagement2', null, ['required'=> false])
        ->end()
    ->end()
    ->tab('conventionStades')
        ->with('conventionStades', ['class' => 'col-md-12'])
            ->add('conventionStades', CollectionType::class, ['by_reference' => false,'label'=>false,], ['edit' => 'inline','inline' => 'table',])
        ->end()
    ->end()
        ;
    }
    if ($suiviFin) {
        $formMapper
            ->tab('conventionContributions')
                ->with('conventionContributions', ['class' => 'col-md-12'])
                    ->add('conventionContributions', CollectionType::class, ['by_reference' => false,'label'=>false,], ['edit' => 'inline','inline' => 'table',])
                ->end()
            ->end()
            ->tab('conventionEngagementRegions')
                ->with('conventionEngagementRegions', ['class' => 'col-md-12'])
                    ->add('conventionEngagementRegions', CollectionType::class, ['by_reference' => false,'label'=>false,], ['edit' => 'inline','inline' => 'table',])
                ->end()
            ->end()
            ;
    }

    if ($suiviExe) {
        $formMapper
        ->tab('conventionSuiviExecutions')
            ->with('conventionSuiviExecutions', ['class' => 'col-md-12'])
                ->add('conventionSuiviExecutions', CollectionType::class, ['by_reference' => false,'label'=>false,], ['edit' => 'inline','inline' => 'table',])
            ->end()
        ->end()
        ;
    }

    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
        ->add('numero')
        ->add('numeroAn')
        ->add('objetConvention')
        ->add('montantConvention', null, ['template' => 'bundles/SonataAdminBundle/fields/show_montant.html.twig'])
        ->add('dateSignature')
        ->add('duree')
        //->add('dateDeliberation')
        //->add('dateSessionApprobation')
        ->add('vise')
        ->add('dateVisa')
        ->add('localisation')
        ->add('modaliteFinancement')
        ->add('observations', null, [], ['label' => 'Observation2'])
        //->add('dateSession')
        ->add('isSignee')
        ->add('consistance')
        ->add('objectifsConvention')
        ->add('typeConvention')
        ->add('natureConvention')
        ->add('secteur')
        ->add('maitreOuvrage')
        ->add('isAvenant')
        ->add('avenantA')
        //->add('avenants')
        //->add('conventionDocuments')
        ->add('thematiques')
        //->add('conventionVersements')
        ->add('maitreOuvrage2')
        ->add('entiteSuiviExecution')
        ->add('partieContractantes')
        ->add('organesSuivi')
        ->add('statutConvention')
        //->add('conventionEngagements')
        ->add('stade')
        ->add('stadeElaboration')
        ->add('stadeExecution')
        //->add('active')
        ;
    }


    /*protected function configureExportFields(): array
    {
        return [
            'id', 'numeroAn', 'numero', 'objetConvention', 'secteur.name'
        ];
    }*/

    public function postPersist($convention)
    {
        $this->majLocalisation($convention);
        $this->initialiserConventionSignatures($convention);
        $this->initialiserConventionEngagements($convention);
        $this->initialiserConventionContributions($convention);
    }

    public function postUpdate($convention)
    {
        $this->majLocalisation($convention);
        $this->initialiserConventionSignatures($convention);
        $this->initialiserConventionEngagements($convention);
        $this->initialiserConventionContributions($convention);
    }

    public function initialiserConventionSignatures($convention)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');
        /** @var Convention $convention */
        if ($convention->getConventionSignatures()->isEmpty()):
            foreach ($convention->getPartieContractantes() as $partenaire):
                $row = new ConventionSignature();
                $row->setConvention($convention);
                $row->setPartenaire($partenaire);
                $row->setIsSignee(false);
                $em->persist($row);
            endforeach;
            $em->flush();
        endif;
    }

    public function initialiserConventionEngagements($convention)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');
        /** @var Convention $convention */
        if (($convention->getConventionEngagements()->isEmpty()) and (!empty($convention->getAnneeEngagement1()))  and (!empty($convention->getAnneeEngagement2()))):
            for ($an = $convention->getAnneeEngagement1(); $an <= $convention->getAnneeEngagement2(); $an++) {
                foreach ($convention->getPartieContractantes() as $partenaire):
                    $row = new ConventionEngagement();
                    $row->setConvention($convention);
                    $row->setPartenaire($partenaire);
                    $row->setAnnee($an);
                    $row->setMontantProgramme("0");
                    $em->persist($row);
                endforeach;
            }
            $em->flush();
        endif;
    }

    public function initialiserConventionContributions($convention)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');
        /** @var Convention $convention */
        if (($convention->getConventionContributions()->isEmpty()) and (!empty($convention->getAnneeEngagement1()))  and (!empty($convention->getAnneeEngagement2()))):
            for ($an = $convention->getAnneeEngagement1(); $an <= $convention->getAnneeEngagement2(); $an++) {
                foreach ($convention->getPartieContractantes() as $partenaire):
                    $row = new ConventionContribution();
                    $row->setConvention($convention);
                    $row->setPartenaire($partenaire);
                    $row->setAnnee($an);
                    $row->setMontant("0");
                    $em->persist($row);
                endforeach;
            }
            $em->flush();
        endif;
    }

    public function majLocalisation($convention)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');
        /** @var Convention $convention */
        $localisations = $convention->getLocalisations();
        if (count($localisations) == 0) {
            $localisation = null;
        } elseif (count($localisations) == 1) {
            $localisation = $localisations->first();
        }else {
            $localisation = $em->getRepository(Localisation::class)->find(9);
        }
       $convention->setLocalisation($localisation);
       $em->persist($convention);$em->flush();
    }

}
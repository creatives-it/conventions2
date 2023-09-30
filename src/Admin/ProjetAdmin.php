<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Axe;
use App\Entity\DocumentPlanification;
use App\Entity\Projet;
use App\Entity\ProjetNature;
use App\Entity\ProjetPhase;
use App\Form\GMapAddressType;
use Ivory\GoogleMapBundle\Form\Type\PlaceAutocompleteType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;

//use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Vich\UploaderBundle\Form\Type\VichImageType;
//use Vich\UploaderBundle\Form\Type\VichFileType;
use Sonata\Form\Type\DatePickerType;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;

//use Sonata\AdminBundle\Form\Type\ModelType;
//use Sonata\Form\Type\CollectionType;
//use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;

final class ProjetAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('view', $this->getRouterIdParameter().'/view')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('numero')
            ->add('objet')
            ->add('description')
            ->add('montantInvestissement')
            ->add('contributionRegion')
            ->add('contributionPartenaire')
            ->add('lieu')
            ->add('duree')
            ->add('dateDemarrage', DateFilter::class, [],DatePickerType::class,
                    [   'datepicker_use_button' => false,
                        'dp_use_current'    => false,
                        'format'            => 'dd/MM/yyyy',
                        //'dp_days_of_week_disabled'  => [0, 6],
                    ])
            ->add('dateAchevement', DateFilter::class, [],DatePickerType::class,
                    [   'datepicker_use_button' => false,
                        'dp_use_current'    => false,
                        'format'            => 'dd/MM/yyyy',
                        //'dp_days_of_week_disabled'  => [0, 6],
                    ])
            ->add('tauxAvancement')
            ->add('anneeProgrammation')
            ->add('isPdr')
            ->add('convention')
            ->add('nature')
            ->add('phase')
            ->add('axe')
            ->add('observation')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('numero', null, ['editable' => true])
            ->add('objet', null, ['editable' => true])
            ->add('montantInvestissement', null, ['template' => 'bundles/SonataAdminBundle/fields/list_montant.html.twig'])
            ->add('contributionRegion', null, ['editable' => true])
            ->add('dateDemarrage', null, ['editable' => true])
            ->add('dateAchevement', null, ['editable' => true])
            ->add('tauxAvancement', null, ['editable' => true])
            ->add('isPdr', null, ['editable' => true])
            ->add('convention', null, ['editable' => true])
            ->add('phase', null, ['editable' => true])
            ->add('_action', null, [
                'actions' => [
                    'view' => ['template' => 'CRUD/list__action_view.html.twig'],
                    'edit' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
        //->tab('Renseignements')
        ->with('Identification', ['class' => 'col-md-6'])
            ->add('numero', null, ['required'=> false])
            ->add('objet', null, ['required'=> true])
            ->add('description', SimpleFormatterType::class, [
                        'required' => false,
                        'format' => 'richhtml',
                        'attr' => ['class' => 'ckeditor']
                        ])
            ->add('address', PlaceAutocompleteType::class, [
            ])
            ->add('montantInvestissement', null, ['required'=> false])
            ->add('contributionRegion', null, ['required'=> false])
            ->add('contributionPartenaire', null, ['required'=> false])
            ->add('lieu', null, ['required'=> false])
            ->add('duree', null, ['required'=> false])
        ->end()
        ->with('Autres infos', ['class' => 'col-md-6'])
            ->add('convention', null, ['required'=> false])
            ->add('nature', ModelType::class, [
                'class' => ProjetNature::class,
                'multiple' => false,
                'required'=> false
            ])
            ->add('phase', ModelType::class, [
                'class' => ProjetPhase::class,
                'multiple' => false,
                'required'=> false
            ])
            ->add('axe', ModelType::class, [
                'class' => Axe::class,
                'multiple' => false,
                'required'=> false
            ])
            ->add('dateDemarrage', DatePickerType::class, [
                    'required'=> false, 'dp_side_by_side' => true, 'datepicker_use_button' => false,
                    'dp_use_current'    => false,
                ])
            ->add('dateAchevement', DatePickerType::class, [
                    'required'=> false, 'dp_side_by_side' => true, 'datepicker_use_button' => false,
                    'dp_use_current'    => false,
                ])
            ->add('tauxAvancement', null, ['required'=> false])
            ->add('anneeProgrammation', null, ['required'=> false])
            ->add('isPdr', null, ['required'=> false])
            ->add('observation', SimpleFormatterType::class, [
                'required' => false,
                'format' => 'richhtml',
                'attr' => ['class' => 'ckeditor']
            ])
        ->end()
        //->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
        ->add('numero')
        ->add('objet')
        ->add('description', 'html', ['strip' => true])
        ->add('montantInvestissement', null, ['template' => 'bundles/SonataAdminBundle/fields/show_montant.html.twig'])
        ->add('contributionRegion')
        ->add('contributionPartenaire')
        ->add('lieu')
        ->add('duree')
        ->add('convention')
        ->add('nature')
        ->add('phase')
        ->add('axe')
        ->add('dateDemarrage')
        ->add('dateAchevement')
        ->add('tauxAvancement')
        ->add('anneeProgrammation')
        ->add('isPdr')
        ->add('observation', 'html', ['strip' => true])
        ;
    }
}
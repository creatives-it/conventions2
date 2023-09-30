<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\Form\Type\DatePickerType;


final class ConventionVersementAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('montant')
            ->add('intitule')
            ->add('dateVersement', DateFilter::class, [],DatePickerType::class,
                    [   'datepicker_use_button' => false,
                        'dp_use_current'    => false,
                        'format'            => 'dd/MM/yyyy',
                        //'dp_days_of_week_disabled'  => [0, 6],
                    ])
            ->add('partenaire')
            ->add('convention')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('montant', null, ['template' => 'bundles/SonataAdminBundle/fields/list_montant.html.twig'])
            ->add('intitule', null, ['editable' => true])
            ->add('dateVersement', null, ['editable' => true])
            ->add('partenaire', null, ['editable' => true])
            ->add('convention', null, ['editable' => true])
            ->add('_action', null, [
                'actions' => [
                    //'show' => [],
                    'edit' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
        //->tab('Renseignements')
            ->with('Identification', ['class' => 'col-md-6'])
            ->add('partenaire', null, ['required'=> true])
            ->add('montant', null, ['required'=> true])
            ->add('intitule', null, ['required'=> false])
            ->add('dateVersement', DatePickerType::class, [
                            'required'=> false, 'dp_side_by_side' => true, 'datepicker_use_button' => false,'dp_use_current'    => false,
                        ])
            //->add('convention', null, ['required'=> false])
            ->end()
        //->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
            ->add('montant', null, ['template' => 'bundles/SonataAdminBundle/fields/show_montant.html.twig'])
            ->add('intitule')
            ->add('dateVersement')
            ->add('partenaire')
            ->add('convention')
        ;
    }
}
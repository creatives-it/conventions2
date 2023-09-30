<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\Form\Type\DatePickerType;

//use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Vich\UploaderBundle\Form\Type\VichImageType;
//use Vich\UploaderBundle\Form\Type\VichFileType;
//use Sonata\Form\Type\DatePickerType;
//use Sonata\AdminBundle\Form\Type\ModelType;
//use Sonata\Form\Type\CollectionType;
//use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;

final class ConventionSuiviExecutionAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('date', DateFilter::class, [],DatePickerType::class,
                    [   'datepicker_use_button' => false,
                        'dp_use_current'    => false,
                        'format'            => 'dd/MM/yyyy',
                        //'dp_days_of_week_disabled'  => [0, 6],
                    ])
            ->add('decisions')
            ->add('tauxAvancement')
            ->add('observation')
            ->add('convention')
            ->add('stade')
            ->add('organeGouvernances')
            ->add('medias')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('date', null, ['editable' => true])
            ->add('tauxAvancement', null, ['editable' => true])
            ->add('convention', null, ['editable' => true])
            ->add('stade', null, ['editable' => true])
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
            ->add('date', DatePickerType::class, [
                        'required'=> true, 'dp_side_by_side' => true, 'datepicker_use_button' => false,'dp_use_current'    => false,
                    ])
            ->add('organeGouvernances', null, ['required'=> false])
            ->add('decisions', null, ['required'=> false])
            ->add('tauxAvancement', null, ['required'=> false])
            //->add('convention', null, ['required'=> false])
            ->add('stade', null, ['required'=> false])
            /*->add('medias', null, [
                'label'=> 'Pieces Jointes',
                'required'=> false])*/
            ->add('observation', null, ['required'=> false])
        ->end()
        //->end()
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
            ->add('date')
            ->add('decisions')
            ->add('tauxAvancement')
            ->add('observation')
            ->add('convention')
            ->add('stade')
            ->add('organeGouvernances')
            ->add('medias')
        ;
    }
}
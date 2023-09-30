<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

//use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Vich\UploaderBundle\Form\Type\VichImageType;
//use Vich\UploaderBundle\Form\Type\VichFileType;
//use Sonata\Form\Type\DatePickerType;
//use Sonata\AdminBundle\Form\Type\ModelType;
//use Sonata\Form\Type\CollectionType;
//use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;

final class MandatAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('name')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('name', null, ['editable' => true])
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
                ->add('name', null, ['required'=> false])
            ->end()
        //->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
            ->add('name')
            ->add('sessions')
        ;
    }
}
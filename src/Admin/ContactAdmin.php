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

final class ContactAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('nom')
            ->add('gsm1')
            ->add('gsm2')
            ->add('tel')
            ->add('whatsapp')
            ->add('email')
            ->add('organeGouvernances')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('nom', null, ['editable' => true])
            ->add('gsm1', null, ['editable' => true])
            ->add('gsm2', null, ['editable' => true])
            ->add('tel', null, ['editable' => true])
            ->add('whatsapp', null, ['editable' => true])
            ->add('email', null, ['editable' => true])
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
                ->add('nom', null, ['required'=> false])
            ->add('gsm1', null, ['required'=> false])
            ->add('gsm2', null, ['required'=> false])
            ->add('tel', null, ['required'=> false])
            ->add('whatsapp', null, ['required'=> false])
            ->add('email', null, ['required'=> false])
            ->end()
        //->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
            ->add('nom')
            ->add('gsm1')
            ->add('gsm2')
            ->add('tel')
            ->add('whatsapp')
            ->add('email')
            ->add('organeGouvernances')
        ;
    }
}
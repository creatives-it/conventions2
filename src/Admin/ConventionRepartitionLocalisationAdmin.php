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

final class ConventionRepartitionLocalisationAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('montant')
            ->add('pourcent')
            ->add('convention')
            ->add('localisation')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('montant', null, ['template' => 'bundles/SonataAdminBundle/fields/list_montant.html.twig'])
            ->add('pourcent', null, ['editable' => true])
            ->add('convention', null, ['editable' => true])
            ->add('localisation', null, ['editable' => true])
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
            ->add('localisation', null, ['required'=> false])
            ->add('montant', null, ['required'=> false])
            ->add('pourcent', null, ['required'=> false])
            //->add('convention', null, ['required'=> false])
        ->end()
        //->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
            ->add('montant', null, ['template' => 'bundles/SonataAdminBundle/fields/show_montant.html.twig'])
            ->add('pourcent')
            ->add('convention')
            ->add('localisation')
        ;
    }
}
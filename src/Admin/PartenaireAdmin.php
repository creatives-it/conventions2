<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class PartenaireAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'ASC',];

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('view', $this->getRouterIdParameter().'/view')
        ;
    }
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
            ->add('image', null, ['label' => 'Logo','template' => 'bundles/SonataAdminBundle/fields/list_vich_image-w100.html.twig'])
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
                ->add('name', null, ['required'=> true])
                ->add('imageFile', VichImageType::class, [
                    'label' => 'Logo',
                    'required'=> false,
                    'data_class' => null,
                    'attr' => ['class' => 'admin-preview']
                ])
            ->end()
        //->end()

        //->with('Photo', ['class' => 'col-md-6'])

            //->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
            ->add('name')
            ->add('conventionMaitreOuvrages')
            ->add('conventionContributions')
            ->add('conventionVersements')
            ->add('maitreOuvrages2')
            ->add('conventionPartieContractantes')
        ;
    }
}
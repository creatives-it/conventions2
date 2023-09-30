<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Datagrid\DatagridMapper;



final class StadeAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'position','_sort_order' => 'ASC',];

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
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
            ->add('_action', null, [
                'actions' => [
                    'move' => [
                        'template' => '@PixSortableBehavior/Default/_sort.html.twig',
                        'enable_top_bottom_buttons' => false,
                    ],
                    //'show' => [],
                    // 'edit' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
        //->tab('Renseignements')
            ->with('Identification', ['class' => 'col-md-6'])
                ->add('name', null, ['required'=> true])
            ->end()
        //->end()
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
            ->add('name')
            ->add('conventions')
        ;
    }
}
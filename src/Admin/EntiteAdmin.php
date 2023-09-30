<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;



final class EntiteAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'root','_sort_order' => 'ASC',];
//    protected $datagridValues = [
//        '_sort_by' => [
//            ['field' => 'root',  'direction' => 'asc'],
//            ['field' => 'lvl',   'direction' => 'asc']
//        ]];
    public function createQuery($context = 'list')
    {
        $proxyQuery = parent::createQuery('list');
        $proxyQuery->addOrderBy($proxyQuery->getRootAlias().'.root', 'ASC');
        $proxyQuery->addOrderBy($proxyQuery->getRootAlias().'.lvl', 'ASC');

        return $proxyQuery;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('name')
            ->add('typeEntite')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('name', null, ['editable' => true])
            ->add('typeEntite', null, ['editable' => true])
            ->add('parent', null, ['editable' => true])
            ->add('children', null, ['editable' => true])
            //->add('getAllAscendants', null, ['editable' => false, 'template'=>'bundles/SonataAdminBundle/fields/list_dc2type.html.twig'])
            //->add('getAllDescendants', null, ['editable' => false, 'template'=>'bundles/SonataAdminBundle/fields/list_dc2type.html.twig'])
            //->add('root', null, ['editable' => true])
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
                ->add('name', null, ['required'=> true])
                ->add('typeEntite', null, ['required'=> false])
                ->add('parent', null, ['required'=> false])
            ->end()
        //->end()


        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
        ->add('name')
        ->add('typeEntite')
        ->add('root')
        ->add('parent')
        ->add('children')
        ;
    }
}
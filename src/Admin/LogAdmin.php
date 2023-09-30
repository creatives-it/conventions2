<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

use Sonata\DoctrineORMAdminBundle\Filter\StringFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Sonata\Form\Type\DatePickerType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\Form\Type\CollectionType;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;

final class LogAdmin extends AbstractAdmin
{
    protected $datagridValues = [
    '_sort_by' => 'id',
    '_sort_order' => 'DESC',
    ];

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list','show'));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('action',StringFilter::class,  array(), ChoiceType::class, array(
                'choices' => array(
                    'update' => 'update',
                    'create' => 'create',
                    'remove'=>'remove')
            ))
            ->add('loggedAt', DateFilter::class, [],DatePickerType::class,
                [   'datepicker_use_button' => false,
                    'dp_use_current'    => false,
                    'format'            => 'dd/MM/yyyy',
                    //'dp_days_of_week_disabled'  => [0, 6],
                    ])
            ->add('objectId')
            ->add('objectClass')
            ->add('version')
            //->add('data')
            ->add('username')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('action', null, ['template' => 'bundles/SonataAdminBundle/fields/list_trans.html.twig'])
            ->add('objectId', null, ['editable' => false])
            ->add('objectClass', null, ['template' => 'bundles/SonataAdminBundle/fields/list_slice_11.html.twig'])
            ->add('version', null, ['editable' => false])
            ->add('data', null, ['template' => 'bundles/SonataAdminBundle/fields/list_dc2type.html.twig'])
            ->add('username', null, ['editable' => false])
            ->add('loggedAt', null, ['editable' => false, 'template' => 'bundles/SonataAdminBundle/fields/list_datetime.html.twig'])
            ->add('_action', null, [
                'actions' => [
                    'show' => [], 'edit' => [],
                ],
            ]);
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('action')
            ->add('loggedAt')
            ->add('objectId')
            ->add('objectClass')
            ->add('version')
            ->add('data', null, ['template' => 'bundles/SonataAdminBundle/fields/show_dc2type.html.twig'])
            ->add('username')
//            ->add('champ.nom', null, ['label' => 'Nom'])
        ;
    }
}

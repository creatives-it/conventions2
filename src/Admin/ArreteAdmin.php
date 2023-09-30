<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Vich\UploaderBundle\Form\Type\VichFileType;


final class ArreteAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('numero')
            ->add('objetArrete', null, ['show_filter' => true])
            ->add('session', null, ['show_filter' => true])
            ->add('contenu')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('numero', null, ['editable' => true])
            ->add('objetArrete', null, ['editable' => true])
            ->add('media', null, ['template' => 'bundles/SonataAdminBundle/fields/list_media_download.html.twig'])
            ->add('session', null, ['editable' => true])
            ->add('_action', null, [
                'actions' => [
                    //'show' => [],
                    'edit' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        if ($this->isCurrentRoute('edit', 'admin.arrete')) {
            $formMapper
                ->with('Identification', ['class' => 'col-md-6'])
                ->add('session', null, ['required'=> true])
                ->end()
            ;
        }
        $formMapper
        //->tab('Renseignements')
        ->with('Identification', ['class' => 'col-md-6'])
            ->add('numero', null, ['required'=> false])
            ->add('objetArrete', null, ['required'=> false])
            ->add('media', ModelListType::class, [], ['link_parameters' =>['context' => 'default']])
//            ->add('fichier', VichFileType::class, [
//                         'required'=> false,
//                         'data_class' => null,
//                      ])
        ->end()
        ->with('Contenu', ['class' => 'col-md-6'])
            ->add('contenu', SimpleFormatterType::class, [
                'required' => false,
                'format' => 'richhtml',
                'attr' => ['class' => 'ckeditor'],
                'label' => false
            ])

        ->end()
        //->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
        ->add('session')
        ->add('numero')
        ->add('objetArrete')
        ->add('media')

    ;
    }
}
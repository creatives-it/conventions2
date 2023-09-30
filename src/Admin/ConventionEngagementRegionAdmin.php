<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

//use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Vich\UploaderBundle\Form\Type\VichImageType;
//use Vich\UploaderBundle\Form\Type\VichFileType;
//use Sonata\Form\Type\DatePickerType;
//use Sonata\AdminBundle\Form\Type\ModelType;
//use Sonata\Form\Type\CollectionType;
//use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;

final class ConventionEngagementRegionAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('annee')
            ->add('engagementProgramme')
            ->add('engagementProgrammeExcedentAnneePrecedante')
            ->add('engagementNonProgramme')
            ->add('engagementRealise')
            ->add('convention')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('annee', null, ['editable' => true])
            ->add('engagementProgramme', null, ['editable' => true])
            ->add('engagementProgrammeExcedentAnneePrecedante', null, ['editable' => true])
            ->add('engagementNonProgramme', null, ['editable' => true])
            ->add('engagementRealise', null, ['editable' => true])
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
            ->add('annee', ChoiceType::class, [
                'choices'=> [
                    '2015' => '2015',
                    '2016' => '2016',
                    '2017' => '2017',
                    '2018' => '2018',
                    '2019' => '2019',
                    '2020' => '2020',
                    '2021' => '2021',
                    '2022' => '2022',
                    '2023' => '2023',
                    '2024' => '2024',
                    '2025' => '2025',
                    '2026' => '2026',
                    '2027' => '2027',
//                    '2028' => '2028',
//                    '2029' => '2029',
//                    '2030' => '2030',
//                    '2031' => '2031',
//                    '2032' => '2032',
                ],
                'required'=> true
            ])
                ->add('engagementProgramme', null, ['required'=> false])
                ->add('engagementProgrammeExcedentAnneePrecedante', null, ['required'=> false])
                ->add('engagementNonProgramme', null, ['required'=> false])
                ->add('engagementRealise', null, ['required'=> false])
            //->add('convention', null, ['required'=> false])
            ->end()
        //->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
        ->add('convention')
        ->add('annee')
        ->add('engagementProgramme')
        ->add('engagementProgrammeExcedentAnneePrecedante')
        ->add('engagementNonProgramme')
        ->add('engagementRealise')
        ;
    }
}
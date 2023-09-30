<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\Form\Type\CollectionType;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\Form\Type\DatePickerType;
use Vich\UploaderBundle\Form\Type\VichFileType;


final class SessionAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('month', ChoiceFilter::class, [
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'choices' => [
                        'يناير' => 'يناير',
                        'فبراير' => 'فبراير',
                        'مارس' => 'مارس',
                        'أبريل' => 'أبريل',
                        'ماي' => 'ماي',
                        'يونيو' => 'يونيو',
                        'يوليوز' => 'يوليوز',
                        'غشت' => 'غشت',
                        'شتنبر' => 'شتنبر',
                        'أكتوبر' => 'أكتوبر',
                        'نونبر' => 'نونبر',
                        'دجنبر' => 'دجنبر'
                    ],
                ],
            ])
            ->add('annee')
            ->add('date', DateFilter::class, [],DatePickerType::class,
                    [   'datepicker_use_button' => false,
                        'dp_use_current'    => false,
                        'format'            => 'dd/MM/yyyy',
                        //'dp_days_of_week_disabled'  => [0, 6],
                    ])
            ->add('typeSession')
            ->add('mandat')
            ->add('lieuSession')
            ->add('nbrePresents')
            ->add('contenu')
            ->add('conventions')
            ->add('conventionsTexte')
            ->add('arretes.numero')
            ->add('arretes.objetArrete')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('month', null, [
                'editable' => true,
                'header_style' => 'width: 8%; text-align: center',
                'row_align' => 'center'
            ])
            ->add('annee', null, [
                'editable' => true,
                'template' => 'bundles/SonataAdminBundle/fields/list_annee.html.twig',
                'header_style' => 'width: 8%; text-align: center',
                'row_align' => 'center'
            ])
            ->add('date', null, [
                'editable' => true,
                'header_style' => 'width: 8%; text-align: center',
                'row_align' => 'center'
            ])
            ->add('typeSession', null, [
                'editable' => true,
                'header_style' => 'width: 15%; text-align: center',
                'row_align' => 'center'
            ])
            ->add('mandat')
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
            ->add('month', ChoiceType::class, [
                'choices' => [
                    'يناير' => 'يناير',
                    'فبراير' => 'فبراير',
                    'مارس' => 'مارس',
                    'أبريل' => 'أبريل',
                    'ماي' => 'ماي',
                    'يونيو' => 'يونيو',
                    'يوليوز' => 'يوليوز',
                    'غشت' => 'غشت',
                    'شتنبر' => 'شتنبر',
                    'أكتوبر' => 'أكتوبر',
                    'نونبر' => 'نونبر',
                    'دجنبر' => 'دجنبر'
                ],
                'required'=> true
            ])
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
                    '2028' => '2028',
                    '2029' => '2029',
                    '2030' => '2030',
                    '2031' => '2031',
                    '2032' => '2032',
                ],
                'required'=> true
            ])
            ->add('date', DatePickerType::class, [
                            'required'=> false, 'dp_side_by_side' => true, 'datepicker_use_button' => false,'dp_use_current'    => false,
                        ])
            ->add('typeSession', null, ['required'=> true])
            ->add('mandat')

            ->add('lieuSession')
            ->add('nbrePresents')

        ->end()


            ->with('Contenu', ['class' => 'col-md-6'])
            ->add('contenu', SimpleFormatterType::class, [
                'required' => false,
                'format' => 'richhtml',
                'attr' => ['class' => 'ckeditor']
            ])

            ->end()
        ->with('Pieces jointes', ['class' => 'col-md-6'])
            ->add('fichier1', VichFileType::class, [
                'label' => 'Ordre du jour',
                'required'=> false,
                'data_class' => null,
            ])
            ->add('fichier2', VichFileType::class, [
                'label' => 'PV (word)',
                'required'=> false,
                'data_class' => null,
            ])
            ->add('fichier3', VichFileType::class, [
                'label' => 'PV (PDF)',
                'required'=> false,
                'data_class' => null,
            ])
        ->end()

        ->with('Arretes', ['class' => 'col-md-6'])
            ->add('arretes', CollectionType::class, ['by_reference' => false,'label'=>false,], ['edit' => 'inline','inline' => 'table',])
        ->end()

        ->with('Conventions citees', ['class' => 'col-md-6'])
            ->add('conventions')
            ->add('conventionsTexte', SimpleFormatterType::class, [
                'required' => false,
                'format' => 'richhtml',
                'attr' => ['class' => 'ckeditor']
            ])

        ->end()
        ->with('Observations', ['class' => 'col-md-6'])
            ->add('observations', SimpleFormatterType::class, [
                'label' => false,
                'required' => false,
                'format' => 'richhtml',
                'attr' => ['class' => 'ckeditor']
            ])
        ->end()
        //->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
        ->add('month')
        ->add('annee')
        ->add('date')
        ->add('typeSession')
        ->add('mandat')
        ->add('lieuSession')
        ->add('nbrePresents')
        ->add('contenu', 'html', ['strip' => true])
        ->add('conventions')
        ->add('conventionsTexte', 'html', ['strip' => true])
        ->add('observations', 'html', ['strip' => true])
        ;
    }
}
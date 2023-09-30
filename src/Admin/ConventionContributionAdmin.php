<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


final class ConventionContributionAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('montant')
            ->add('contribution')
            ->add('datePrevue', DateFilter::class, [],DatePickerType::class,
                    [   'datepicker_use_button' => false,
                        'dp_use_current'    => false,
                        'format'            => 'dd/MM/yyyy',
                        //'dp_days_of_week_disabled'  => [0, 6],
                    ])
            ->add('observation')
            ->add('partenaire')
            ->add('natureContribution')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('montant', null, ['template' => 'bundles/SonataAdminBundle/fields/list_montant.html.twig'])
            ->add('contribution', null, ['editable' => true])
            ->add('datePrevue', null, ['editable' => true])
            ->add('partenaire', null, ['editable' => true])
            ->add('natureContribution', null, ['editable' => true])
            ->add('_action', null, [
                'actions' => [
                    //'show' => [],
                    'edit' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        if (!is_null($this->getSubject()->getConvention())) {
            $query = $this->modelManager->getEntityManager('App\Entity\Partenaire')->createQueryBuilder('i');
            $query->select('i')
                ->from('App\Entity\Partenaire', 'i')
                ->leftJoin('i.conventionPartieContractantes', 'e')
                ->andWhere('e = :cnv')->setParameter('cnv',$this->getSubject()->getConvention() )
                ->orderBy('i.name')
            ;
            $partieContractantesIds = $this->getSubject()->getConvention()->getPartieContractantesIds();

        } else {
            $query = $this->modelManager->getEntityManager('App\Entity\Partenaire')->createQueryBuilder('i')
                ->select('i')
                ->from('App\Entity\Partenaire', 'i')
                ->orderBy('i.name')
            ;
            $partieContractantesIds = [];
        }

        $formMapper
        //->tab('Renseignements')
            ->with('Identification', ['class' => 'col-md-6'])
            ->add('partenaire', ModelType::class, [
                'label' => false,
                'required'=> false,
                //'query' => $query,
                'group_by' => function($choice, $key, $value)  use ($partieContractantesIds) {
                    if (in_array($value, $partieContractantesIds)) {
                        return 'المتعاقدون';
                    }
                    return 'غير المتعاقدين';
                },
                'btn_add' => false,
                'multiple' => false,
                'expanded' => false,
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
            ->add('montant', null, ['required'=> true])
            ->add('natureContribution', null, ['required'=> false])
            //->add('contribution', null, ['required'=> false])
            /*->add('datePrevue', DatePickerType::class, [
                            'required'=> false, 'dp_side_by_side' => true, 'datepicker_use_button' => false,'dp_use_current'    => false,
                        ])*/
            ->add('observation', null, ['required'=> false])
            ->end()
        //->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
            ->add('montant', null, ['template' => 'bundles/SonataAdminBundle/fields/show_montant.html.twig'])
            ->add('contribution')
            ->add('datePrevue')
            ->add('observation')
            ->add('partenaire')
            ->add('natureContribution')
        ;
    }
}
<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


final class ConventionEngagementAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('annee')
            ->add('montantProgramme')
            ->add('montantRealise')
            ->add('observation')
            ->add('intitule')
            ->add('convention')
            ->add('natureContribution')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('annee', null, ['editable' => true])
            ->add('montantProgramme', null, ['template' => 'bundles/SonataAdminBundle/fields/list_montant.html.twig'])
            ->add('montantRealise', null, ['template' => 'bundles/SonataAdminBundle/fields/list_montant.html.twig'])
            ->add('intitule', null, ['editable' => true])
            ->add('convention', null, ['editable' => true])
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
            ->add('montantProgramme', null, ['required'=> true])
            //->add('intitule', null, ['required'=> false])
            ->add('natureContribution', null, ['required'=> false])
            //->add('montantRealise', null, ['required'=> false])
            ->add('observation', null, ['required'=> false])
            //->add('convention', null, ['required'=> false])
            ->end()
        //->end()

        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
            ->add('annee')
            ->add('montantProgramme', null, ['template' => 'bundles/SonataAdminBundle/fields/show_montant.html.twig'])
            ->add('montantRealise', null, ['template' => 'bundles/SonataAdminBundle/fields/show_montant.html.twig'])
            ->add('observation')
            ->add('intitule')
            ->add('convention')
            ->add('natureContribution')
        ;
    }
}
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

//use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Vich\UploaderBundle\Form\Type\VichImageType;
//use Vich\UploaderBundle\Form\Type\VichFileType;
//use Sonata\Form\Type\DatePickerType;
//use Sonata\AdminBundle\Form\Type\ModelType;
//use Sonata\Form\Type\CollectionType;
//use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;

final class ConventionSignatureAdmin extends AbstractAdmin
{
    protected $datagridValues = ['_sort_by' => 'id','_sort_order' => 'DESC',];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('dateTransmissionPourSignature', DateFilter::class, [],DatePickerType::class,
                    [   'datepicker_use_button' => false,
                        'dp_use_current'    => false,
                        'format'            => 'dd/MM/yyyy',
                        //'dp_days_of_week_disabled'  => [0, 6],
                    ])
            ->add('numeroEnvoiDepart')
            ->add('rappel1')
            ->add('rappel2')
            ->add('dateCourrierArrive', DateFilter::class, [],DatePickerType::class,
                    [   'datepicker_use_button' => false,
                        'dp_use_current'    => false,
                        'format'            => 'dd/MM/yyyy',
                        //'dp_days_of_week_disabled'  => [0, 6],
                    ])
            ->add('numeroCourrierArrive')
            ->add('observations')
            ->add('convention')
            ->add('partenaire')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('dateTransmissionPourSignature', null, ['editable' => true])
            ->add('numeroEnvoiDepart', null, ['editable' => true])
            ->add('rappel1', null, ['editable' => true])
            ->add('rappel2', null, ['editable' => true])
            ->add('dateCourrierArrive', null, ['editable' => true])
            ->add('numeroCourrierArrive', null, ['editable' => true])
            ->add('convention', null, ['editable' => true])
            ->add('partenaire', null, ['editable' => true])
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
            ->add('dateTransmissionPourSignature', DatePickerType::class, [
                            'required'=> false, 'dp_side_by_side' => true, 'datepicker_use_button' => false,'dp_use_current'    => false,
                        ])
            ->add('numeroEnvoiDepart', null, ['required'=> false])
            ->add('rappel1', null, ['required'=> false])
            ->add('rappel2', null, ['required'=> false])
            ->add('isSignee', null, ['required'=> false])
            ->add('dateCourrierArrive', DatePickerType::class, [
                            'required'=> false, 'dp_side_by_side' => true, 'datepicker_use_button' => false,'dp_use_current'    => false,
                        ])
            ->add('numeroCourrierArrive', null, ['required'=> false])
            ->add('observations', null, ['required'=> false])
            //->add('convention', null, ['required'=> false])
        ->end()
        //->end()


        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
    $showMapper
            ->add('dateTransmissionPourSignature')
            ->add('numeroEnvoiDepart')
            ->add('rappel1')
            ->add('rappel2')
            ->add('dateCourrierArrive')
            ->add('numeroCourrierArrive')
            ->add('observations')
            ->add('convention')
            ->add('partenaire')
        ;
    }
}
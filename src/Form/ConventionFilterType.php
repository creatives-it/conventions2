<?php

namespace App\Form;

use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type;

class ConventionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('numero', Type\NumberRangeFilterType::class)
            //->add('montantConvention', Type\NumberRangeFilterType::class, ['label'=> 'Montant Convention'])
            //->add('dateSession', Type\DateRangeFilterType::class, ['label'=> 'Date Session'])
            ->add('objetConvention', Type\TextFilterType::class, ['label'=> 'Objet Convention'])
            ->add('partieContractantes', Type\EntityFilterType::class, [
                'class' => 'App\Entity\Partenaire',
                'multiple' => true,
                'label'=> 'Partie Contractantes',
                'apply_filter'  => function (QueryInterface $filterQuery, $field, $values)
                {
                    if (empty($values['value'])) {
                        return null;
                    }
                    $query = $filterQuery->getQueryBuilder();
                    $query->leftJoin($field, 'p');
                    foreach ($values['value'] as $value)  {
                        $query->andWhere(':value MEMBER OF e.partieContractantes')->setParameter('value',$value);
                    }
                },
            ])
            ->add('typeConvention', Type\EntityFilterType::class, [
                'class' => 'App\Entity\TypeConvention',
                'multiple' => true,
                'label'=> 'Type Convention'
            ])
            ->add('sessionApprobation', Type\EntityFilterType::class, [
                'class' => 'App\Entity\Session',
                'multiple' => true
            ])
            ->add('secteur', Type\EntityFilterType::class, [
                'class' => 'App\Entity\Secteur',
                'multiple' => true
            ])
            ->add('localisation', Type\EntityFilterType::class, [
                'class' => 'App\Entity\Localisation',
                'multiple' => true
            ])
            ->add('entiteSuiviExecution', Type\EntityFilterType::class, [
                'class' => 'App\Entity\Entite',
                'multiple' => true,
                'label'=> 'Entite Suivi Execution'
            ])
            ->add('maitreOuvrage', Type\EntityFilterType::class, [
                'class' => 'App\Entity\Partenaire',
                'multiple' => true,
                'label'=> 'Maitre Ouvrage'
            ])
            ->add('stade', Type\EntityFilterType::class, [
                'class' => 'App\Entity\Stade',
                'multiple' => true
            ])
            ->add('isAvenant', Type\BooleanFilterType::class)
            //->add('vise', Type\BooleanFilterType::class)


            /*->add('conventionDestinataires', Type\CollectionAdapterFilterType::class, array(
                'label' => false,
                //'label' => 'ConventionDestinataire',
                'entry_type' => ConventionDestinataireEntiteFilterType::class,
                'add_shared' => function (FilterBuilderExecuterInterface $qbe)  {
                    $closure = function (QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr) {
                        // add the join clause to the doctrine query builder
                        // the where clause for the label and color fields will be added automatically with the right alias later by the Lexik\Filter\QueryBuilderUpdater
                        $filterBuilder->leftJoin($alias . '.conventionDestinataires', $joinAlias);
                    };

                    // then use the query builder executor to define the join and its alias.
                    $qbe->addOnce($qbe->getAlias().'.conventionDestinataires', 'd', $closure);
                },
            ))*/
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection'   => false,
            'validation_groups' => array('filtering')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'convention_filter';
    }
}

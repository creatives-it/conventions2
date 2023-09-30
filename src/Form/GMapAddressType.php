<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class GMapAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', null, array(
                'required'      => true,
            ))
            ->add('locality', HiddenType::class, array(
                'required'      => false,
            ))
            ->add('country', HiddenType::class, array(
                'required'      => false
            ))
            ->add('lat', HiddenType::class, array(
                'required'      => false
            ))
            ->add('lng', HiddenType::class, array(
                'required'      => false
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'virtual'   => true, // Ici nous précisons que notre FormType est un champ virtuel
        ]);
    }

    public function getName()
    {
        return 'gmap_address'; // Le nom de notre champ, il sera utilisé après
    }
}

?>
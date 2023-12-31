<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Sonata\UserBundle\Admin;

use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Sonata\UserBundle\Form\Type\SecurityRolesType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormTypeInterface;

class SonataUserAdmin extends AbstractAdmin
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $options = $this->formOptions;
        $options['validation_groups'] = (!$this->getSubject() || null === $this->getSubject()->getId()) ? 'Registration' : 'Profile';

        $formBuilder = $this->getFormContractor()->getFormBuilder($this->getUniqid(), $options);

        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getExportFields()
    {
        // avoid security field to be exported
        return array_filter(parent::getExportFields(), static function ($v) {
            return !\in_array($v, ['password', 'salt'], true);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user): void
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    public function setUserManager(UserManagerInterface $userManager): void
    {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('username')
            ->add('groups')
            ->add('entiteConsultantes')
            //->add('realRoles', null, ['template' => 'bundles/SonataAdminBundle/fields/list_dc2type.html.twig'])
            ->add('enabled', null, ['editable' => true])
            //->add('contactsAutres')
        ;

        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add('impersonating', 'string', ['template' => '@SonataUser/Admin/Field/impersonating.html.twig'])
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper): void
    {
        $filterMapper
            //->add('id')
            ->add('username')
            //->add('email')
            ->add('groups')
            //->add('fonctionnaire', null,['admin_code' => 'admin.fonctionnaire'])
            //->add('contact')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->with('Général')
                ->add('username')
                ->add('email')
                //->add('fonctionnaire', null,['admin_code' => 'admin.fonctionnaire'])
            ->end()
            ->with('Groupes')
                ->add('groups')
            ->end()
            ->with('Profile')
                ->add('dateOfBirth')
                ->add('firstname')
                ->add('lastname')
                //->add('contact')
                //->add('services')
//                ->add('website')
//                ->add('biography')
//                ->add('gender')
//                ->add('locale')
//                ->add('timezone')
                ->add('phone')
            ->end()
//            ->with('Social')
//                ->add('facebookUid')
//                ->add('facebookName')
//                ->add('twitterUid')
//                ->add('twitterName')
//                ->add('gplusUid')
//                ->add('gplusName')
//            ->end()
            ->with('Sécurité')
                ->add('token')
                ->add('twoStepVerificationCode')
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        // define group zoning
        $formMapper
            ->tab('Identification')
                ->with('General', ['class' => 'col-md-6'])->end()
                ->with('Profile', ['class' => 'col-md-6'])->end()
                //->with('Social', ['class' => 'col-md-6'])->end()
                ->with('Status', ['class' => 'col-md-6'])->end()
            ->end()
            ->tab('Groupes')
                ->with('Groupes', ['class' => 'col-md-6'])->end()
//                ->with('Keys', ['class' => 'col-md-4'])->end()
//            ->with('Rôles', ['class' => 'col-md-6'])->end()
            ->end()
        ;

        $now = new \DateTime();

        $genderOptions = [
            'choices' => \call_user_func([$this->getUserManager()->getClass(), 'getGenderList']),
            'required' => true,
            'translation_domain' => $this->getTranslationDomain(),
        ];

        // NEXT_MAJOR: Remove this when dropping support for SF 2.8
        if (method_exists(FormTypeInterface::class, 'setDefaultOptions')) {
            $genderOptions['choices_as_values'] = true;
        }

        $formMapper
            ->tab('Identification')
                ->with('General')
                    ->add('username', null, ['label'=>'Compte'])
                    //->add('fonctionnaire', null, ['required'=> false],['admin_code' => 'admin.fonctionnaire'])
                    ->add('email')
                    ->add('plainPassword', TextType::class, [
                        'required' => (!$this->getSubject() || null === $this->getSubject()->getId()),
                    ])
                    ->add('poste')
                    ->add('entiteConsultantes')
                    ->add('autresInfos')
                ->end()
                ->with('Profile')
    //                    ->add('dateOfBirth', DatePickerType::class, [
    //                        'years' => range(1900, $now->format('Y')),
    //                        'dp_min_date' => '1-1-1900',
    //                        'dp_max_date' => $now->format('c'),
    //                        'required' => false,
    //                    ])
                    ->add('firstname', null, ['required' => false])
                    ->add('lastname', null, ['required' => false])
        //                    ->add('website', UrlType::class, ['required' => false])
                    //->add('contact')
        //                    ->add('biography', TextType::class, ['required' => false])
        //                    ->add('gender', ChoiceType::class, $genderOptions)
        //                    ->add('locale', LocaleType::class, ['required' => false])
        //                    ->add('timezone', TimezoneType::class, ['required' => false])
                    ->add('phone', null, ['required' => false])
                ->end()
                ->with('Status')
                    ->add('enabled', null, ['required' => false])
                ->end()
            //                ->with('Social')
//                    ->add('facebookUid', null, ['required' => false])
//                    ->add('facebookName', null, ['required' => false])
//                    ->add('twitterUid', null, ['required' => false])
//                    ->add('twitterName', null, ['required' => false])
//                    ->add('gplusUid', null, ['required' => false])
//                    ->add('gplusName', null, ['required' => false])
//                ->end()
            ->end()
            ->tab('Groupes')

                ->with('Groupes')
                    ->add('groups', ModelType::class, [
                        'required' => false,
                        'expanded' => true,
                        'multiple' => true,
                    ])
                ->end()
//                ->with('Rôles')
//                    ->add('realRoles', SecurityRolesType::class, [
//                        'label' => false,
//                        'expanded' => true,
//                        'multiple' => true,
//                        'required' => false,
//                    ])
//                ->end()
//                ->with('Keys')
//                    ->add('token', null, ['required' => false])
//                    ->add('twoStepVerificationCode', null, ['required' => false])
//                ->end()
            ->end()
        ;
    }
}

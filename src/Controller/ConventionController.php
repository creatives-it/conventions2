<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\MesfonctionsController;
use App\Entity\Convention;
use App\Entity\ConventionContribution;
use App\Entity\ConventionEngagement;
use App\Entity\ConventionLecture;
use App\Entity\ConventionSuivi;
use App\Entity\Partenaire;
use App\Form\ConventionFilterType;
use App\Service\ContainerParametersHelper;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\TemplateProcessor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @Route("/convention")
 */
final class ConventionController extends AbstractController
{
    private $mesfonctionsController;
    private $defaultController;
    private $translator;
    private $pathHelpers;

    public function __construct(DefaultController $defaultController, MesfonctionsController $mesfonctionsController, TranslatorInterface $translator, ContainerParametersHelper $pathHelpers)
    {
        $this->pathHelpers = $pathHelpers;
        $this->mesfonctionsController = $mesfonctionsController;
        $this->defaultController = $defaultController;
        $this->translator = $translator;
    }

    /**
     * @Route("/liste_suivis", name="convention_liste_suivis")
     * @Security("is_granted('ROLE_USER')")
     */
    public function liste_suivis(Request $request, FilterBuilderUpdaterInterface $query_builder_updater)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $form = $this->get('form.factory')->create(ConventionFilterType::class)
            //->remove('typeConvention')
        ;
        //$conventions = $em->getRepository(Convention::class)->findBy([],['numero'=>'ASC','numeroAn'=>'ASC']);
        $conventions = $this->getDoctrine()->getManager()
            ->getRepository(Convention::class)
            ->createQueryBuilder('c')
            ->join('c.conventionSuivis', 's')
            ->andWhere('s.user = :u')->setParameter('u', $user)
            ->orderBy('c.numero', 'ASC')
            ->addOrderBy('c.numeroAn', 'ASC')
            ->getQuery()->getResult();
        //dump($conventions);die;
        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $filterBuilder = $this->getDoctrine()->getManager()
                ->getRepository(Convention::class)
                ->createQueryBuilder('c')
                ->join('c.conventionSuivis', 's')
                ->andWhere('s.user = :u')->setParameter('u', $user)
                ->orderBy('c.numero', 'ASC')
                ->addOrderBy('c.numeroAn', 'ASC');
            $query_builder_updater->addFilterConditions($form, $filterBuilder);
            //var_dump($filterBuilder->getDql()); die;
            $conventions = $filterBuilder->getQuery()->getResult();
        }
        return $this->render('convention/liste_suivis.html.twig', array(
            'form' => $form->createView(),
            'conventions' => $conventions,
        ));
    }


    /**
     * @Route("/liste_unread", name="convention_liste_unread")
     * @Security("is_granted('ROLE_USER')")
     */
    public function liste_unread(Request $request, FilterBuilderUpdaterInterface $query_builder_updater)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $form = $this->get('form.factory')->create(ConventionFilterType::class)
            //->remove('typeConvention')
        ;
        //$conventions = $em->getRepository(Convention::class)->findBy([],['numero'=>'ASC','numeroAn'=>'ASC']);
        $conventionReads = $this->getDoctrine()->getManager()
            ->getRepository(Convention::class)
            ->createQueryBuilder('c')
            ->join('c.conventionLectures', 's')
            ->andWhere('s.readBy = :u')->setParameter('u', $user)
            ->getQuery()->getResult();
        $conventionReadIds = [0];
        foreach ($conventionReads as $conventionRead) {
            $conventionReadIds[] = $conventionRead->getid();
        }

        if (!($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) and !($this->get('security.authorization_checker')->isGranted('ROLE_CONV_SUIVI_FINANCIER'))) {
            $entites = $this->defaultController->entiteaconsulters(true);
            $conventions = $this->getDoctrine()->getManager()
                ->getRepository(Convention::class)
                ->createQueryBuilder('c')
                ->join('c.entiteConsultantes','e','WITH','e.id IN (:es) ')->setParameter('es', $entites)
                ->leftJoin('c.conventionLectures', 's')
                ->andWhere('c.id not in (:ids)')->setParameter('ids', $conventionReadIds)
                ->orderBy('c.numero', 'ASC')
                ->addOrderBy('c.numeroAn', 'ASC')
                ->getQuery()->getResult();
        } else {
            $conventions = $this->getDoctrine()->getManager()
                ->getRepository(Convention::class)
                ->createQueryBuilder('c')
                ->leftJoin('c.conventionLectures', 's')
                ->andWhere('c.id not in (:ids)')->setParameter('ids', $conventionReadIds)
                ->orderBy('c.numero', 'ASC')
                ->addOrderBy('c.numeroAn', 'ASC')
                ->getQuery()->getResult();
        }
        //dump($conventions);die;
        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));

            if (!($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) and !($this->get('security.authorization_checker')->isGranted('ROLE_CONV_SUIVI_FINANCIER'))) {
                $entites = $this->defaultController->entiteaconsulters(true);
                $filterBuilder = $this->getDoctrine()->getManager()
                    ->getRepository(Convention::class)
                    ->createQueryBuilder('c')
                    ->join('c.entiteConsultantes','e','WITH','e.id IN (:es) ')->setParameter('es', $entites)
                    ->leftJoin('c.conventionLectures', 's')
                    ->andWhere('c.id not in (:ids)')->setParameter('ids', $conventionReadIds)
                    ->orderBy('c.numero', 'ASC')
                    ->addOrderBy('c.numeroAn', 'ASC');
            } else {
                $filterBuilder = $this->getDoctrine()->getManager()
                    ->getRepository(Convention::class)
                    ->createQueryBuilder('c')
                    ->join('c.conventionLectures', 's')
                    ->andWhere('s.user = :u')->setParameter('u', $user)
                    ->orderBy('c.numero', 'ASC')
                    ->addOrderBy('c.numeroAn', 'ASC');
            }
            $query_builder_updater->addFilterConditions($form, $filterBuilder);
            //var_dump($filterBuilder->getDql()); die;
            $conventions = $filterBuilder->getQuery()->getResult();
        }
        return $this->render('convention/liste_unread.html.twig', array(
            'form' => $form->createView(),
            'conventions' => $conventions,
        ));
    }


    public function nbreTotalConventionsUnread(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $nbre = 99;
        $conventionReads = $this->getDoctrine()->getManager()
            ->getRepository(Convention::class)
            ->createQueryBuilder('c')
            ->join('c.conventionLectures', 's')
            ->andWhere('s.readBy = :u')->setParameter('u', $user)
            ->getQuery()->getResult();
        $conventionReadIds = [0];
        foreach ($conventionReads as $conventionRead) {
            $conventionReadIds[] = $conventionRead->getid();
        }

        if (!($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) and !($this->get('security.authorization_checker')->isGranted('ROLE_CONV_SUIVI_FINANCIER'))) {
            $entites = $this->defaultController->entiteaconsulters(true);
            $conventions = $this->getDoctrine()->getManager()
                ->getRepository(Convention::class)
                ->createQueryBuilder('c')
                ->join('c.entiteConsultantes','e','WITH','e.id IN (:es) ')->setParameter('es', $entites)
                ->leftJoin('c.conventionLectures', 's')
                ->andWhere('c.id not in (:ids)')->setParameter('ids', $conventionReadIds)
                ->andWhere('c.id > 388')
                ->orderBy('c.numero', 'ASC')
                ->addOrderBy('c.numeroAn', 'ASC')
                ->getQuery()->getResult();
        } else {
            $conventions = $this->getDoctrine()->getManager()
                ->getRepository(Convention::class)
                ->createQueryBuilder('c')
                ->leftJoin('c.conventionLectures', 's')
                ->andWhere('c.id not in (:ids)')->setParameter('ids', $conventionReadIds)
                ->andWhere('c.id > 388')
                ->orderBy('c.numero', 'ASC')
                ->addOrderBy('c.numeroAn', 'ASC')
                ->getQuery()->getResult();
        }
        $nbre = count($conventions);
        return $this->render('default/nbre.html.twig', array('nbre' => $nbre));
    }

    /**
     * @Route("/{id}/suivre", name="convention_suivre")
     * @Security("is_granted('ROLE_USER')")
     */
    public function suivre(Request $request, Convention $convention)
    {
        $entity = new ConventionSuivi();
        $entity->setConvention($convention);
        $entity->setUser($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);$em->flush();
        $this->addFlash('success', $this->translator->trans('Convention marquée comme suivie'));
        return $this->redirectToRoute('admin_app_convention_view', ['id'=>$convention->getId()]);
    }
    /**
     * @Route("/{id}/annuler_suivi", name="convention_annuler_suivi")
     * @Security("is_granted('ROLE_USER')")
     */
    public function annulerSuivi(Request $request, Convention $convention)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository(ConventionSuivi::class)->findBy([
            'convention' => $convention,
            'user' => $this->getUser()
        ]);
        foreach ($entities as $entity) :
            $em->remove($entity);
        endforeach;
        $em->flush();
        $this->addFlash('success', $this->translator->trans('Suivi annulé'));
        return $this->redirectToRoute('admin_app_convention_view', ['id'=>$convention->getId()]);
    }

}
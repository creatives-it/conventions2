<?php

namespace App\Controller;

use App\Entity\Arrete;
use App\Entity\Convention;
use App\Entity\ConventionContribution;
use App\Entity\ConventionVersement;
use App\Entity\Entite;
use App\Entity\Partenaire;
use App\Entity\Projet;
use App\Service\Settings;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home()
    {
        return $this->redirectToRoute('sonata_admin_dashboard');
//        return $this->render('default/home.html.twig', [
//            'controller_name' => 'DefaultController',
//        ]);
    }

    /**
     * @Route("/entiteaconsulters", name="entiteaconsulters")
     */
    public function entiteaconsulters($ids=false)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $return = array();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $entites = $em->getRepository(Entite::class)->findAll();
        } else {
            $entites = $user->getEntiteConsultantes();
            foreach ($entites as $entite) :
                /* @var Entite $entite */
                if (!$entite->getAllDescendants()->isEmpty()) {
                    foreach ($entite->getAllDescendants() as $subEntite) :
                        $entites->add($subEntite);
                    endforeach;
                }
            endforeach;
        }
        $return = $entites;
        if ($ids) {
            $entiteIds = array();
            foreach ($entites as $entite) :
                $entiteIds[] = $entite->getId();
            endforeach;
            return $entiteIds;
        }
        return $return;
    }


    /**
     * @Route("convention/deletedItems", name="convention_deletedItems")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function deletedItems()
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('softdeleteable');
        $rows = $em->getRepository(Convention::class)->getDeletedItems();
        return $this->render('convention/deleted_items.html.twig', [
            'rows' => $rows,
        ]);
    }

    public function nbreTotalConventions(Request $request)
    {
        $now = new Datetime();
        $annee = $now->format('Y');
        $em = $this->getDoctrine()->getManager();
        //$nbre = 99;
        $nbre = $em->getRepository(Convention::class)->nbreTotal();
        return $this->render('default/nbre.html.twig', array('nbre' => $nbre));
    }

    public function nbreTotalArretes(Request $request)
    {
        $now = new Datetime();
        $annee = $now->format('Y');
        $em = $this->getDoctrine()->getManager();
        //$nbre = 99;
        $nbre = $em->getRepository(Arrete::class)->nbreTotal();
        return $this->render('default/nbre.html.twig', array('nbre' => $nbre));
    }


    public function nbreTotalProjets(Request $request)
    {
        $now = new Datetime();
        $annee = $now->format('Y');
        $em = $this->getDoctrine()->getManager();
        //$nbre = 99;
        $nbre = $em->getRepository(Projet::class)->nbreTotal();
        return $this->render('default/nbre.html.twig', array('nbre' => $nbre));
    }


    public function nbreConventionsApprouves($typeConvention)
    {
        $em = $this->getDoctrine()->getManager();
        $nbre = $em->getRepository(Convention::class)->nbreConventionsApprouves($typeConvention);
        return $this->render('default/nbre.html.twig', array('nbre' => $nbre));
    }

    public function nbreAvenantsApprouves()
    {
        $em = $this->getDoctrine()->getManager();
        $nbre = $em->getRepository(Convention::class)->nbreAvenantsApprouves();
        return $this->render('default/nbre.html.twig', array('nbre' => $nbre));
    }

    public function nbreConventionsByStade($stade)
    {
        $em = $this->getDoctrine()->getManager();
        $nbre = $em->getRepository(Convention::class)->nbreConventionsByStade($stade);
        return $this->render('default/nbre.html.twig', array('nbre' => $nbre));
    }

    public function nbreConventionsAttenteVisa(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $conventions = $em->getRepository(Convention::class)->getListAttenteVisa();

        //$nbre = 99;
        $nbre = count($conventions);
        return $this->render('default/nbre.html.twig', array('nbre' => $nbre));
    }

    public function nbreConventionsAttenteSignaturePartenaires(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $conventions = $em->getRepository(Convention::class)->getListAttenteSignaturePartenaires();

        //$nbre = 99;
        $nbre = count($conventions);
        return $this->render('default/nbre.html.twig', array('nbre' => $nbre));
    }

    public function nbreTotalPartenaires(Request $request)
    {
        $now = new Datetime();
        $annee = $now->format('Y');
        $em = $this->getDoctrine()->getManager();
        $nbre = $em->getRepository(Partenaire::class)->nbreTotal();
        return $this->render('default/nbre.html.twig', array('nbre' => $nbre));
    }

    public function montantTotalContributions(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $montant = $em->getRepository(ConventionContribution::class)->montantTotal();
        return $this->render('default/montant.html.twig', array('montant' => $montant));
    }

    public function montantTotalVersements(Request $request)
    {
        $now = new Datetime();
        $annee = $now->format('Y');
        $em = $this->getDoctrine()->getManager();
        //$nbre = 99;
        $montant = $em->getRepository(ConventionVersement::class)->montantTotal();
        return $this->render('default/montant.html.twig', array('montant' => $montant));
    }


    public function getLastInserteds(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $boxClass = 'box-success';
        $titres[0] = 'آخر الإتفاقيات إضافة';
        $titres[1] = 'الرقم';
        $titres[2] = 'المشروع';
        $list = $em->getRepository(Convention::class)->getLastInserteds();
        //dd($list);
        return $this->render('default/box_list_convention_lasts.html.twig', array(
            'list' => $list,
            'titres' => $titres,
            'boxClass' => $boxClass
        ));
    }

    public function getLastUpdateds(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $boxClass = 'box-warning';
        $titres[0] = 'آخر الإتفاقيات تعديلا';
        $titres[1] = 'الرقم';
        $titres[2] = 'المشروع';
        $list = $em->getRepository(Convention::class)->getLastUpdateds();
        //dd($list);
        return $this->render('default/box_list_convention_lasts.html.twig', array(
            'list' => $list,
            'titres' => $titres,
            'boxClass' => $boxClass
        ));
    }

    /**
     * @Route("/calendar", name="calendar", methods={"GET"})
     */
    public function calendar()
    {
        return $this->render('Calendar/calendar.html.twig');
    }

    /**
     * @Route("/initsettings", name="initsettings", methods={"GET"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function initsettings(Settings $settings)
    {
        $settings->set('SITE_NAME','Bureau d\'ordre','string', 1);
        // modifiables
        $this->addFlash('sonata_flash_success', 'Paramètres initialisés avec succès !');
        return $this->redirectToRoute('sonata_admin_dashboard');
    }


    /**
     * @Route("/entities", name="entities", methods={"GET"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function entitiesAction() {
        $entities= [];
        $em = $this->getDoctrine()->getManager();
        $metas = $em->getMetadataFactory()->getAllMetadata();
        foreach ($metas as $meta) {
            $name = $meta->getName();
            if(strpos($name, 'App\Entity') !== false) {
                $array = explode("\\", $name);
                $entities[] = $array[count($array)-1];
            }
            //$entities[] = $meta->getName();
        }
        asort($entities);
        return $this->render('default/entities.html.twig', [
            'entities' => $entities
        ]);
    }

    /**
     * @Route("/roles", name="roles", methods={"GET"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function rolesAction() {
        $roles = [];
        $entities= [];
        $em = $this->getDoctrine()->getManager();
        $metas = $em->getMetadataFactory()->getAllMetadata();
        foreach ($metas as $meta) {
            $name = $meta->getName();
            if(strpos($name, 'App\Entity') !== false) {
                $array = explode("\\", $name);
                $entities[] = $array[count($array)-1];
            }
            //$entities[] = $meta->getName();
        }
        //dump($entities);die;
        foreach ($entities as $entity) {
            $roles[] = "ROLE_ADMIN_".strtoupper($this->from_camel_case($entity))."_ALL";
            //$roles[] = strtoupper($entity);
        }
        asort($roles);
        return $this->render('default/roles.html.twig', [
            'roles' => $roles
        ]);
    }

    function from_camel_case($input) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}

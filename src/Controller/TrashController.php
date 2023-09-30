<?php

namespace App\Controller;

use App\Entity\Convention;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Route("/trash")
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class TrashController extends AbstractController
{
    /**
     * @Route("/home", name="trash_homepage")
     */
    public function home()
    {
        return $this->render('trash/index.html.twig');
    }


    /**
     * @Route("/conventions", name="trash_conventions")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function conventions()
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('softdeleteable');
        $rows = $em->getRepository(Convention::class)->getDeletedItems();
        return $this->render('trash/conventions.html.twig', [
            'rows' => $rows,
        ]);
    }

    /**
     * @Route("/{id}/undelete_convention", name="trash_undelete_convention")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     */
    public function undeleteConvention($id)
    {
        $em = $this->getDoctrine()->getManager();
        $filter = $em->getFilters()->disable('softdeleteable');
        $convention= $em->getRepository(Convention::class)->find($id);
        $convention->setDeletedAt(null);
        $em->persist($convention); $em->flush();
        //$em->getFilters()->getFilter('softdeleteable')->disableForEntity(ConventionContribution::class);
        $this->addFlash('sonata_flash_success', 'Conventions recouvrées avec succès !');
        return $this->redirectToRoute('trash_conventions');
    }


}

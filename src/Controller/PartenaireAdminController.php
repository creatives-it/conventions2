<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\MesfonctionsController;
use App\Entity\Partenaire;
use App\Service\ContainerParametersHelper;
use PhpOffice\PhpWord\Element\Field;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\TemplateProcessor;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class PartenaireAdminController extends CRUDController
{
    private $mesfonctionsController;
    private $translator;
    private $pathHelpers;

    public function __construct(MesfonctionsController $mesfonctionsController, TranslatorInterface $translator, ContainerParametersHelper $pathHelpers)
    {
        $this->pathHelpers = $pathHelpers;
        $this->mesfonctionsController = $mesfonctionsController;
        $this->translator = $translator;
    }

    /**
     * @param int $id
     */
    public function viewAction($id = null)
    {
        try {
            if ($id !== null) {
                $partenaire = $this->admin->getObject($id);
            }
        } catch (NotFoundHttpException $e) {
            error_log($e->getMessage());
        }
        /*$partenaire->setDateRead(new \Datetime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($partenaire);
        $em->flush();*/

        return $this->render('partenaire/view.html.twig', [
            'partenaire' => $partenaire,
        ]);
        /*$response = $this->forward('App\Controller\PartenaireController::viewAction', [
            'partenaire'  => $partenaire,
        ]);
        return $response;*/
    }


}
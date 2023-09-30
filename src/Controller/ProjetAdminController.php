<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\MesfonctionsController;
use App\Entity\Projet;
use App\Service\ContainerParametersHelper;
//use GuzzleHttp\Client;
use Ivory\GoogleMap\Base\Bound;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlay\InfoWindow;
use Ivory\GoogleMap\Overlay\Marker;
use Ivory\GoogleMap\Service\Geocoder\Request\GeocoderAddressRequest;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

use Ivory\GoogleMap\Service\Geocoder\GeocoderService;
use Ivory\GoogleMap\Service\Serializer\SerializerBuilder;
use Http\Adapter\Guzzle6\Client;
use Http\Message\MessageFactory\GuzzleMessageFactory;

final class ProjetAdminController extends CRUDController
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
                $projet = $this->admin->getObject($id);
            }
        } catch (NotFoundHttpException $e) {
            error_log($e->getMessage());
        }

        $map = new Map();
        $map->setAutoZoom(true);
        $map->setCenter(new Coordinate(35.7632743,-5.8344698));
        $map->setMapOption('zoom', 14);
        $map->setBound(new Bound(new Coordinate(35, -6), new Coordinate(36, -5)));
        $map->setStylesheetOptions(array('width' => '100%', 'height' => '100%'));
        //$map->setStylesheetOption('width', '1200px');
        //$map->setStylesheetOption('height', '700px');
        //$map->setLanguage('fr');
        /*$latlng = [35.7632743,-5.8344698];
        $position = new Coordinate($latlng[0], $latlng[1], true);
        $marker = new Marker($position, 'drop', null, null, null);

$map->getOverlayManager()->addMarker($marker);

/*
 * $r600 Amphitheatre Parkway, Mountain View, CA';
$request = new GeocoderAddressRequest('1600 Amphitheatre Parkway, Mountain View, CA');
$response = $geocoder->geocode($request);

$geocoder = new GeocoderService(
    new Client(),
    new GuzzleMessageFactory(),
    SerializerBuilder::create($psr6Pool)
);
$geocoder = new GeocoderService(new Client(), new GuzzleMessageFactory());
$response = $geocoder->geocode($request);

dd($response);
*/
        return $this->render('projet/view.html.twig', [
            'projet' => $projet,
            'map' => $map
        ]);
        /*$response = $this->forward('App\Controller\ProjetController::viewAction', [
            'projet'  => $projet,
        ]);
        return $response;*/
    }


}
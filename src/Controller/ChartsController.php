<?php

namespace App\Controller;

use App\Entity\Convention;

use App\Entity\ConventionContribution;
use App\Entity\ConventionEngagement;
use App\Entity\Partenaire;
use App\Service\ContainerParametersHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/charts")
 */
class ChartsController extends AbstractController
{
    private $mesfonctionsController;
    private $pathHelpers;
    private $translator;

    public function __construct(MesfonctionsController $mesfonctionsController, ContainerParametersHelper $pathHelpers, TranslatorInterface $translator)
    {
        $this->mesfonctionsController = $mesfonctionsController;
        $this->pathHelpers = $pathHelpers;
        $this->translator = $translator;
    }
    /**
     * @Route("/", name="charts")
     */
    public function index()
    {
        return $this->render('charts/index.html.twig', [
        ]);
    }



    // Graphe 10 : Répartition du personnel médical et infirmier par province et préfecture
    /**
     * @Route("/graph10", name="graph10")
     */
    function graph10() {

        //  column
        $titre = 'Répartition du personnel médical et infirmier par province et préfecture';
        $div = 'graph10';
        $xAxis4 = array(
            'categories' => array('CHR Agadir Ida Outanane','CHP Inezgane Ait Melloul','CHP Chtouka Ait Baha','CHP Taroudant','HL Taroudant','CHP Tiznit','CHP Tata'),
            'type' => 'category',
            'labels' => array(
//                'rotation' => -45,
//                'align' => 'left',
                'style' => array('fontSize' => '13px')
            ),
            'reversed' => true
        );

        $yAxis4 = array(
            'min' => 0,
            'title' => array(
                'text' => '',
                'useHTML' => 'Highcharts.hasBidiBug'
            ),
            'opposite' => true,
            'stackLabels' => array(
                'enabled' => true,
                'style' => array('fontWeight' => 'bold',)
            )
        );

        $series4 = array(
            array('name'=>'Personnel Médical','data'=> array(106,53,24,53,11,43,16)),
            array('name'=>'Personnel infirmier','data'=> array(189,165,99,132,55,148,56)),
            array('name'=>'Autres','data'=> array(8,2,0,1,0,1,0))
        );
        $ob4 = new Highchart();
        $ob4->chart->renderTo($div); // The #id of the div where to render the chart
        $ob4->chart->type('column');
        $ob4->title->text($titre);
        $ob4->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob4->xAxis($xAxis4);
        $ob4->yAxis($yAxis4);
        $ob4->legend->enabled(true);

        $ob4->plotOptions->column(
            array(
                'dataLabels' => array(
                    'enabled' => false,
                    'rotation' => -90,
                )
            )
        );
        $ob4->series($series4);

        //return $this->render('charts/chart.html.twig', ['chart'=>$ob4, 'div'=>$div]);
        return $ob4;
    }


    /**
     * @Route("/graph20", name="graph20")
     */
    function graph20() {

        //  column 3D
        $titre = $this->translator->trans('graph20');
        $div = 'graph20';
        $data = $xData = $yData = $series = array();
        $t = $this->getDoctrine()->getRepository(Convention::class)->getNbreByAnnee();
        foreach ($t as $e):
            $x = $e['x'];
            $y = intval($e['y']);
            if ($x > 2007) array_push($xData, $x);
            if ($x > 2007) array_push($yData, intval($e['y']));
        endforeach;
        //dump($xData);dump($yData);die;
        //$xData = array('2017','2018','2019','2020','2021');
        $xAxis5 = array(
            'categories' => $xData,
            'labels' => array(
                'skew3d' => true,
                'style' => array('fontSize' => '13px')
            ),
            'reversed' => true
        );
        $yAxis5 = array(
            'min' => 0,
            'title' => array(
                'text' => "",
                'useHTML' => 'Highcharts.hasBidiBug'
            ),
            'opposite' => true

        );
        //$data = array(400,448.3,461.5,441.5,466);
        $series5 = array(
            array('name'=>'عدد الإتفاقيات','data'=> $yData)
        );
        $ob5 = new Highchart();
        $ob5->chart->renderTo($div); // The #id of the div where to render the chart
        $ob5->chart->type('column');
        $ob5->chart->options3d(
            array(
                'enabled' => true,
                'alpha' => 10,
                'beta' => 25,
                'depth' => 70
            )
        );
        $ob5->title->text($titre);
        $ob5->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob5->xAxis($xAxis5);
        $ob5->yAxis($yAxis5);
        $ob5->legend->enabled(false);

        $ob5->plotOptions->column(
            array(
                'depth' => 25,
//                'dataLabels' => array(
//                    'enabled' => true,
//                )
            )
        );
        $ob5->plotOptions->series(
            array(
                'dataLabels' => array(
                    'enabled' => true,
                )));
        $ob5->series($series5);
        $ob5->tooltip->useHTML(true);
        //return $ob5;
        return $this->render('charts/chart.html.twig', ['chart'=>$ob5, 'div'=>$div]);
    }

    /**
     * @Route("/graph30", name="graph30")
     */
    function graph30(Request $request = null)    {
        $titre = "توزيع الإتفاقيات حسب القطاع";
        $titre = $this->translator->trans('graph30');
        $div = 'graph30';
        $ob3 = new Highchart();
        $ob3->chart->renderTo($div);
        $ob3->title->text($titre);
        $ob3->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        //$ob3->subtitle->text('سنة : '.$annee);
        $ob3->plotOptions->pie(array(
            'size' => '80%','allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'     => array(
                'align' => 'right',
                'enabled' => true,
                'format' => '{point.name}: % {point.percentage:.1f}',
                'useHTML' => true
            ),
            'showInLegend'  => false
        ));
        $data = $xData = $yData = $series = array();
        $t = $this->getDoctrine()->getRepository(Convention::class)->getNbreByBranche();
        foreach ($t as $e):
            $x = $e['x'];
            array_push($xData, $x);
            array_push($yData, intval($e['y']));
            //if (intval($e['y']) > 10.01)
                $data[] = array($x, intval($e['y']));
        endforeach;
        $ob3->series(array(array('type' => 'pie','name' => "عدد الإتفاقيات", 'data' => $data)));
        $ob3->tooltip->headerFormat('<span style="font-size:11px">{series.name}</span><br>');
        $ob3->tooltip->pointFormat('<span style="text-align: left">{point.name}</span>: <b>{point.y:.0f}</b><br/>');
        $ob3->tooltip->useHTML(true);

        if (($request !== null) and ($request->query->get('chart'))) return $this->render('charts/chart.html.twig', ['chart'=>$ob3, 'div'=>$div]);
        return $ob3;
    }


    /**
     * @Route("/graph31", name="graph31")
     */
    function graph31(Request $request = null)    {

        $titre = "توزيع الإتفاقيات حسب الوضعية";
        $titre = $this->translator->trans('graph31');
        $div = 'graph31';
        $ob3 = new Highchart();
        $ob3->chart->renderTo($div);
        $ob3->title->text($titre);
        $ob3->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob3->plotOptions->pie(array(
            'size' => '80%','allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'     => array(
                'align' => 'right',
                'enabled' => true,
                'format' => '{point.name}: % {point.percentage:.1f}',
                'useHTML' => true
            ),
            'showInLegend'  => false
        ));
        $data = $xData = $yData = $series = array();
        $t = $this->getDoctrine()->getRepository(Convention::class)->getNbreByStade();

        foreach ($t as $e):
            $x = $e['x'];
            array_push($xData, $x);
            array_push($yData, intval($e['y']));
            //if (intval($e['y']) > 10.01) $data[] = array($x, intval($e['y']));
            $data[] = array($x, intval($e['y']));
        endforeach;
        //dd($data);
        $ob3->series(array(array('type' => 'pie','name' => "عدد الإتفاقيات", 'data' => $data)));
        $ob3->tooltip->headerFormat('<span style="font-size:11px">{series.name}</span><br>');
        $ob3->tooltip->pointFormat('<span style="text-align: left">{point.name}</span>: <b>{point.y:.0f}</b><br/>');
        $ob3->tooltip->useHTML(true);

        if (($request !== null) and ($request->query->get('chart')))  return $this->render('charts/chart.html.twig', ['chart'=>$ob3, 'div'=>$div]);
        return $ob3;
    }

    /**
     * @Route("/graph32", name="graph32")
     */
    function graph32(Request $request = null)    {

        $titre = $this->translator->trans('graph32');
        $div = 'graph32';
        $ob3 = new Highchart();
        $ob3->chart->renderTo($div);
        $ob3->title->text($titre);
        $ob3->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob3->plotOptions->pie(array(
            'size' => '80%','allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'     => array(
                'align' => 'right',
                'enabled' => true,
                'format' => '{point.name}: % {point.percentage:.1f}',
                'useHTML' => true
            ),
            'showInLegend'  => false
        ));
        $data = $xData = $yData = $series = array();
        $t = $this->getDoctrine()->getRepository(Convention::class)->getNbreByLocalisation();
        //dd($t);
        foreach ($t as $e):
            $x = $e['x'];
            array_push($xData, $x);
            array_push($yData, intval($e['y']));
            //if (intval($e['y']) > 10.01) $data[] = array($x, intval($e['y']));
            $data[] = array($x, intval($e['y']));
        endforeach;
        //dd($data);
        $ob3->series(array(array('type' => 'pie','name' => "عدد الإتفاقيات", 'data' => $data)));
        $ob3->tooltip->headerFormat('<span style="font-size:11px">{series.name}</span><br>');
        $ob3->tooltip->pointFormat('<span style="text-align: left">{point.name}</span>: <b>{point.y:.0f}</b><br/>');
        $ob3->tooltip->useHTML(true);

        if (($request !== null) and ($request->query->get('chart'))) return $this->render('charts/chart.html.twig', ['chart'=>$ob3, 'div'=>$div]);
        return $ob3;
    }

    /**
     * @Route("/graph40", name="graph40")
     */
    function graph40()    {

        $titre = 'Répartition des investissements';
        $div = 'graph40';
        // pie drilldown
        $ob2 = new Highchart();
        $ob2->chart->renderTo($div);
        $ob2->chart->type('pie');
        $ob2->title->text($titre);
        $ob2->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob2->plotOptions->series(
            array(
                'dataLabels' => array(
                    'enabled' => true,
                    'format' => '{point.name}: {point.y:.1f}%'
                )));

        $ob2->tooltip->headerFormat('<span style="font-size:11px">{series.name}</span><br>');
        $ob2->tooltip->pointFormat('<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> du total<br/>');

        $data = array(
            array(
                'name' => 'Taroudannt',
                'y' => 18.73,
                'drilldown' => 'Taroudannt',
                'visible' => true
            ),
            array(
                'name' => 'Agadir',
                'y' => 53.61,
                'drilldown' => 'Agadir',
                'visible' => true
            ),
            array('Inezgane', 45.0),
            array('Tiznit', 1.5)
        );
        $ob2->series(
            array(
                array(
                    'name' => 'Investissement',
                    'colorByPoint' => true,
                    'data' => $data
                )
            )
        );

        $drilldown = array(
            array(
                'name' => 'Agadir',
                'id' => 'Agadir',
                'data' => array(
                    array("Tourisme", 26.61),
                    array("Agriculture", 16.96),
                    array("Commerce", 6.4),
                    array("Infrastructure", 3.55),
                    array("Autres", 0.09)
                )
            ),
            array(
                'name' => 'Taroudannt',
                'id' => 'Taroudannt',
                'data' => array(
                    array("Tourisme", 7.73),
                    array("Agriculture", 1.13),
                    array("Infrastructure", 0.45),
                    array("Autres", 0.26)
                )
            ),
        );
        $ob2->drilldown->series($drilldown);

        return $this->render('charts/chart.html.twig', ['chart'=>$ob2, 'div'=>$div]);
    }

    /**
     * @Route("/graph50", name="graph50")
     */
    function graph50()    {

        $titre = 'عدد الإتفاقيات حسب المجال';
        $div = 'graph50';
        $data = $xData = $yData = $series = array();
        $t = $this->getDoctrine()->getRepository(Convention::class)->getNbreBySecteur();
        foreach ($t as $e):
            $x = $e['x'];
            $y = intval($e['y']);
            array_push($xData, $x);
            array_push($yData, intval($e['y']));
        endforeach;
        // graph 6 stacked bar
        $xAxis6 = array(
            'categories' => $xData,
            //'reversed' => true
            'labels' => [
                'style' => ['color'=> '#333666', 'fontSize' => '13px','fontWeight' => 'bold']
            ]
        );
        $yAxis6 = array(
            'min' => 0,
            'title' => array(
                'text' => null
            ),
            //'reversed' => true
        );
        $series6 = array(
            array('name'=>'عدد الإتفاقيات','data'=> $yData)
        );
        $ob6 = new Highchart();
        $ob6->chart->renderTo($div); // The #id of the div where to render the chart
        $ob6->chart->type('bar');
        $ob6->legend->reversed(true);
        $ob6->legend->enabled(false);
        $ob6->title->text($titre);
        $ob6->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob6->xAxis($xAxis6);
        $ob6->yAxis($yAxis6);
        $ob6->plotOptions->series(
            array(
                'stacking' => 'normal',
                'dataLabels' => array(
                    'enabled' => true
                )
            )
        );
        $ob6->series($series6);
        return $this->render('charts/chart.html.twig', ['chart'=>$ob6, 'div'=>$div]);
    }


    /**
     * @Route("/graph53", name="graph53")
     * @param int $annee
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function graph53($annee = 2021)    {
        if (!isset($annee)) $annee = 2021;
        $titre = 'عدد الخبرات حسب الموضوع';
        $div = 'graph53a'.$annee;
        $data = $xData = $yData = $series = array();
        $t = $this->getDoctrine()->getRepository(Convention::class)->getNbreByCategorieDossierByAnnee($annee);
        foreach ($t as $e):
            $x = $e['x'];
            $y = intval($e['y']);
            array_push($xData, $x);
            array_push($yData, intval($e['y']));
        endforeach;
        // graph 6 stacked bar
        $xAxis6 = array(
            'categories' => $xData,
            //'reversed' => true
            'labels' => [
                'style' => ['color'=> '#333666', 'fontSize' => '13px','fontWeight' => 'bold']
            ]
        );
        $yAxis6 = array(
            'min' => 0,
            'title' => array(
                'text' => null
            ),
            //'reversed' => true
        );
        $series6 = array(
            array('name'=>'عدد الخبرات','data'=> $yData)
        );
        $ob6 = new Highchart();
        $ob6->chart->renderTo($div); // The #id of the div where to render the chart
        $ob6->chart->type('bar');
        $ob6->legend->reversed(true);
        $ob6->legend->enabled(false);
        $ob6->title->text($titre);
        $ob6->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob6->subtitle->text('سنة : '.$annee);
        $ob6->xAxis($xAxis6);
        $ob6->yAxis($yAxis6);
        $ob6->plotOptions->series(
            array(
                'stacking' => 'normal',
                'dataLabels' => array(
                    'enabled' => true
                )
            )
        );

        $ob6->series($series6);
        return $this->render('charts/chart.html.twig', ['chart'=>$ob6, 'div'=>$div]);
    }

    /**
     * @Route("/graph53a", name="graph53a")
     * @param int $annee
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function graph53a($annee = 2021)    {
        if (!isset($annee)) $annee = 2021;
        $titre = 'عدد الخبرات حسب الموضوع';
        $div = 'graph53a'.$annee;
        $data = $xData = $yData = $series = array();
        $t = $this->getDoctrine()->getRepository(Convention::class)->getNbreByCategorieDossierByAnnee($annee);
        foreach ($t as $e):
            $x = $e['x'];
            $y = intval($e['y']);
            array_push($xData, $x);
            array_push($yData, intval($e['y']));
        endforeach;
        // graph 6 stacked bar
        $xAxis6 = array(
            'categories' => $xData,
            //'reversed' => true
            'labels' => [
                'style' => ['color'=> '#333666', 'fontSize' => '13px','fontWeight' => 'bold']
            ]
        );
        $yAxis6 = array(
            'min' => 0,
            'title' => array(
                'text' => null
            ),
            //'reversed' => true
        );
        $series6 = array(
            array('name'=>'عدد الخبرات','data'=> $yData)
        );
        $ob6 = new Highchart();
        $ob6->chart->renderTo($div); // The #id of the div where to render the chart
        $ob6->chart->type('bar');
        $ob6->legend->reversed(true);
        $ob6->legend->enabled(false);
        $ob6->title->text($titre);
        $ob6->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob6->subtitle->text('سنة : '.$annee);
        $ob6->xAxis($xAxis6);
        $ob6->yAxis($yAxis6);
        $ob6->plotOptions->series(
            array(
                'stacking' => 'normal',
                'dataLabels' => array(
                    'enabled' => true
                )
            )
        );

        $ob6->series($series6);
        return $ob6;
    }

    /**
     * @Route("/graph60", name="graph60")
     */
    function graph60()    {

        $titre = 'Les investissements par source de financement';
        $div = 'graph60';
// graph 4 column empile une sur une autre
        $xAxis4 = array(
            'categories' => array('Agadir','Inezgane','Tiznit','Tata','Taroudant','Chtouka'),
            'type' => 'category',
            'labels' => array(
//                'rotation' => -45,
//                'align' => 'left',
                'style' => array('fontSize' => '13px')
            ),
            'reversed' => true
        );

        $yAxis4 = array(
            'min' => 0,
            'title' => array(
                'text' => 'Les investissements',
                'useHTML' => 'Highcharts.hasBidiBug'
            ),
            'opposite' => true,
            'stackLabels' => array(
                'enabled' => true,
                'style' => array('fontWeight' => 'bold',)
            )
        );

        $series4 = array(
            array('name'=>'Fonds privés','data'=> array(252000,160000,76000,52000,66000,66000)),
            array('name'=>'Autres publics','data'=> array(2102000,1150000,456000,178000,186000,160000)),
            array('name'=>'Région','data'=> array(282000,190000,256000,148000,156000,190000))
        );
        $ob4 = new Highchart();
        $ob4->chart->renderTo($div); // The #id of the div where to render the chart
        $ob4->chart->type('column');
        $ob4->title->text($titre);
        $ob4->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob4->xAxis($xAxis4);
        $ob4->yAxis($yAxis4);
        $ob4->legend->enabled(true);

        $ob4->plotOptions->column(
            array(
                'stacking' => 'normal',
                'dataLabels' => array(
                    'enabled' => false,
                    'rotation' => -90,
                )
            )
        );
        $ob4->series($series4);
        return $this->render('charts/chart.html.twig', ['chart'=>$ob4, 'div'=>$div]);
    }

    /**
     * @Route("/graph70", name="graph70")
     */
    function graph70()    {

        $titre = 'Capacité hôtelière par catégorie';
        $div = 'graph70';
        $ob3 = new Highchart();
        $ob3->chart->renderTo($div);
        $ob3->title->text($titre);
        $ob3->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob3->plotOptions->pie(array(
            'size' => '80%','allowPointSelect'  => true,
            'cursor'    => 'pointer','dataLabels'     => array('align' => 'left','enabled' => true,'format' => '{point.name}: {point.percentage:.1f}%'
            ),'showInLegend'  => false
        ));
        $data = array(
            array('Hôtel 5*', 2388),
            array('Hôtel 4*', 4170),
            array('Hôtel 3*', 993),
            array('Hôtel 2*', 859),
            array('Hôtel 1*', 175),
            array('Résidences', 1497),
            array('Village VT', 3061),
        );
        $ob3->credits->enabled(true);
        $ob3->credits->text('CRT, 2015');
        //$ob3->credits->href('http://www.ssepdr.ma');
        $ob3->series(array(array('type' => 'pie','name' => "Nombre", 'data' => $data)));
        $ob3->tooltip->headerFormat('<span style="font-size:11px">{series.name}</span><br>');
        $ob3->tooltip->pointFormat('<span style="text-align: right">{point.name}</span>: <b>{point.y}</b> MDH<br/>');
        $ob3->tooltip->useHTML(true);
        return $this->render('charts/chart.html.twig', ['chart'=>$ob3, 'div'=>$div]);
    }

    /**
     * @Route("/graph80", name="graph80")
     */
    function graph80()    {
        $titre = 'Dépenses de fonctionnement';
        $div = 'graph80';
        // graph 4 column
        $xAxis4 = array(
            'categories' => array('2015','2016','2017'),
            'type' => 'category',
            'labels' => array(
//                'rotation' => -45,
//                'align' => 'left',
                'style' => array('fontSize' => '13px')
            ),
            'reversed' => true
        );

        $yAxis4 = array(
            'min' => 0,
            'title' => array(
                'text' => 'Les dépenses',
                'useHTML' => 'Highcharts.hasBidiBug'
            ),
            'opposite' => true,
            'stackLabels' => array(
                'enabled' => true,
                'style' => array('fontWeight' => 'bold',)
            )
        );

        $series4 = array(
            array('name'=>'Activités du Conseil','data'=> array(5171512,5922070,9356109)),
            array('name'=>'Gestion du personnel','data'=> array(3831062,3881108,11081920)),
            array('name'=>'Gestion administrative','data'=> array(4097601,5105903,5396817)),
            array('name'=>'Remboursement de la dette','data'=> array(6639918,6152512,5406685)),
            array('name'=>'Engagements financiers','data'=> array(10898152,2650000,11820000)),
            array('name'=>'Diverses dépenses','data'=> array(506928,1411022,2106767)),
        );
        $ob4 = new Highchart();
        $ob4->chart->renderTo($div); // The #id of the div where to render the chart
        $ob4->chart->type('column');
        $ob4->title->text($titre);
        $ob4->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob4->xAxis($xAxis4);
        $ob4->yAxis($yAxis4);
        $ob4->legend->enabled(true);

        $ob4->plotOptions->column(
            array(
                'stacking' => 'normal',
                'dataLabels' => array(
                    'enabled' => false,
                    'rotation' => -90,
                )
            )
        );
        $ob4->series($series4);
        return $this->render('charts/chart.html.twig', ['chart'=>$ob4, 'div'=>$div]);
    }

    /**
     * @Route("/graph81", name="graph81")
     */
    function graph81()    {
        $titre = 'تطور عدد الخبرات المنجزة';
        $div = 'graph81';
        // graph 4 column
        $data = $xData = $yData = $inscrisData = $realisesData = $nonrealisesData = $nonrealises2Data = $series = array();
        $inscris = $this->getDoctrine()->getRepository(Convention::class)->getNbreInscrisByAnnee();
        foreach ($inscris as $e):
            $x = $e['x'];
            $y = intval($e['y']);
            array_push($xData, $x);
            array_push($inscrisData, intval($e['y']));
        endforeach;
        $nonRealises = $this->getDoctrine()->getRepository(Convention::class)->getNbreNonRealisesByAnnee();
        foreach ($nonRealises as $e):
            $x = $e['x'];
            $y = intval($e['y']);
            array_push($nonrealisesData, intval($e['y']));
            //$nonrealisesData[$x] = intval($e['y']);
        endforeach;
        $realises = $this->getDoctrine()->getRepository(Convention::class)->getNbreRealisesByAnnee();
        foreach ($realises as $e):
            $x = $e['x'];
            $y = intval($e['y']);
            array_push($realisesData, intval($e['y']));
            //$realisesData[$x] = intval($e['y']);
        endforeach;
        //dump($realises);
        //dump($inscrisData);
        //dump($realisesData);
        //dump($nonrealisesData);
        for ($i = 0; $i < count($xData); $i++) {
            $nonrealisesData2[$i] =  $inscrisData[$i] - $realisesData[$i];
            $data[$xData[$i]][1] =  $inscrisData[$i];
            $data[$xData[$i]][2] =  $realisesData[$i];
            $data[$xData[$i]][3] =  $inscrisData[$i] - $realisesData[$i];
        }
        //dd($data);
        //dd($realisesData);
        $xAxis4 = array(
            'categories' => $xData,
            'type' => 'category',
            'labels' => array(
//                'rotation' => -45,
//                'align' => 'left',
                'style' => array('fontSize' => '13px')
            ),
            'reversed' => true
        );

        $yAxis4 = array(
            'min' => 0,
            'title' => array(
                'text' => 'عدد الخبرات',
                'useHTML' => 'Highcharts.hasBidiBug'
            ),
            'opposite' => true,
            'stackLabels' => array(
                'enabled' => true,
                'style' => array('fontWeight' => 'bold',)
            )
        );
        $series4 = array(
            //array('name'=>'المسجلة','data'=> $inscrisData),
            array('name'=>'غير المنجزة','data'=> $nonrealisesData2),
            //array('name'=>' 2غير المنجزة','data'=> $nonrealises2Data),
            array('name'=>'المنجزة','data'=> $realisesData),
        );
        $ob4 = new Highchart();
        $ob4->chart->renderTo($div); // The #id of the div where to render the chart
        $ob4->chart->type('column');
        $ob4->title->text($titre);
        $ob4->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob4->xAxis($xAxis4);
        $ob4->yAxis($yAxis4);
        $ob4->legend->enabled(true);

        $ob4->plotOptions->column(
            array(
                'stacking' => 'normal',
                'dataLabels' => array(
                    'enabled' => false,
                    'rotation' => -90,
                )
            )
        );
        $ob4->series($series4);
        return [
            'chart' =>  $ob4,
            'titre' =>  $titre,
            'data'  =>  $data
        ];
        return $this->render('charts/chart.html.twig', [
            'chart'=>$ob4,
            'div'=>$div,
            'data'=>$data
        ]);
    }

    /**
     * @Route("/graph90", name="front_graph90")
     */
    public function graph90()
    {
        // demi cercle
        $titre = "نسبة التبليغات";
        $div = 'graph90';
        $data = $xData = $yData = $series = array();
//        $t = $this->getDoctrine()->getRepository(Projet::class)->getMontantInvestissementTotalByTypeObjectif();
//        foreach ($t as $e):
//            $x = $e['x'];
//            array_push($xData, $x);
//            array_push($yData, intval($e['y']/1000000));
//            $data['عدد الإتفاقيات'][] = array($x, intval($e['y']/1000000));
//        endforeach;
        $nbre1 = $this->getDoctrine()->getRepository(Convention::class)->getNbreTotalNotifies();
        $nbre2 = $this->getDoctrine()->getRepository(Convention::class)->getNbreTotalNonNotifies();
        //dump(intval($nbre2));
        //dd(intval($nbre1));
        $data['تبليغ الإتفاقيات'] =[['غير المبلغة',intval($nbre2)],['المبلغة',intval($nbre1)]];
        foreach ($data as $p => $d):
            $d2 = array_merge($d,
                array(array(
                    'name' =>  'Other',
                    'y' =>  7.61,
                    'dataLabels' => array('enabled' => false)
                )));
            $series[] = array('type' => 'pie','name' => $p,"innerSize" =>  "50%", 'data' => $d);
        endforeach;
//        dump($data);
        //  dump($series); die;

        $ob = new Highchart();
        $ob->chart->renderTo($div);  // The #id of the div where to render the chart
        $ob->chart->plotBackgroundColor(null);
        $ob->chart->plotBorderWidth(0);
        $ob->chart->plotShadow(false);
        $ob->title->text($titre);
        $ob->title->align('center');
        $ob->title->verticalAlign('middle');
        $ob->title->y(60);
        $ob->tooltip->headerFormat('<span style="font-size:11px">{series.name}</span><br>');
        $ob->tooltip->pointFormat('<span style="text-align: right">{point.name}</span>: <b>{point.y}</b><br/>');
        //$ob->tooltip->pointFormat('{series.name}: <b>{point.percentage:.1f}</b> %');
        $ob->plotOptions->pie(array(
            'dataLabels'     => array(
                'enabled' => true,
                'distance' => -50,
                'style' => array(
                    'fontWeight' => 'bold',
                    'color' => 'white'
                ),
                'useHTML' => true
            ),
            'startAngle' => -90,
            'endAngle' => 90,
            'center'  => array('50%', '75%'),
            'size' => '110%'
        ));
        $ob->series($series);

        return $this->render('charts/chart.html.twig', ['chart'=>$ob, 'div'=>$div]);
    }

    /**
     * @Route("/graph800", name="graph800")
     */
    function graph800()    {
        $titre = 'Dépenses de fonctionnement';
        $div = 'graph800';
        // graph 4 column un a cote de l autre
        $xAxis4 = array(
            'categories' => array('2015','2016','2017'),
            //'type' => 'category',
            'labels' => array(
//                'rotation' => -45,
//                'align' => 'left',
                'style' => array('fontSize' => '13px')
            ),
            'reversed' => true
        );

        $yAxis4 = array(
            'min' => 0,
            'title' => array(
                'text' => 'Les dépenses',
                'useHTML' => 'Highcharts.hasBidiBug'
            ),
            'opposite' => true,
        );

        $series4 = array(
            array('name'=>'Activités du Conseil','data'=> array(5171512,5922070,9356109)),
            array('name'=>'Gestion du personnel','data'=> array(3831062,3881108,11081920)),
            array('name'=>'Gestion administrative','data'=> array(4097601,5105903,5396817)),
            array('name'=>'Remboursement de la dette','data'=> array(6639918,6152512,5406685)),
            array('name'=>'Engagements financiers','data'=> array(10898152,2650000,11820000)),
            array('name'=>'Diverses dépenses','data'=> array(506928,1411022,2106767)),
        );
        $ob4 = new Highchart();
        $ob4->chart->renderTo($div); // The #id of the div where to render the chart
        $ob4->chart->type('column');
        $ob4->title->text($titre);
        $ob4->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob4->xAxis($xAxis4);
        $ob4->yAxis($yAxis4);
        $ob4->legend->enabled(true);

        $ob4->series($series4);
        return $this->render('charts/chart.html.twig', ['chart'=>$ob4, 'div'=>$div]);
    }

    /**
     * @Route("/{id}/graph801", name="graph801")
     */
    function graph801(Convention $convention)    {
        $titre = 'تنفيذ الإلتزامات';
        $div = 'graph801';
        // graph 4 column un a cote de l autre
        $data = $xData = $yData = $engagementsData = $contributionsData = $contributionsData2 = $series = array();
        $engagements = $this->getDoctrine()->getRepository(ConventionEngagement::class)->getMontantEngagementsByAnnee($convention);
        //dd($engagements);
        foreach ($engagements as $e):
            $x = $e['x'];
            $y = intval($e['y']);
            //array_push($xData, $x);
            $engagementsData[$x] = intval($e['y']);
            //array_push($engagementsData, intval($e['y']));
        endforeach;
        $contributions = $this->getDoctrine()->getRepository(ConventionContribution::class)->getMontantContributionsByAnnee($convention);
        foreach ($contributions as $e):
            $x = $e['x'];
            $y = intval($e['y']);
            //array_push($contributionsData, intval($e['y']));
            $contributionsData[$x] = intval($e['y']);
        endforeach;
        $xData = $convention->getEngagementsAnnees();
        //dd($xData);
        foreach ($xData as $k=>$an):
            $categories[] = $an;
            $engagements2Data[] =  (isset($engagementsData[$an])) ? $engagementsData[$an] : 0 ;
            $contributions2Data[] =  (isset($contributionsData[$an])) ? $contributionsData[$an] : 0;
        endforeach;

        /*
        dump($engagementsData);
        dump($contributionsData);
        dump($data);
        die;*/

        $xAxis4 = array(
            'categories' => $categories,
            //'type' => 'category',
            'labels' => array(
//                'rotation' => -45,
//                'align' => 'left',
                'style' => array('fontSize' => '13px')
            ),
            'reversed' => true
        );

        $yAxis4 = array(
            'min' => 0,
            'title' => array(
                'text' => 'المبالغ',
//                'useHTML' => 'Highcharts.hasBidiBug'
            ),
            'opposite' => true,
        );

        $series4 = array(
            array('name'=>'المبرمج','data'=> $engagements2Data),
            array('name'=>'المنفذ','data'=> $contributions2Data, 'color' => 'rgba(220,220,0,0.9)'),
        );
        $ob4 = new Highchart();
        $ob4->chart->renderTo($div); // The #id of the div where to render the chart
        $ob4->chart->type('column');
        $ob4->title->text($titre);
        $ob4->title->widthAdjust(-200);
        $ob4->title->style(array('color'=> '#333666', 'fontSize' => '16px','fontWeight' => 'bold'));
        $ob4->xAxis($xAxis4);
        $ob4->yAxis($yAxis4);
        //$ob4->exporting->enabled(false);
        $ob4->legend->enabled(true);
        $ob4->legend->reversed(true);

        $ob4->series($series4);
        return $this->render('charts/chart.html.twig', ['chart'=>$ob4, 'div'=>$div]);
    }

    /**
     * @Route("/{id}/{idp}/graph801a", name="graph801a")
     * @Entity("convention", expr="repository.find(id)")
     * @Entity("partenaire", expr="repository.find(idp)")
     */
    function graph801a(Convention $convention, Partenaire $partenaire)    {
        $titre = 'تنفيذ إلتزامات'.' '.$partenaire.'';
        $div = 'graph801a'.$partenaire->getId();
        // graph 4 column un a cote de l autre
        $data = $xData = $yData = $engagementsData = $contributionsData = $contributionsData2 = $series = array();
        $engagements = $this->getDoctrine()->getRepository(ConventionEngagement::class)->getMontantEngagementsPartenaireByAnnee($convention, $partenaire);
        //dd($engagements);
        foreach ($engagements as $e):
            $x = $e['x'];
            $y = intval($e['y']);
            //array_push($xData, $x);
            $engagementsData[$x] = intval($e['y']);
            //array_push($engagementsData, intval($e['y']));
        endforeach;
        $contributions = $this->getDoctrine()->getRepository(ConventionContribution::class)->getMontantContributionsPartenaireByAnnee($convention, $partenaire);
        foreach ($contributions as $e):
            $x = $e['x'];
            $y = intval($e['y']);
            //array_push($contributionsData, intval($e['y']));
            $contributionsData[$x] = intval($e['y']);
        endforeach;
        $xData = $convention->getEngagementsAnnees();
        //dd($xData);
        foreach ($xData as $k=>$an):
            $categories[] = $an;
            $engagements2Data[] =  (isset($engagementsData[$an])) ? $engagementsData[$an] : 0 ;
            $contributions2Data[] =  (isset($contributionsData[$an])) ? $contributionsData[$an] : 0;
        endforeach;

        /*
        dump($engagementsData);
        dump($contributionsData);
        dump($data);
        die;*/

        $xAxis4 = array(
            'categories' => $categories,
            //'type' => 'category',
            'labels' => array(
//                'rotation' => -45,
//                'align' => 'left',
                'style' => array('fontSize' => '13px')
            ),
            'reversed' => true
        );

        $yAxis4 = array(
            'min' => 0,
            'title' => array(
                'text' => 'المبالغ',
//                'useHTML' => 'Highcharts.hasBidiBug'
            ),
            'opposite' => true,
        );

        $series4 = array(
            array('name'=>'المبرمج','data'=> $engagements2Data),
            array('name'=>'المنفذ','data'=> $contributions2Data, 'color' => 'rgba(220,220,0,0.9)'),
        );
        $ob4 = new Highchart();
        $ob4->chart->renderTo($div); // The #id of the div where to render the chart
        $ob4->chart->type('column');
        $ob4->title->text($titre);
        $ob4->title->widthAdjust(-200);
        $ob4->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob4->xAxis($xAxis4);
        $ob4->yAxis($yAxis4);
        $ob4->legend->enabled(true);
        $ob4->legend->reversed(true);

        $ob4->series($series4);
        return $this->render('charts/chart.html.twig', ['chart'=>$ob4, 'div'=>$div]);
    }

    /**
     * @Route("/{id}/graph802", name="graph802")
     */
    function graph802(Partenaire $partenaire)    {
        $titre = 'الإلتزامات المالية ل'.$partenaire->getName();
        $div = 'graph802';
        // graph 4 column un a cote de l autre
        $em = $this->getDoctrine()->getManager();
        //$partenaire = $em->getRepository(Partenaire::class)->find(1);
        $rows = $this->getDoctrine()->getRepository(Partenaire::class)->getParticipationAnnuelles($partenaire);
        $categories = array_keys($rows);
        $engagements2Data = $contributions2Data =[];
        foreach ($rows as $k=>$row):
            $engagements2Data[] = intval($row['engagement']);
            $contributions2Data[] = intval($row['contribution']);
        endforeach;
//dump($engagements2Data);dd($contributions2Data);
        $xAxis4 = array(
            'categories' => $categories,
            //'type' => 'category',
            'labels' => array(
//                'rotation' => -45,
//                'align' => 'left',
                'style' => array('fontSize' => '13px')
            ),
            'reversed' => true
        );

        $yAxis4 = array(
            'min' => 0,
            'title' => array(
                'text' => 'المبالغ',
//                'useHTML' => 'Highcharts.hasBidiBug'
            ),
            'opposite' => true,
        );

        $series4 = array(
            array('name'=>'المبرمج','data'=> $engagements2Data),
            array('name'=>'المنفذ','data'=> $contributions2Data, 'color' => 'rgba(220,220,0,0.9)'),
        );
        $ob4 = new Highchart();
        $ob4->chart->renderTo($div); // The #id of the div where to render the chart
        $ob4->chart->type('column');
        $ob4->title->text($titre);
        $ob4->title->widthAdjust(-200);
        $ob4->title->style(array('color'=> '#333666', 'fontSize' => '14px','fontWeight' => 'bold'));
        $ob4->xAxis($xAxis4);
        $ob4->yAxis($yAxis4);
        $ob4->legend->enabled(true);
        $ob4->legend->reversed(true);

        $ob4->series($series4);
        return $this->render('charts/chart.html.twig', ['chart'=>$ob4, 'div'=>$div]);
    }
}

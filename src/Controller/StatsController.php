<?php

namespace App\Controller;

use App\Entity\Convention;
use App\Entity\Localisation;
use App\Form\ConventionFilterType;
use App\Service\ContainerParametersHelper;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\ChartsController;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/stats")
 */
class StatsController extends AbstractController
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
     * Liste des statistiques .
     * @Route("/", name="stats")
     */
    public function index()
    {
        return $this->render('stats/index.html.twig');
    }


    /**
     * @Route("/stat10", name="stat10")
     */
    public function stat10(ChartsController $chartsController)
    {
        $chart = $chartsController->graph10();
        return $this->render('stats/stat10.html.twig', ['chart'=>$chart]);
    }

    /**
     * @Route("/stat20", name="stat20")
     */
    public function stat20(Request $request, FilterBuilderUpdaterInterface $query_builder_updater)
    {
        $form = $this->get('form.factory')->create(ConventionFilterType::class)
            ->remove('isAvenant')
            ;
        $t = $this->getDoctrine()->getRepository(Convention::class)->getNbreByAnnee();

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $filterBuilder0 = $this->getDoctrine()->getManager()
                ->getRepository(Convention::class)
                ->createQueryBuilder('c');
            $filterBuilder = $filterBuilder0
                ->select('YEAR(s.date) as x','COUNT(c.id) as y')
                ->join('c.sessionApprobation', 's')
                ->where($filterBuilder0->expr()->isNotNull('s.date'))
                ->andWhere('c.isAvenant = 0')
                ->groupBy('x')
                ->orderBy('x', 'ASC');
            $query_builder_updater->addFilterConditions($form, $filterBuilder);
            //var_dump($filterBuilder->getDql());die;
            $t = $filterBuilder->getQuery()->getResult();
        }
        //  column
        $titre = $this->translator->trans('graph20');
        $div = 'graph20';
        $data = $xData = $yData = $series = array();
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
        return $this->render('stats/stat20.html.twig', [
            'chart'=>  $ob5,
            'data' =>  $t,
            'form' =>  $form->createView()
        ]);
    }

    /**
     * @Route("/stat30", name="stat30")
     */
    public function stat30(Request $request, FilterBuilderUpdaterInterface $query_builder_updater)
    {
        $form = $this->get('form.factory')->create(ConventionFilterType::class)
            ->remove('isAvenant');
        $t = $this->getDoctrine()->getRepository(Convention::class)->getNbreByBranche();

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $filterBuilder0 = $this->getDoctrine()->getManager()
                ->getRepository(Convention::class)
                ->createQueryBuilder('c');
            $filterBuilder = $filterBuilder0
                ->select('b.name as x','COUNT(c.id) as y')
                ->leftJoin('c.secteur','s')
                ->leftJoin('s.branche', 'b')
                ->where($filterBuilder0->expr()->isNotNull('s.branche'))
                ->andWhere('c.isAvenant = 0')
                ->groupBy('x')
                ->orderBy('x', 'ASC');
            $query_builder_updater->addFilterConditions($form, $filterBuilder);
            //var_dump($filterBuilder->getDql());die;
            $t = $filterBuilder->getQuery()->getResult();
        }
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

        return $this->render('stats/stat30.html.twig', [
            'chart'=>  $ob3,
            'data' =>  $t,
            'form' =>  $form->createView()]);
    }


    /**
     * @Route("/stat31", name="stat31")
     */
    public function stat31(Request $request, FilterBuilderUpdaterInterface $query_builder_updater)
    {
        $form = $this->get('form.factory')->create(ConventionFilterType::class)
            ->remove('isAvenant');
        $t = $this->getDoctrine()->getRepository(Convention::class)->getNbreByStade();

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $filterBuilder0 = $this->getDoctrine()->getManager()
                ->getRepository(Convention::class)
                ->createQueryBuilder('c');
            $filterBuilder = $filterBuilder0
                ->select('s.name as x','COUNT(c.id) as y')
                ->leftJoin('c.stade','s')
                ->where($filterBuilder0->expr()->isNotNull('c.stade'))
                ->andWhere('c.isAvenant = 0')
                ->groupBy('x')
                ->orderBy('x', 'ASC');
            $query_builder_updater->addFilterConditions($form, $filterBuilder);
            //var_dump($filterBuilder->getDql());die;
            $t = $filterBuilder->getQuery()->getResult();
        }
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

        return $this->render('stats/stat31.html.twig', [
            'chart'=>  $ob3,
            'data' =>  $t,
            'form' =>  $form->createView()]);
    }


    /**
     * @Route("/stat32", name="stat32")
     */
    public function stat32(Request $request, FilterBuilderUpdaterInterface $query_builder_updater)
    {
        $form = $this->get('form.factory')->create(ConventionFilterType::class)
            ->remove('isAvenant');
        $t = $this->getDoctrine()->getRepository(Convention::class)->getNbreByLocalisation();

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $filterBuilder0 = $this->getDoctrine()->getManager()
                ->getRepository(Convention::class)
                ->createQueryBuilder('c');
            $filterBuilder = $filterBuilder0
                ->select('l.name as x','COUNT(c.id) as y', 'l.id')
                ->leftJoin('c.localisation','l')
                ->where($filterBuilder0->expr()->isNotNull('c.localisation'))
                ->andWhere('c.isAvenant = 0')
                ->groupBy('x','l.id')
                ->orderBy('l.id', 'ASC');
            $query_builder_updater->addFilterConditions($form, $filterBuilder);
            //var_dump($filterBuilder->getDql());die;
            $t = $filterBuilder->getQuery()->getResult();
        }
        $titre = "توزيع الإتفاقيات حسب الوضعية";
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

        return $this->render('stats/stat32.html.twig', [
            'chart'=>  $ob3,
            'data' =>  $t,
            'form' =>  $form->createView()]);
    }

    /**
     * @Route("/stat53", name="stat53")
     */
    public function stat53(ChartsController $chartsController)
    {
        $titre = 'عدد الخبرات حسب الموضوع والسنة';
        $chart2020 = $chartsController->graph53a(2020);
        $chart2021 = $chartsController->graph53a(2021);
        $chart2018 = $chartsController->graph53a(2018);
        $chart2019 = $chartsController->graph53a(2019);
        return $this->render('stats/stat53.html.twig', [
            'titre'=>$titre,
            'chart2020'=>$chart2020,
            'chart2021'=>$chart2021,
            'chart2018'=>$chart2018,
            'chart2019'=>$chart2019,
        ]);
    }


    /**
     * @Route("/stat40", name="stat40")
     */
    public function stat40(ChartsController $chartsController)
    {
        $chart = $chartsController->graph40();
        return $this->render('stats/stat40.html.twig', ['chart'=>$chart]);
    }


    /**
     * @Route("/stat50", name="stat50")
     */
    public function stat50(ChartsController $chartsController)
    {
        $chart = $chartsController->graph50();
        return $this->render('stats/stat50.html.twig', ['chart'=>$chart]);
    }


    /**
     * @Route("/stat60", name="stat60")
     */
    public function stat60(ChartsController $chartsController)
    {
        $chart = $chartsController->graph60();
        return $this->render('stats/stat60.html.twig', ['chart'=>$chart]);
    }


    /**
     * @Route("/stat70", name="stat70")
     */
    public function stat70(ChartsController $chartsController)
    {
        $chart = $chartsController->graph70();
        return $this->render('stats/stat00.html.twig', ['chart'=>$chart[0],'titre'=>$chart[1]]);
    }


    /**
     * @Route("/stat80", name="stat80")
     */
    public function stat80(ChartsController $chartsController)
    {
        $chart = $chartsController->graph80();
        return $this->render('stats/stat00.html.twig', ['chart'=>$chart[0],'titre'=>$chart[1]]);
    }

    /**
     * @Route("/stat81", name="stat81")
     */
    public function stat81(ChartsController $chartsController)
    {
        $chart = $chartsController->graph81();
        //dd($chart);
        return $this->render('stats/stat81.html.twig', [
            'chart'=>$chart["chart"],
            'titre'=>$chart["titre"],
            'data'=>$chart["data"]
        ]);
    }
}

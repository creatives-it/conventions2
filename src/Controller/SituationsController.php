<?php

namespace App\Controller;

use App\Entity\Convention;
use App\Entity\Partenaire;
use App\Form\ConventionFilterType;
use App\Service\ContainerParametersHelper;
use App\Service\Settings;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Situations controller.
 *
 * @Route("/situations")
 */
class SituationsController extends AbstractController
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
     * Liste des situations .
     * @Route("/", name="situations")
     */
    public function indexAction(Request $request)
    {
        return $this->render('situations/list.html.twig', array());
    }

    /**
     * @Route("/a1", name="situation_a1")
     */
    public function a1(Request $request, Settings $settings)
    {
        $em = $this->getDoctrine()->getManager();

        $conventions = $em->getRepository(Convention::class)->findBy(
            [],
            ['numero'=>'ASC']
        );

        return $this->render('situations/a1.html.twig', [
            'conventions' => $conventions,
        ]);
    }

    /**
     * @Route("/a2", name="situation_a2")
     */
    public function a2(Request $request, Settings $settings)
    {
        $em = $this->getDoctrine()->getManager();
        $delaiMin = 10;
        $conventions = $em->getRepository(Convention::class)->getListAttenteVisa($delaiMin);

        return $this->render('situations/a2.html.twig', [
            'conventions' => $conventions,
            'delaiMin' => $delaiMin
        ]);
    }

    /**
     * @Route("/a3", name="situation_a3")
     */
    public function a3(Request $request, Settings $settings)
    {
        $em = $this->getDoctrine()->getManager();
        $delaiMin = 15;
        $conventions = $em->getRepository(Convention::class)->getListAttenteSignaturePartenaires($delaiMin);

        return $this->render('situations/a3.html.twig', [
            'conventions' => $conventions,
            'delaiMin' => $delaiMin
        ]);
    }

    /**
     * @Route("/b1", name="situation_b1")
     */
    public function b1(Request $request, Settings $settings)
    {
        $em = $this->getDoctrine()->getManager();
        $partenaire = $em->getRepository(Partenaire::class)->find(1);
        $rows = $em->getRepository(Partenaire::class)->getParticipationAnnuelles($partenaire);
//dd($rows);
        return $this->render('situations/b1.html.twig', [
            'rows' => $rows,
        ]);
    }


    /**
     * @Route("/ax1", name="situation_ax1")
     * @throws Exception
     */
    public function ax1(Request $request, FilterBuilderUpdaterInterface $query_builder_updater) {
        $titre = $this->translator->trans('situationAx1');
        $em = $this->getDoctrine()->getManager();
        $form = $this->get('form.factory')->create(ConventionFilterType::class);
        $conventions = $em->getRepository(Convention::class)->findBy(
            [],
            ['numero'=>'ASC']
        );
        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $filterBuilder = $this->getDoctrine()->getManager()
                ->getRepository(Convention::class)
                ->createQueryBuilder('e')
                ->orderBy('e.numero', 'ASC');
            $query_builder_updater->addFilterConditions($form, $filterBuilder);
            //var_dump($filterBuilder->getDql());die;
            $conventions = $filterBuilder->getQuery()->getResult();

            $spreadsheet = new Spreadsheet();

            $styleNormal = [
                'font' => ['bold' => false,'size' => 11],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,],],
            ];
            $styleNormalImpaire = [
                'font' => ['bold' => false,'size' => 11],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,],],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'ECF8E7',
                    ],
                    'endColor' => [
                        'argb' => 'ECF8E7',
                    ],
                ],
            ];
            $styleDroite = [
                'font' => ['bold' => false,'size' => 11],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,],],
            ];
            $styleEntete = [
                'font' => ['bold' => true,'size' => 12],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,],],
            ];
            $styleTitre = [
                'font' => ['bold' => true,'size' => 22],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,],],
            ];
            $styleSousTitre = [
                'font' => ['bold' => true,'size' => 12],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,],],
            ];
            $styleTitreColonne = [
                'font' => ['bold' => true,'size' => 12],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,],],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'a7d0a7',
                    ],
                    'endColor' => [
                        'argb' => 'a7d0a7',
                    ],
                ],
            ];
            $styleTotal = [
                'font' => ['bold' => true,'size' => 12],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,],],
            ];
            $styleTotalGeneral = [
                'font' => ['bold' => true,'size' => 13],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,],],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'CCCCCC',
                    ],
                    'endColor' => [
                        'argb' => 'CCCCCC',
                    ],
                ],
            ];

            // @var $sheet Worksheet
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setRightToLeft(true);
            // FORMAT PAGE
            $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);// paysage
            $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToWidth(1);// 1 page en largeur
            $sheet->getPageSetup()->setFitToHeight(0); // pages en longueur illimitees
            $sheet->getHeaderFooter()
                ->setEvenFooter('&R&D&C&B' . $titre . '&L &P / &N')
                ->setOddFooter('&L&D&C&B' . $titre . '&R &P / &N');
            // NOM FEUILLE
            //$sheet->setTitle("Ax1");

            // image
            $IMG = 'http://crtta.conventions.creatives.ma/img/entete.png';
            $row_num = 1;
            if (isset($IMG) && !empty($IMG)) {
                $imageType = "png";
                if (strpos($IMG, ".png") === false) { $imageType = "jpg";        }

                $drawing = new MemoryDrawing();
                $sheet->getRowDimension($row_num)->setRowHeight(80);
                $sheet->mergeCells('A' . $row_num . ':H' . $row_num);
                $gdImage = ($imageType == 'png') ? imagecreatefrompng($IMG) : imagecreatefromjpeg($IMG);
                $drawing->setName('المحكمة الادارية بالرباط');
                $drawing->setDescription('مكتب الخبرة');
                $drawing->setResizeProportional(false);
                $drawing->setImageResource($gdImage);
                $drawing->setRenderingFunction(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::RENDERING_JPEG);
                $drawing->setMimeType(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_DEFAULT);
                $drawing->setWidth(370);
                $drawing->setHeight(100);
                $drawing->setOffsetX(5); $drawing->setOffsetY(5);
                $drawing->setCoordinates('A1');
                $drawing->setWorksheet($sheet);
            }

            // TITRE
            $sheet->getRowDimension(3)->setRowHeight(30);
            $sheet->setCellValue('A2',$titre);
            $sheet->getStyle('A2')->applyFromArray($styleTitre);
            $sheet->mergeCells('A2:P2');

            // SOUS TITRES
            $sheet->getStyle('A3:P3')->applyFromArray($styleSousTitre);
            $sheet->getStyle('A4:P4')->applyFromArray($styleSousTitre);

            $data = $form->getData();

            $stades = [];
            if (!$data['stade']->isEmpty()) {
                foreach ($data['stade'] as $s){
                    $stades[] = $s->getName();
                }
                $sheet->setCellValue('C3',"الوضعية :");
                $sheet->setCellValue('D3',implode(' ، ',$stades));
                $sheet->getStyle('D3')->getAlignment()->setHorizontal('right');
            }
            $typeConventions = [];
            if (!$data['typeConvention']->isEmpty()) {
                foreach ($data['typeConvention'] as $s){
                    $typeConventions[] = $s->getName();
                }
                $sheet->setCellValue('E3',"النوع :");
                $sheet->setCellValue('F3',implode(' ، ',$typeConventions));
                $sheet->getStyle('F3')->getAlignment()->setHorizontal('right');
            }
            $secteurs = [];
            if (!$data['secteur']->isEmpty()) {
                foreach ($data['secteur'] as $s){
                    $secteurs[] = $s->getName();
                }
                $sheet->setCellValue('G3',"المجال :");
                $sheet->setCellValue('H3',implode(' ، ',$secteurs));
                $sheet->getStyle('H3')->getAlignment()->setHorizontal('right');
            }
            $localisations = [];
            if (!$data['localisation']->isEmpty()) {
                foreach ($data['localisation'] as $s){
                    $localisations[] = $s->getName();
                }
                $sheet->setCellValue('G4',"التوطين :");
                $sheet->setCellValue('H4',implode(' ، ',$localisations));
                $sheet->getStyle('H4')->getAlignment()->setHorizontal('right');
            }
            $maitreOuvrages = [];
            if (!$data['maitreOuvrage']->isEmpty()) {
                foreach ($data['maitreOuvrage'] as $s){
                    $maitreOuvrages[] = $s->getName();
                }
                $sheet->setCellValue('E4',"المكلف بالتنفيذ :");
                $sheet->setCellValue('F4',implode(' ، ',$maitreOuvrages));
                $sheet->getStyle('F4')->getAlignment()->setHorizontal('right');
            }
            $entiteSuiviExecutions = [];
            if (!$data['entiteSuiviExecution']->isEmpty()) {
                foreach ($data['entiteSuiviExecution'] as $s){
                    $entiteSuiviExecutions[] = $s->getName();
                }
                $sheet->setCellValue('C4',"القسم المكلف بالتتبع :");
                $sheet->setCellValue('D4',implode(' ، ',$entiteSuiviExecutions));
                $sheet->getStyle('D4')->getAlignment()->setHorizontal('right');
            }

            $sheet->setCellValue('M4',"عدد الإتفاقيات :");
            $sheet->setCellValue('N4',count($conventions));
            $sheet->getStyle('N4')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('O4',"بتاريخ :");
            $sheet->setCellValue('P4',(new \Datetime())->format('d/m/Y'));
            $sheet->getStyle('P4')->getAlignment()->setHorizontal('center');

            $sheet->getRowDimension(4)->setRowHeight(25);
            // fin sous titres

            // LIGNE DES TITRES DES COLONNES
            $row = 5;
            //'Type Convention''Statut Convention''Vise'
            $sheet->setCellValue('A'.$row, $this->translator->trans('Numero An'));
            $sheet->setCellValue('B'.$row, $this->translator->trans('Numero'));
            $sheet->setCellValue('C'.$row, $this->translator->trans('Project'));
            $sheet->setCellValue('D'.$row, $this->translator->trans('Secteur'));
            $sheet->setCellValue('E'.$row, $this->translator->trans('Localisation'));
            $sheet->setCellValue('F'.$row, $this->translator->trans('Entite Suivi Execution'));
            $sheet->setCellValue('G'.$row, $this->translator->trans('Partie Contractantes'));
            $sheet->setCellValue('H'.$row, $this->translator->trans('Numero Decision'));
            $sheet->setCellValue('I'.$row, $this->translator->trans('Date Session'));
            $sheet->setCellValue('J'.$row, $this->translator->trans('Date Visa'));
            $sheet->setCellValue('K'.$row, $this->translator->trans('Is Signee'));
            $sheet->setCellValue('L'.$row, $this->translator->trans('Organes Suivi'));
            $sheet->setCellValue('M'.$row, $this->translator->trans('Maitre Ouvrage'));
            $sheet->setCellValue('N'.$row, $this->translator->trans('Stade'));
            $sheet->setCellValue('O'.$row, $this->translator->trans('Montant Convention'));
            $sheet->setCellValue('P'.$row, $this->translator->trans('Contribution financiere Region dans le projet'));
            $sheet->setCellValue('Q'.$row, $this->translator->trans('Engagements programmes').' '.'2019');
            $sheet->setCellValue('R'.$row, $this->translator->trans('Engagements realises').' '.'2019');
            $sheet->setCellValue('S'.$row, $this->translator->trans('Engagements programmes').' '.'2020');
            $sheet->setCellValue('T'.$row, $this->translator->trans('Engagements realises').' '.'2020');
            $sheet->setCellValue('U'.$row, $this->translator->trans('Engagements programmes').' '.'2021');
            $sheet->setCellValue('V'.$row, $this->translator->trans('Engagements realises').' '.'2021');
            $sheet->setCellValue('W'.$row, $this->translator->trans('Engagements programmes').' '.'2022');
            $sheet->setCellValue('X'.$row, $this->translator->trans('Engagements realises').' '.'2022');
            $sheet->setCellValue('Y'.$row, $this->translator->trans('Engagements programmes').' '.'2023');
            $sheet->setCellValue('Z'.$row, $this->translator->trans('Engagements realises').' '.'2023');
            $sheet->setCellValue('AA'.$row, $this->translator->trans('Observations'));

            $sheet->getStyle('A'.$row.':AA'.$row)->applyFromArray($styleTitreColonne);

            $sheet->getRowDimension($row)->setRowHeight(35);

            $row++;
            foreach ($conventions as $convention):
                $sheet->insertNewRowBefore($row+1, 1);
                /* @var $convention Convention */
                $sheet->setCellValue('A'.$row, $convention->getNumeroAn());
                $sheet->setCellValue('B'.$row, $convention->getNumero());
                $sheet->setCellValue('C'.$row, $convention->getObjetConvention());
                $sheet->setCellValue('D'.$row, $convention->getSecteur());
                $sheet->setCellValue('E'.$row, $convention->getLocalisation());
                $sheet->setCellValue('F'.$row, $convention->getEntiteSuiviExecution());
                $sheet->setCellValue('G'.$row, $convention->getPartieContractantesString());
                $sheet->setCellValue('H'.$row, $convention->getNumeroDecision());
                $sheet->setCellValue('I'.$row, (null !== $convention->getDateSessionApprobation()) ? $convention->getDateSessionApprobation()->format('d/m/Y') : '');
                $sheet->setCellValue('J'.$row, (null !== $convention->getDateVisa()) ? $convention->getDateVisa()->format('d/m/Y') : '');
                $sheet->setCellValue('K'.$row, ($convention->getIsSignee()) ? 'نعم': 'لا');
                $sheet->setCellValue('L'.$row, $convention->getOrganesSuivi());
                $sheet->setCellValue('M'.$row, $convention->getMaitreOuvrage());
                $sheet->setCellValue('N'.$row, $convention->getStade());
                $sheet->setCellValue('O'.$row, $convention->getMontantConvention());
                $sheet->setCellValue('P'.$row, $convention->getMontantEngagementGlobalRegion());

                $sheet->setCellValue('Q'.$row, $convention->getMontantEngagementAnnuelRegion(2019));
                $sheet->setCellValue('S'.$row, $convention->getMontantEngagementAnnuelRegion(2020));
                $sheet->setCellValue('U'.$row, $convention->getMontantEngagementAnnuelRegion(2021));
                $sheet->setCellValue('W'.$row, $convention->getMontantEngagementAnnuelRegion(2022));
                $sheet->setCellValue('Y'.$row, $convention->getMontantEngagementAnnuelRegion(2023));

                $sheet->setCellValue('R'.$row, $convention->getMontantContributionAnnuelRegion(2019));
                $sheet->setCellValue('T'.$row, $convention->getMontantContributionAnnuelRegion(2020));
                $sheet->setCellValue('V'.$row, $convention->getMontantContributionAnnuelRegion(2021));
                $sheet->setCellValue('X'.$row, $convention->getMontantContributionAnnuelRegion(2022));
                $sheet->setCellValue('Z'.$row, $convention->getMontantContributionAnnuelRegion(2023));

                $sheet->setCellValue('AA'.$row, $convention->getObservations());

                if ($row % 2 == 0) {
                    $sheet->getStyle('A'.$row.':AA'.$row)->applyFromArray($styleNormal);
                } else {
                    $sheet->getStyle('A'.$row.':AA'.$row)->applyFromArray($styleNormalImpaire);
                }
                //$sheet->getRowDimension($row)->setRowHeight(25);

                $row++;
            endforeach;
            $nblignes = $row-1;
            //$sheet->getStyle('A3:P'.$nblignes)->applyFromArray($styleNormal);

            // alignement
            $sheet->getStyle('A3:A'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('B3:B'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('C3:C'.$nblignes)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('C3:C'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('D3:D'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('E3:E'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('F3:F'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('G3:G'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('H3:H'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('I3:I'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('J3:J'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('K3:K'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('L3:L'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('M3:M'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('N3:N'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('O3:O'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('P3:P'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('Q3:Q'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('R3:R'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('S3:S'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('T3:T'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('U3:U'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('V3:V'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('W3:W'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('X3:X'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('Y3:Y'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('Z3:Z'.$nblignes)->getAlignment()->setWrapText(true);
            $sheet->getStyle('AA3:AA'.$nblignes)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('AA3:AA'.$nblignes)->getAlignment()->setWrapText(true);

            // number format (Montants)
            $sheet->getStyle('O3:O'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('P3:P'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('O3:O'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('P3:P'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('Q3:Q'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('R3:R'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('S3:S'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('T3:T'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('U3:U'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('V3:V'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('W3:W'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('X3:X'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('Y3:Y'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('Z3:Z'.$nblignes)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            // column dimensions
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setWidth(40);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(30);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setWidth(15);
            $sheet->getColumnDimension('L')->setWidth(30);
            $sheet->getColumnDimension('M')->setWidth(30);
            $sheet->getColumnDimension('N')->setAutoSize(true);
            $sheet->getColumnDimension('O')->setWidth(30);
            $sheet->getColumnDimension('P')->setAutoSize(true);
            $sheet->getColumnDimension('Q')->setWidth(16);
            $sheet->getColumnDimension('R')->setWidth(16);
            $sheet->getColumnDimension('S')->setWidth(16);
            $sheet->getColumnDimension('T')->setWidth(16);
            $sheet->getColumnDimension('U')->setWidth(16);
            $sheet->getColumnDimension('V')->setWidth(16);
            $sheet->getColumnDimension('W')->setWidth(16);
            $sheet->getColumnDimension('X')->setWidth(16);
            $sheet->getColumnDimension('Y')->setWidth(16);
            $sheet->getColumnDimension('Z')->setWidth(16);
            $sheet->getColumnDimension('AA')->setWidth(30);
//
            $sheet->setSelectedCell('A3');

            // Create your Office 2007 Excel (XLSX Format)
            $writer = new Xlsx($spreadsheet);

            // Create a Temporary file in the system
            //$slug = trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titre));
            $slug = str_replace("/"," ",$titre);
            $fileName = $slug.'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            // Create the excel file in the tmp directory of the system
            $writer->save($temp_file);

            // Return the excel file as an attachment
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }
        return $this->render('situations/form.html.twig', array(
            'form' => $form->createView(),
            'conventions' => $conventions,
            'titre'     => $titre
        ));
    }








}

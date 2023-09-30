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
use App\Service\ContainerParametersHelper;
use DateTime;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\TemplateProcessor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ConventionAdminController extends CRUDController
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
     * @Route("{id}/{idp}/tableParticipationsConvention", name="convention_tableParticipationsConvention")
     * @Entity("convention", expr="repository.find(id)")
     * @Entity("partenaire", expr="repository.find(idp)")
     */
    public function tableParticipationsConvention(Convention $convention, Partenaire $partenaire)
    {
        $em = $this->getDoctrine()->getManager();
        //$partenaire = $em->getRepository(Partenaire::class)->find(1);
        $rows = $em->getRepository(Partenaire::class)->getParticipationAnnuellesConvention($convention,$partenaire);
         //dd($rows);
        return $this->render('convention/_table_participations.html.twig', [
            'rows' => $rows,
            'name' => $partenaire->getName()
        ]);
    }

    /**
     * @Route("{id}/tableParticipations", name="convention_tableParticipations")
     */
    public function tableParticipations(Partenaire $partenaire)
    {
        $em = $this->getDoctrine()->getManager();
        //$partenaire = $em->getRepository(Partenaire::class)->find(1);
        $rows = $em->getRepository(Partenaire::class)->getParticipationAnnuelles($partenaire);
         //dd($rows);
        return $this->render('convention/_table_participations.html.twig', [
            'rows' => $rows,
            'name' => $partenaire->getName()
        ]);
    }

    /**
     * @param int $id
     */
    public function viewAction($id = null)
    {
        try {
            if ($id !== null) {
                $convention = $this->admin->getObject($id);
            }
        } catch (NotFoundHttpException $e) {
            error_log($e->getMessage());
        }

        // tracer la lecture
        $this->readConvention($convention);

        $engagementProgrammes = $this->getDoctrine()->getRepository(ConventionEngagement::class)->getMontantEngagementsByAnnee($convention);
        //dd($engagementProgrammes);
        foreach ($engagementProgrammes as $e):
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
        $engagements = [];
        foreach ($xData as $k=>$an):
            $categories[] = $an;
            $engagements[$an][1] =  (isset($engagementsData[$an])) ? $engagementsData[$an] : 0 ;
            $engagements[$an][2] =  (isset($contributionsData[$an])) ? $contributionsData[$an] : 0;
        endforeach;

        return $this->render('convention/view.html.twig', [
            'convention' => $convention,
            'engagements' => $engagements,
            'isSuivi' => $this->getUser()->isSuivi($convention)

        ]);
        /*$response = $this->forward('App\Controller\ConventionController::viewAction', [
            'convention'  => $convention,
        ]);
        return $response;*/
    }

    public function readConvention(Convention $convention) {
        if (!empty($this->getUser())) {
            $entity = new ConventionLecture();
            $entity->setConvention($convention);
            $entity->setReadBy($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);$em->flush();
        }
    }


    /**
     * @param Request $request
     * @param int $id
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
    public function ficheAction(Request $request, $id = null)
    {
        try { if ($id !== null) {
            $convention = $this->admin->getObject($id);
        }} catch (NotFoundHttpException $e) { error_log($e->getMessage()); }
        // dump($convention->getPartenairesEngagesOuContribuants());
        // dump($convention->getEngagementsAnneesDecroissant());
        // dd($convention->getEngagementsProgrammesExecutesPartenairesDecroissant());

        // Create a new Word document
        $phpWord = new PhpWord();
        /* Note: any element you append to a document must reside inside of a Section. */

        $phpWord->setDefaultFontName('Sakkal Majalla');
        $phpWord->setDefaultFontSize(12);
        $phpWord->setDefaultParagraphStyle(['bidi'=>true,'align'=>'left','spaceAfter'=>100]);

        // Add style definitions
        $phpWord->addTitleStyle(0, ['rtl' => true,'name'=>'MCS Shafa S_U normal.','bold'=>true, 'size' => 18, 'color'=>'006699'],['bidi'=>true,'align'=>'center']);
        $phpWord->addFontStyle('fontStyle', ['rtl' => true,'name'=>'Sakkal Majalla','bold'=>false, 'size'=>12]);
        $phpWord->addFontStyle('smallFontStyle', ['rtl' => true,'name'=>'Sakkal Majalla','bold'=>false, 'size'=>10]);
        $phpWord->addFontStyle('titreStyle', ['rtl' => true,'name'=>'Sakkal Majalla','bold'=>true, 'size'=>12, 'underline'=>'single']);
        $phpWord->addFontStyle('BoldText', ['rtl' => true,'name'=>'Sakkal Majalla','bold'=>true]);
        $phpWord->addFontStyle('ColoredText', ['rtl' => true,'color'=>'FF8080']);
        $phpWord->addParagraphStyle('pStyle', ['bidi'=>true,'align'=>'left','spaceAfter'=>100]);

        $section = $phpWord->addSection();

        /* @var Convention $convention */
        $section->addTitle('بطاقة تقنية', 0);
        $section->addText($convention->getObjetConvention(), ['rtl' => true,'name'=>'Sakkal Majalla','bold'=>true, 'size'=>14], ['bidi'=>true,'align'=>'center']);
        // $section->addTextBreak(1);

        $phpWord->addTableStyle('noBorderTableStyle', ['bidiVisual'=>true], []);

        // Add table
        $table0 = $section->addTable('noBorderTableStyle');
        $table0->addRow();
        $table0->addCell(4500)->addText('رقم المقرر : '.($convention->getNumeroDecision()), 'fontStyle', 'pStyle');
        $table0->addCell(4500)->addText('دورة المصادقة: '.(!empty($convention->getSessionApprobation()) ? $convention->getSessionApprobation()->getName() : ''), 'fontStyle', 'pStyle');

        
        $avenants = implode(' ، ', $convention->getAvenants()->ToArray());
        $section->addText($avenants, ['rtl' => true,'name'=>'Sakkal Majalla','bold'=>true, 'size'=>13], ['bidi'=>true,'align'=>'center']);


        $styleDiv = "font-family: Sakkal Majalla; font-size: 16px; text-align: left; direction: rtl; unicode-bidi: embed;";//
        //$html = str_replace("<p>","<p style='font-family: Sakkal Majalla; font-size: 12px; text-align: left; direction: rtl; unicode-bidi: embed; margin-bottom: 160pt;'>", $html);

        // $section->addTextBreak(1);
        $section->addText('أهداف ومحتوى  الاتفاقية:', 'titreStyle', 'pStyle');
        Html::addHtml($section, '<div style ="'.$styleDiv.'">'.$convention->getObjectifsConvention().'</div>');
        // $section->addText('محتوى البرنامج: ', 'titreStyle', 'pStyle');
        Html::addHtml($section, '<div style ="'.$styleDiv.'">'.$convention->getConsistance().'</div>');
        // $section->addText('الأطراف المتعاقدة:', 'titreStyle', 'pStyle');
        // if (!$convention->getPartieContractantes()->isEmpty()) {
        //     foreach ($convention->getPartieContractantes() as $partenaire):
        //         $section->addListItem($partenaire->getName(), 0, 'fontStyle', null,'pStyle');
        //     endforeach;
        // }
        // $section->addText('التكلفة الإجمالية للبرنامج:', 'titreStyle', 'pStyle');
        // $section->addText((!empty($convention->getMontantConvention())) ? $convention->getMontantConvention()/1000000 : "", 'fontStyle', 'pStyle');
        
        $firstRowCellStyle = array('bgColor' => 'cccccc', 'valign' => 'center');
        $firstRowStyle = array('name' => 'Sakkal Majalla','rtl' => true, 'size' => 14, 'bold'=>true);
        $BodyFontStyle = array('name'=>'Sakkal Majalla','spaceAfter'=>0,'rtl' => true, 'bold'=>false, 'size'=>12);
        $cellStyle = array('valign' => 'center');
        $centered= array('align'=>'center');
        $righted= array('align'=>'right');
        $styleTable = [
            'layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED,
            'borderSize' => 6, 'borderColor' => 'green', 'width' => 100*50,'unit' => TblWidth::PERCENT,
            'bidiVisual' => true,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
        ];
        $styleTableNoBorder = [
            'layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED,
            'borderSize' => 0, 'borderColor' => 'white', 'width' => 100*50,'unit' => TblWidth::PERCENT,
            'bidiVisual' => true,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
        ];
        // Add table style
        $phpWord->addTableStyle('myOwnTableStyle', $styleTable, $firstRowStyle);
        $phpWord->addTableStyle('myOwnTableHeaderStyle', $styleTableNoBorder, array('name' => 'Sakkal Majalla','rtl' => true, 'size' => 12, 'bold'=>true));

        if (!empty($convention->getEngagementsProgrammesExecutesPartenairesDecroissant())) {
            
            $section->addText('الأطراف المتعاقدة والإلتزامات المبرمجة:', 'titreStyle', 'pStyle');
            $section->addText('(المبالغ بملايين الدراهم)', 'smallFontStyle', 'pStyle');
            // Add table des engagements
            $table = $section->addTable('myOwnTableStyle');
            $table->addRow();
            $table->addCell(1000, array_merge($firstRowCellStyle, array('gridSpan' => 2)))->addText("المجموع",$firstRowStyle, $centered);
            foreach ($convention->getEngagementsAnneesDecroissant() as $engagementsAnnee):
                $table->addCell(1000, array_merge($firstRowCellStyle, array('gridSpan' => 2)))->addText($engagementsAnnee,$firstRowStyle, $centered);
            endforeach;
            $table->addCell(2500, $firstRowCellStyle)->addText('السنة',$firstRowStyle, $centered);
            
            $table->addRow();
            $annees = $convention->getEngagementsAnneesDecroissant();
            for ($i=0;$i<= count($annees); $i++) {
                $table->addCell(1000, $firstRowCellStyle)->addText("منفذ",$firstRowStyle, $centered);
                $table->addCell(1000, $firstRowCellStyle)->addText("مبرمج",$firstRowStyle, $centered);
            }
            $table->addCell(2500, $firstRowCellStyle)->addText('الأطراف   - الالتزامات',$firstRowStyle, $centered);
            $totalGeneral = $total = [];
            $totalGeneral['execute'] = $totalGeneral['engage'] = 0;
            foreach ($convention->getEngagementsProgrammesExecutesPartenairesDecroissant() as $partenaire=>$montants):
                $table->addRow();
                $table->addCell(1000, $cellStyle)->addText($montants['total']['execute'], $BodyFontStyle, $centered);
                $table->addCell(1000, $cellStyle)->addText($montants['total']['engage'], $BodyFontStyle, $centered);
                foreach ($montants as $an => $montant):
                    if ($an != 'total') {
                        $table->addCell(1000, $cellStyle)->addText($montant['execute'], $BodyFontStyle, $centered);
                        $table->addCell(1000, $cellStyle)->addText($montant['engage'], $BodyFontStyle, $centered);
                        if (!isset($total[$an]['execute'])) { $total[$an]['execute'] = 0; }
                        if (!isset($total[$an]['engage']))  { $total[$an]['engage'] = 0; }
                        $total[$an]['execute'] += $montant['execute'];
                        $total[$an]['engage'] += $montant['engage'];
                    }
                endforeach;
                $table->addCell(2500, $cellStyle)->addText($partenaire, $BodyFontStyle, $centered);
                // calcul du total par annee
                $totalGeneral['execute'] += $montants['total']['execute'];
                $totalGeneral['engage']  += $montants['total']['engage'];
                
            endforeach;
            
            $table->addRow();
            $table->addCell(1000, $firstRowCellStyle)->addText($totalGeneral['execute'],$firstRowStyle, $centered);
            $table->addCell(1000, $firstRowCellStyle)->addText($totalGeneral['engage'],$firstRowStyle, $centered);
            foreach ($annees as $an):
                $table->addCell(1000, $firstRowCellStyle)->addText($total[$an]['execute'], $firstRowStyle, $centered);
                $table->addCell(1000, $firstRowCellStyle)->addText($total[$an]['engage'], $firstRowStyle, $centered);
            endforeach;
            $table->addCell(2500, $firstRowCellStyle)->addText("المجموع", $firstRowStyle, $centered);
            
        }
        // Add table Situation et execution
        $section->addText('وضعية الاتفاقية وتنفيذها :', 'titreStyle', 'pStyle');
        $table2 = $section->addTable('myOwnTableStyle');
        $table2->addRow();
        $table2->addCell(3000, $firstRowCellStyle)->addText("وضعية الإنجاز",$firstRowStyle, $centered);
        $table2->addCell(1250, $firstRowCellStyle)->addText("وضعية التنفيذ",$firstRowStyle, $centered);
        $table2->addCell(1250, $firstRowCellStyle)->addText("وضعية الإعداد",$firstRowStyle, $centered);
        $table2->addCell(1000, $firstRowCellStyle)->addText("المدة",$firstRowStyle, $centered);
        $table2->addCell(2500, $firstRowCellStyle)->addText("المكلف بالتنفيذ",$firstRowStyle, $centered);
        
        
        $table2->addRow();
        $suiviExe = '';
        foreach ($convention->getConventionSuiviExecutions() as $suivi):
            $suiviExe .= $suivi->getDecisions().' : '.$suivi->getTauxAvancement().'%<w:br/>';
        endforeach;
        $table2->addCell(3000, $cellStyle)->addText($suiviExe, $BodyFontStyle, $centered);
        
        $table2->addCell(1250, $cellStyle)->addText(!empty($convention->getStadeExecution()) ? $convention->getStadeExecution()->getName() : '', $BodyFontStyle, $centered);
        $table2->addCell(1250, $cellStyle)->addText(!empty($convention->getStadeElaboration()) ? $convention->getStadeElaboration()->getName() : '', $BodyFontStyle, $centered);
        $table2->addCell(1000, $cellStyle)->addText($convention->getDuree(), $BodyFontStyle, $centered);
        $table2->addCell(2500, $cellStyle)->addText(!empty($convention->getMaitreOuvrage()) ? $convention->getMaitreOuvrage()->getName() : '', $BodyFontStyle, $centered);
        
        $table2->addRow();
        $table2->addCell(3000, $firstRowCellStyle)->addText("ملاحظات حول التنفيذ",$firstRowStyle, $centered);
        $table2->addCell(3500, array_merge($firstRowCellStyle, array('gridSpan' => 3)))->addText("ملاحظات حول الاتفاقية",$firstRowStyle, $centered);
        $table2->addCell(2500, $firstRowCellStyle)->addText("التوطين",$firstRowStyle, $centered);
        
        $table2->addRow();
        // $table2->addCell(3000, $cellStyle)->addText('...', $BodyFontStyle, $centered);
        $cellObservation1 = $table2->addCell(3000, $cellStyle);
        Html::addHtml($cellObservation1, '<div style ="'.$styleDiv.'">'.$convention->getObservations().'</div>');
        
        // $table2->addCell(3500, array_merge($cellStyle, array('gridSpan' => 2)))->addText('...', $BodyFontStyle, $centered);
        $cellObservations = $table2->addCell(3500, array_merge($cellStyle, array('gridSpan' => 3)));
        Html::addHtml($cellObservations, '<div style ="'.$styleDiv.'">'.$convention->getObservation1().'</div>');
        $table2->addCell(2500, $cellStyle)->addText(implode(', ',$convention->getLocalisations()->toArray()), $BodyFontStyle, $centered);
            

            
            // $section->addText('المكلف بالتنفيذ:', 'titreStyle', 'pStyle');
            // $section->addText(!empty($convention->getMaitreOuvrage()) ? $convention->getMaitreOuvrage()->getName() : '', 'fontStyle', 'pStyle');
            // $section->addText('حالة الإتفاقية: ', 'titreStyle', 'pStyle');
            // $section->addText(!empty($convention->getStade()) ? $convention->getStade()->getName() : '', 'fontStyle', 'pStyle');
        // $section->addText('المدة:', 'titreStyle', 'pStyle');
        // $section->addText($convention->getDuree(), 'fontStyle', 'pStyle');
        // $section->addText('وضعية التنفيذ:', 'titreStyle', 'pStyle');
        // $section->addText('...', 'fontStyle', 'pStyle');
        // $section->addText('التوطين:', 'titreStyle', 'pStyle');
        // $section->addText(implode(', ',$convention->getLocalisations()->toArray()), 'fontStyle', 'pStyle');
        // $section->addText('ملاحظات:', 'titreStyle', 'pStyle');
        // Html::addHtml($section, '<div style ="'.$styleDiv.'">'.$convention->getObservations().'</div>');


        // header begin
        $header = $section->addHeader();
        $header->firstPage();
        $tableHeader = $header->addTable('myOwnTableHeaderStyle');
        $tableHeader->addRow();

        $cell2 = $tableHeader->addCell(3000, $cellStyle);
        $textrun2 = $cell2->addTextRun(['align' => 'center']);
        $textrun2->addText("طنجة، ".(new Datetime())->format('d/m/Y'),$firstRowStyle, $centered);
        // $header->addTextBreak(1);

        $tableHeader->addCell(3000, $cellStyle)->addImage(
        'https://si.crtta.ma/conventions/img/logo.png',
        [
            'alignment'     => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            'width'         => 100,
            'height'        => 100,
            'marginTop'     => -1,
            'marginLeft'    => -1,
            'wrappingStyle' => 'behind'
        ]);

        $cell1 = $tableHeader->addCell(3000, $cellStyle);
        $textrun1 = $cell1->addTextRun(['align' => 'center']);
        $textrun1->addText("المملكة المغربية",$firstRowStyle, $centered);
        $textrun1->addTextBreak(1);
        $textrun1->addText("وزارة الداخلية",$firstRowStyle, $centered);
        $textrun1->addTextBreak(1);
        $textrun1->addText("جهة طنجة - تطوان - الحسيمة",$firstRowStyle, $centered);

        // header end

        // footer begin
        $footer = $section->addFooter();
        $footer->firstPage();
        $footer->addPreserveText('{PAGE}\{NUMPAGES}', array('size'=>9), array('align'=>'center'));

        $footer_sub = $section->addFooter();
        $footer_sub->addPreserveText('{PAGE}\{NUMPAGES}', array('size'=>9), array('align'=>'center'));
        // footer end

        // Saving the document as OOXML file...
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

        // Create a temporal file in the system
        $fileName = 'fiche.docx';
        $fileName = 'fiche_technique_'.$convention->getId().'.docx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Write in the temporal filepath
        $objWriter->save($temp_file);

        // Send the temporal file as response (as an attachment)
        $response = new BinaryFileResponse($temp_file);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );

        return $response;

    }



    /**
     * @param Request $request
     * @param int $id
     * @return BinaryFileResponse
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
    public function fiche0Action(Request $request, $id = null)
    {
        try { if ($id !== null) {
            $convention = $this->admin->getObject($id);
        }} catch (NotFoundHttpException $e) { error_log($e->getMessage()); }

        $templatesFolder = $this->pathHelpers->getApplicationRootDir(). '/public/modeles/';
        $document = new TemplateProcessor($templatesFolder.'/fiche_technique.docx');
        /** @var Convention $convention */
        $document->setValue('objetConvention', $convention->getObjetConvention());
//        $document->setValue('numero', $convention->getNumero());
        $document->setValue('duree', $convention->getDuree());
        $localisation=  implode(', ',$convention->getLocalisations()->toArray());
        $document->setValue('localisation', $localisation);
        $document->setValue('stadeExecution', "");
        $document->setValue('stade', !empty($convention->getStade()) ? $convention->getStade()->getName() : '');
        $document->setValue('sessionApprobation', !empty($convention->getSessionApprobation()) ? $convention->getSessionApprobation()->getName() : '');
        $document->setValue('numeroDecision', $convention->getNumeroDecision());
        $document->setValue('chargeExecution', !empty($convention->getMaitreOuvrage()) ? $convention->getMaitreOuvrage()->getName() : '');
//        $document->setValue('tauxAvancement', '...'.'%');
        //$organes = $convention->getOrganeGouvernances();
        //$document->setValue('organeGouvernances', '......');
        $montant = (!empty($convention->getMontantConvention())) ? $convention->getMontantConvention()/1000000 : "";
        $document->setValue('montantConvention', $montant);

        // champs comportant tags html
        /*$document->setValue('consistance', $convention->getConsistance());
        $document->setValue('objectifsConvention', $convention->getObjectifsConvention());
        $document->setValue('observations', $convention->getObservations());*/

        $document->setValue('consistance', '...');
        $document->setValue('objectifsConvention', '...');
        $document->setValue('observations', '...');

        // Organes de Gouvernance
        /*$values = [];
        if (!$convention->getOrganeGouvernances()->isEmpty()) {
            foreach ($convention->getOrganeGouvernances() as $organeGouvernance):
                $values[] = [
                    'organeGouvernance' => $organeGouvernance->getName()
                ];
            endforeach;
        } else {
            $values = [[
                'organeGouvernance' => '',
            ]];
        }
        $document->cloneRowAndSetValues('organeGouvernance', $values);*/

        // PARTIES CONTRACTANTES
        $values = [];
        if (!$convention->getPartieContractantes()->isEmpty()) {
            foreach ($convention->getPartieContractantes() as $partenaire):
                $values[] = [
                    'partenaireContractant' => $partenaire->getName()
                ];
            endforeach;
        } else {
            $values = [[
                'partenaireContractant' => '',
            ]];
        }
        $document->cloneRowAndSetValues('partenaireContractant', $values);

        if (!empty($convention->getEngagementsProgrammesPartenaires())) {
            $firstRowCellStyle = array('bgColor' => 'cccccc', 'valign' => 'center');
            $firstRowStyle = array('name' => 'Sakkal Majalla','rtl' => true, 'size' => 16, 'bold'=>true);
            $BodyFontStyle = array('name'=>'Sakkal Majalla','rtl' => true, 'bold'=>false, 'size'=>16);
            $cellStyle = array('valign' => 'center');
            $centered= array('align'=>'center');
            $righted= array('align'=>'right');
            $table = new Table(array('layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED, 'borderSize' => 6, 'borderColor' => 'green','unit' => 'pct', 'width' => 100*50, 'bidiVisual'=> true));
            $table->addRow();
            $table->addCell(1000, $firstRowCellStyle)->addText('السنة',$firstRowStyle, $centered);

            foreach ($convention->getEngagementsAnnees() as $engagementsAnnee):
                $table->addCell(200, $firstRowCellStyle)->addText($engagementsAnnee,$firstRowStyle, $centered);
            endforeach;
            $table->addCell(300, $firstRowCellStyle)->addText("المجموع",$firstRowStyle, $centered);

            foreach ($convention->getEngagementsProgrammesPartenaires() as $partenaire=>$engagements):
                $table->addRow();
                $table->addCell(1000, $cellStyle)->addText($partenaire, $BodyFontStyle, $centered);
                foreach ($engagements as $montant):
                    $table->addCell(200, $cellStyle)->addText($montant, $BodyFontStyle, $centered);
                endforeach;
                $table->addCell(300, $cellStyle)->addText(array_sum($engagements), $BodyFontStyle, $centered);
            endforeach;
        } else {
            $table = new Table(array('layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED, 'borderSize' => 6, 'borderColor' => 'green','unit' => 'pct', 'width' => 100*50, 'bidiVisual'=> true));
        }
        $document->setComplexBlock('table', $table);

//        $dateDepart = $this->mesfonctionsController->arabicDate($ordreMission->getDateDepart()->getTimestamp());
//        $document->setValue('dateDepart', $dateDepart);

        //
        $fileName = 'fiche_technique_'.$convention->getId().'.docx';
        $temp_file = tempnam(sys_get_temp_dir(), 'document');
        $document->saveAs($temp_file);
        $response = new BinaryFileResponse($temp_file);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);
        return $response;
    }

    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionArchiver(ProxyQueryInterface $selectedModelQuery, Request $request)
    {
        $this->admin->checkAccess('edit');
        $modelManager = $this->admin->getModelManager();
        $selectedModels = $selectedModelQuery->execute();
        try {
            foreach ($selectedModels as $selectedModel) {
                $selectedModel->setArchived(true);
            }
            $modelManager->update($selectedModel);
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_archive_error');
            return new RedirectResponse($this->admin->generateUrl('list', ['filter' => $this->admin->getFilterParameters()]));
        }
        $this->addFlash('sonata_flash_success', 'flash_batch_archive_success');
        return new RedirectResponse($this->admin->generateUrl('list', ['filter' => $this->admin->getFilterParameters()]));
    }

    /**
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionDesarchiver(ProxyQueryInterface $selectedModelQuery, Request $request)
    {
        $this->admin->checkAccess('edit');
        $modelManager = $this->admin->getModelManager();
        $selectedModels = $selectedModelQuery->execute();
        try {
            foreach ($selectedModels as $selectedModel) {
                $selectedModel->setArchived(false);
            }
            $modelManager->update($selectedModel);
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_desarchive_error');
            return new RedirectResponse($this->admin->generateUrl('list', ['filter' => $this->admin->getFilterParameters()]));
        }
        $this->addFlash('sonata_flash_success', 'flash_batch_desarchive_success');
        return new RedirectResponse($this->admin->generateUrl('list', ['filter' => $this->admin->getFilterParameters()]));
    }

}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use DateTime;
use DateInterval;
use Symfony\Component\Routing\Annotation\Route;
use Numbers_Words;

class MesfonctionsController extends AbstractController
{

    /**
     * @Route("/mesfonctions/test", name="mesfonctions_test")
     */
    public function testAction()
    {
        dump($this->diffDates('2018-02-14','2018-02-27', 'j'));
        dump($this->diffDates('2018-05-29','2018-05-31', 'j'));
        die;
    }

    /**
     *
     */
    function arabicDate($datetime)
    {
        $months = ["Jan" => "يناير", "Feb" => "فبراير", "Mar" => "مارس", "Apr" => "أبريل", "May" => "ماي", "Jun" => "يونيو", "Jul" => "يوليوز", "Aug" => "غشت", "Sep" => "شتنير", "Oct" => "أكتوبر", "Nov" => "نونبر", "Dec" => "دجنبر"];
        $days = ["Sat" => "السبت", "Sun" => "الأحد", "Mon" => "الإثنين", "Tue" => "الثلاثاء", "Wed" => "الأربعاء", "Thu" => "الخميس", "Fri" => "الجمعة"];
        $hours = ["00" => "....................", "01" => "الواحدة", "02" => "الثانية", "03" => "الثالثة", "04" => "الرابعة", "05" => "الخامسة", "06" => "السادسة", "07" => "السابعة", "08" => "الثامنة", "09" => "التاسعة", "10" => "العاشرة", "11" => "الحادية عشر", "12" => "الثانية عشر"  ];
        $am_pm = ['AM' => 'صباحاً', 'PM' => 'مساءً'];

        $day = $days[date('D', $datetime)];
        $month = $months[date('M', $datetime)];
        $hour = $hours[date('h', $datetime)];
        $am_pm = $am_pm[date('A', $datetime)];
        if (date('h', $datetime) === "00") $am_pm = "";
        $date = $day . ' ' . date('d', $datetime) . ' ' . $month . ' ' . date('Y', $datetime) . ' ' . $hour . ' ' . $am_pm;
        return $date;
    }

    /**
     * @param $dateFin
     * parties de mois comprises entre deux dates
     * @param $dateDebut
     * @param $dateFin
     * @param $type
     * @param $arrete
     * @return array
     */
    public function partiesMois($dateDebut, $dateFin, $type = 'j', $arrete = '2015') {

        $dtDeb = new DateTime($dateDebut);
        $dtFin = new DateTime($dateFin);
        $dtFin0 = new DateTime($dateFin);
        if ($dateFin === null) $dtFin = new DateTime(date("Y-m-d"));
        $parties = array();
        $mois = $dtDeb->format('m/Y');
        $parties[$mois]['1']['date1'] = $dtDeb->format('Y-m-d');
        $parties[$mois]['1']['date2'] = $dtDeb->format('Y-m-t');
        $parties[$mois]['1']['date1fr'] = $dtDeb->format('d/m/Y');
        $parties[$mois]['1']['date2fr'] = $dtDeb->format('t/m/Y');
        $nbrejoursdebut = $this->diffDates($dtDeb->format('Y-m-01'),$dtDeb->format('Y-m-d'),'j', $arrete);
//        $parties[$mois]['1']['nbrejours'] = $this->diffDates($dtDeb->format('Y-m-d'),$dtDeb->format('Y-m-t'),'j');
        $parties[$mois]['1']['nbrejours'] = $dtDeb->format('t') - $nbrejoursdebut;
        if ($parties[$mois]['1']['date1'] == $parties[$mois]['1']['date2']) $parties[$mois]['1']['nbrejours'] = 1;
        $date1 = $dtDeb; $date2 = $dtFin->modify('first day of this month');
        $i = 1;
        if (($dateDebut <= $dateFin) or ($dateFin === null)) {
            while ($date1 < $date2) {
                $i++;
                //$date1->add(new \DateInterval("P1M"));
                $date1->modify('first day of next month');
                $mois = $date1->format('m/Y');
                $parties[$mois][$i]['date1'] = $date1->format('Y-m-01');
                $parties[$mois][$i]['date2'] = $date1->format('Y-m-t');
                $parties[$mois][$i]['date1fr'] = $date1->format('01/m/Y');
                $parties[$mois][$i]['date2fr'] = $date1->format('t/m/Y');
                if ($parties[$mois][$i]['date1'] == $parties[$mois][$i]['date2']) {
                    $parties[$mois][$i]['nbrejours'] = 1;
                } else {
                    $parties[$mois][$i]['nbrejours'] = ($arrete == '2015') ? $this->diffDates($parties[$mois][$i]['date1'], $parties[$mois][$i]['date2'], 'j', $arrete)+1 : 30 ; //  30j selon arrêté 1999 // ;
                }
            }
        }
        $mois = $dtFin0->format('m/Y');
//        $parties[$mois][$i]['date1'] = $dtFin0->format('Y-m-01');
//        $parties[$mois][$i]['date1fr'] = $dtFin0->format('01/m/Y');
        $parties[$mois][$i]['date2'] = $dtFin0->format('Y-m-d');
        $parties[$mois][$i]['date2fr'] = $dtFin0->format('d/m/Y');
        if ($parties[$mois][$i]['date1'] == $parties[$mois][$i]['date2']) {
            $parties[$mois][$i]['nbrejours'] = 1;
        } else {
            $parties[$mois][$i]['nbrejours'] = $this->diffDates($parties[$mois][$i]['date1'],$dtFin0->format('Y-m-d'), 'j', $arrete)+1;
        }
        //if (isset($parties[$mois][$i]['date1']))
//        if ($i == 1) $parties[$mois][$i]['nbrejours'] = $this->diffDates($parties[$mois][$i]['date1'],$dtFin0->format('Y-m-d'), 'j');

        //dump($parties);die;
//        return 'deb='.$dtDeb->format('Y-m-d')."---Fin=".$dtFin->format('Y-m-d') ;

        return $parties;
    }

    /**
     * Différence en mois/jours entre deux dates
     * @param $dateDebut
     * @param $dateFin
     * @return int
     */
    public function diffDates($dateDebut, $dateFin, $type = 'j') {
//        $dtDeb = new DateTime($dateDebut) ;
//        $dtFin = new DateTime($dateFin) ;
//        if ($dateFin === null) $dtFin = new DateTime(date("Y-m-d"));
//        if (($dateDebut <= $dateFin) or ($dateFin === null)) { $signe = 1; } else { $signe = -1; }
//        $diff =  $dtDeb->diff($dtFin);
        $diff =  $dateDebut->diff($dateFin);
        $ret = 0;

        $months = $diff->y * 12  + $diff->m     + $diff->d / 30;
        $nj = ($diff->d < 5) ? $diff->d : $diff->d ;
        $days   = $diff->y * 365 + $diff->m *30 + $nj;
        if ($type === 'm') $ret = $signe * $this->chiffreArrondi($months,1);
        if ($type === 'j') $ret = $signe * round($days);

        return (int) $ret ;
    }

    /**
     * Chiffre à 4 chiffres après la virgule
     * @param $nbre
     * @param $precision
     * @return float
     */
    public function chiffreArrondi($valeur, $precision=4) {
        //        $nbre = round($valeur, $precision, PHP_ROUND_HALF_DOWN);
        return floor($valeur*pow(10,$precision))/pow(10,$precision);
    }

    /**
     * Convertit un montant en chiffres ===> montant en LETTREs
     */
    public function MontantEnLettres($montant) {
        $entiere = floor($montant);
        $fraction = round(($montant - floor($montant))*100);
        $montantEnLettres = Numbers_Words::toWords($entiere, "fr"). ' Dirhams';
        if ($fraction > 0) $montantEnLettres .= ' '.Numbers_Words::toWords($fraction, "fr").' centimes';
        return $montantEnLettres;
    }

}


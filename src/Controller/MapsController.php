<?php

namespace App\Controller;

use App\Entity\Convention;

use App\Entity\ConventionContribution;
use App\Entity\ConventionEngagement;
use App\Entity\Localisation;
use App\Entity\Partenaire;
use App\Repository\LocalisationRepository;
use App\Service\ContainerParametersHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/maps")
 */
class MapsController extends AbstractController
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
     * @Route("/", name="maps")
     */
    public function index()
    {
        return $this->render('charts/index.html.twig', []);
    }

    /**
     * @Route("/map10", name="map10")
     */
    function map10() {
        $div = 'map10';
        $titre = 'الإلتزامات المالية حسب الأقاليم ';
        $titreSerie = 'الإلتزامات المالية ';
        $theme = $this->themes(76);
        $data = [];   $i = 0;
        $localisations = $this->getDoctrine()->getManager()->getRepository(Localisation::class)->findAll();
        foreach ($localisations AS $localisation) {
            /* @var Localisation $localisation */
            if (!empty($localisation->getMapKey())) {
                $data[] = [
                    'label' => $localisation->getName(),
                    'id' => $localisation->getMapKey(),
                    'value' => number_format($localisation->getMontantConventions()/1000000,0, ',','.'),//dispalayed on map
                    'value0' => number_format($localisation->getMontantConventions(),0, ',','.'),// displayed in tooltip
                    'color' => $theme[$i],
                    'dataLabels' => ['enabled'=>true,'shape' => 'callout']
                ];
                $i++;
            }
        }
        //dd($data);
        return $this->render('maps/map.html.twig', ['div'=>$div, 'data'=>$data, 'titre'=>$titre, 'titreSerie'=>$titreSerie]);
    }

    /**
     * @Route("/map20", name="map20")
     */
    function map20() {

        $titre = 'عدد الإتفاقيات حسب الأقاليم ';
        $titreSerie = 'عدد الإتفاقيات ';
        $div = 'map20';
        $theme = $this->themes(39);
        $data = [];   $i = 0;
        $localisations = $this->getDoctrine()->getManager()->getRepository(Localisation::class)->findAll();
        foreach ($localisations AS $localisation) {
            /* @var Localisation $localisation */
            if (!empty($localisation->getMapKey())) {
                $data[] = [
                    'label' => $localisation->getName(),
                    'id' => $localisation->getMapKey(),
                    'value' => number_format($localisation->getMontantConventions(),0, ',','.'), //dispalayed on map
                    'value0' => number_format($localisation->getMontantConventions(),0, ',','.'),// displayed in tooltip
                    'color' => $theme[$i]
                ];
                $i++;
            }
        }
        //dd($data);
        return $this->render('maps/map.html.twig', ['div'=>$div, 'data'=>$data, 'titre'=>$titre, 'titreSerie'=>$titreSerie]);
    }

    /**
     * @Route("/testthemes", name="testthemes")
     */
    function testThemes() {
        return $this->render('maps/testthemes.html.twig', []);

    }
    /**
     * @Route("{k}/testtheme", name="testtheme")
     */
    function testTheme($k = 0) {
            $div = 'testthemes'.$k;
            $titre = $k;
            $titreSerie = $k;

            $theme = $this->themes($k);
            $data = [];
            $i = 0;
            $localisations = $this->getDoctrine()->getManager()->getRepository(Localisation::class)->findAll();
            foreach ($localisations AS $localisation) {
                /* @var Localisation $localisation */
                if (!empty($localisation->getMapKey())) {
                    $data[] = [
                        'label' => $localisation->getName(),
                        'id' => $localisation->getMapKey(),
                        'value' => '',
                        'value0' => '',
                        'color' => $theme[$i]
                    ];
                    $i++;
                }
            }
        //dd($data);
        return $this->render('maps/map_test.html.twig', ['div'=>$div, 'data'=>$data, 'titre'=>$titre, 'titreSerie'=>$titreSerie]);
    }

    private function themes($id = 0) {
        $return = ["#aa9966","#ccbb66","#ddcc80","#eedd99","#cccc91","#aabb88","#999966","#887744"];
        if     ($id== 1) {$return = ["#555b6e","#62717e","#6f868e","#7c9b9e","#89b0ae","#a4cac5","#bee3db","#dceeea"];}
        elseif ($id== 2) {$return = ["#07306b","#07519d","#2171b6","#4293c7","#6bafd7","#9fcbe2","#c7dcf0","#dfedf9"];}
        elseif ($id== 3) {$return = ["#0c4007","#2c581c","#436a2b","#5a7b3a","#6d8a47","#809853","#a2b269","#ced386"];}
        elseif ($id== 4) {$return = ["#d5d8db","#e8e8e8","#c3ecb2","#b7e3d9","#aadaff","#fff2af","#fbe18a","#f6cf65"];}// google map
        elseif ($id== 5) {$return = ["#9ddbff","#b0e8ff","#c3f4fe","#fefded","#e8e1c9","#aad1ac","#bbdcb5","#cbe7be"];}
        elseif ($id== 6) {$return = ["#3b727c","#7a8b7d","#b9a37e","#d1be9d","#82a775","#99836e","#b05f66","#64513b"];}
        elseif ($id== 7) {$return = ["#ffffe5","#f7fcb9","#d9f0a3","#addd8e","#78c679","#41ab5d","#238443","#005a32"];}
        elseif ($id== 8) {$return = ["#ffffd9","#edf8b1","#c7e9b4","#7fcdbb","#41b6c4","#1d91c0","#225ea8","#0c2c84"];}
        elseif ($id== 9) {$return = ["#f7fcf0","#e0f3db","#ccebc5","#a8ddb5","#7bccc4","#4eb3d3","#2b8cbe","#08589e"];}
        elseif ($id==10) {$return = ["#f7fcfd","#e5f5f9","#ccece6","#99d8c9","#66c2a4","#41ae76","#238b45","#005824"];}
        elseif ($id==11) {$return = ["#fff7fb","#ece2f0","#d0d1e6","#a6bddb","#67a9cf","#3690c0","#02818a","#016450"];}
        elseif ($id==12) {$return = ["#fff7fb","#ece7f2","#d0d1e6","#a6bddb","#74a9cf","#3690c0","#0570b0","#034e7b"];}
        elseif ($id==13) {$return = ["#f7fcfd","#e0ecf4","#bfd3e6","#9ebcda","#8c96c6","#8c6bb1","#88419d","#6e016b"];}
        elseif ($id==14) {$return = ["#fff7f3","#fde0dd","#fcc5c0","#fa9fb5","#f768a1","#dd3497","#ae017e","#7a0177"];}
        elseif ($id==15) {$return = ["#f7f4f9","#e7e1ef","#d4b9da","#c994c7","#df65b0","#e7298a","#ce1256","#91003f"];}
        elseif ($id==16) {$return = ["#fff7ec","#fee8c8","#fdd49e","#fdbb84","#fc8d59","#ef6548","#d7301f","#990000"];}
        elseif ($id==17) {$return = ["#fcfbfd","#efedf5","#dadaeb","#bcbddc","#9e9ac8","#807dba","#6a51a3","#4a1486"];}
        elseif ($id==18) {$return = ["#f7fbff","#deebf7","#c6dbef","#9ecae1","#6baed6","#4292c6","#2171b5","#084594"];}
        elseif ($id==19) {$return = ["#f7fcf5","#e5f5e0","#c7e9c0","#a1d99b","#74c476","#41ab5d","#238b45","#005a32"];}
        elseif ($id==20) {$return = ["#8c510a","#bf812d","#dfc27d","#f6e8c3","#c7eae5","#80cdc1","#35978f","#01665e"];}
        elseif ($id==21) {$return = ["#762a83","#9970ab","#c2a5cf","#e7d4e8","#d9f0d3","#a6dba0","#5aae61","#1b7837"];}
        elseif ($id==22) {$return = ["#c51b7d","#de77ae","#f1b6da","#fde0ef","#e6f5d0","#b8e186","#7fbc41","#4d9221"];}
        elseif ($id==23) {$return = ["#b2182b","#d6604d","#f4a582","#fddbc7","#d1e5f0","#92c5de","#4393c3","#2166ac"];}
        elseif ($id==24) {$return = ["#b2182b","#d6604d","#f4a582","#fddbc7","#e0e0e0","#bababa","#878787","#4d4d4d"];}
        elseif ($id==25) {$return = ["#d73027","#f46d43","#fdae61","#fee090","#e0f3f8","#abd9e9","#74add1","#4575b4"];}
        elseif ($id==26) {$return = ["#d53e4f","#f46d43","#fdae61","#fee08b","#e6f598","#abdda4","#66c2a5","#3288bd"];}
        elseif ($id==27) {$return = ["#d73027","#f46d43","#fdae61","#fee08b","#d9ef8b","#a6d96a","#66bd63","#1a9850"];}
        elseif ($id==28) {$return = ["#7fc97f","#beaed4","#fdc086","#ffff99","#386cb0","#f0027f","#bf5b17","#666666"];}
        elseif ($id==29) {$return = ["#a6cee3","#1f78b4","#b2df8a","#33a02c","#fb9a99","#e31a1c","#fdbf6f","#ff7f00"];}
        elseif ($id==30) {$return = ["#fbb4ae","#b3cde3","#ccebc5","#decbe4","#fed9a6","#ffffcc","#e5d8bd","#fddaec"];}
        elseif ($id==31) {$return = ["#b3e2cd","#fdcdac","#cbd5e8","#f4cae4","#e6f5c9","#fff2ae","#f1e2cc","#cccccc"];}
        elseif ($id==32) {$return = ["#8dd3c7","#ffffb3","#bebada","#fb8072","#80b1d3","#fdb462","#b3de69","#fccde5"];}
        elseif ($id==33) {$return = ["#66c2a5","#fc8d62","#8da0cb","#e78ac3","#a6d854","#ffd92f","#e5c494","#b3b3b3"];}
        elseif ($id==34) {$return = ["#69ef7b","#258387","#60e9dc","#19477d","#bbc3fe","#84317b","#eb67f9","#2580fe"];}
        elseif ($id==35) {$return = ["#4f8c9d","#64d4fd","#214d4e","#73eca3","#2f882d","#b9cda1","#7c8a4f","#a5e841"];}
        elseif ($id==36) {$return = ["#069668","#92e986","#214d4e","#23dbe1","#298cc0","#cad3fa","#5756a0","#5826a6"];}
        elseif ($id==37) {$return = ["#58b5e1","#294775","#d38ffd","#b131ae","#793c73","#b8b2f0","#3444bc","#49edc9"];}
        elseif ($id==38) {$return = ["#a0e3b7","#267761","#69ef7b","#728e24","#b1d34f","#76480d","#c68136","#f2d174"];}
        elseif ($id==39) {$return = ["#48bf8e","#0f767a","#7fb4bd","#333a9e","#d678ef","#a21fa1","#6d7ddb","#e9c9fa"];}
        elseif ($id==40) {$return = ["#72e5ef","#016876","#74aff3","#1a4fa3","#fe62cc","#871550","#b97eac","#5336c5"];}
        elseif ($id==41) {$return = ["#56ebd3","#18857f","#83c0e6","#1d7bc3","#fa79f5","#5e4393","#8e80fb","#97127b"];}
        elseif ($id==42) {$return = ["#58b5e1","#1d7583","#6ceac0","#218841","#bde267","#89975b","#6ce03f","#1f4196"];}
        elseif ($id==43) {$return = ["#069668","#79e3f9","#2480a1","#bcc5eb","#6672bb","#9ee5a4","#02531d","#2ae282"];}
        elseif ($id==44) {$return = ["#8de4d3","#1d7583","#8dbcf9","#633ca3","#fa41c7","#3f436d","#f89ade","#812050"];}
        elseif ($id==45) {$return = ["#52ef99","#2a8665","#8de4d3","#304866","#199bce","#710c9e","#8278d2","#bfd6fa"];}
        elseif ($id==46) {$return = ["#72e5ef","#214d4e","#52ef99","#579ba1","#494b93","#8489e0","#8a36b1","#df72ef"];}
        elseif ($id==47) {$return = ["#a0e3b7","#275b52","#4cf185","#13a64f","#b6e45c","#769b5a","#53c6ef","#60409b"];}
        elseif ($id==48) {$return = ["#72e5ef","#2d747a","#76dd78","#2f882d","#a8c280","#663d20","#d37a41","#fd5917"];}
        elseif ($id==49) {$return = ["#0cc0aa","#0a4f4e","#a7dcf9","#5794d7","#53348e","#ae67ca","#c20da6","#eec8f1"];}
        elseif ($id==50) {$return = ["#5671d4","#7298db","#8ebee1","#b3e3e4","#ffd0bd","#ff9e9b","#f7697b","#cf4969"];}
        elseif ($id==51) {$return = ["#058DC7","#50B432","#ED561B","#DDDF00","#24CBE5","#64E572","#FF9655","#FFF263","#6AF9C4"];}
        elseif ($id==52) {$return = ["#9EEDEC","#E5C1ED","#E2A1AA","#F6A1E5","#A9CEF9","#B8CDCB","#B3B7C7","#B4A7EC","#EADAC4","#A9C3D1"];}
        elseif ($id==53) {$return = ["#A5D4B7","#C5FFB1","#AEA7F8","#D1B4CE","#F3DD9F","#F7B5A2","#DFC5A1","#CDB1AA"];}
        elseif ($id==54) {$return = ["#A7D8DF","#F5D1BB","#F79087","#CEE8E9","#F5B3A5","#C7A6C5","#F8DAE2","#E6ACBB","#F6F6AE","#94BBA6"];}
        elseif ($id==55) {$return = ["#DDE5DE","#B19FD9","#BFFBE7","#D7BAC8","#A9EDC9","#E3B8E2","#D1B6F7","#DBDBDC"];}
        elseif ($id==56) {$return = ["#975D52","#C58F51","#998E61","#667405","#93A2A9","#92A98C","#DDC589","#E1AE6D","#A5B9C0","#EDDFB8"];}
        elseif ($id==57) {$return = ["#F6D258","#F0CDC9","#D1AF94","#97D5E0","#88B14B","#0C4C8A","#5C7148","#5587A2","#D23075","#F0552C"];}
        elseif ($id==58) {$return = ["#DE869C","#E19855","#95CCAD","#F3C56D","#F3E5D8","#9E7560","#5DA5D8","#86C2CD","#B8B6DB","#CACAC8"];}
        elseif ($id==59) {$return = ["#FBE0D7","#E9ADAD","#F9C3D0","#FBBFA7","#FFECA8","#C4E5DA","#91B1D8","#D9CFE7","#B69CB6"];}
        elseif ($id==60) {$return = ["#E3BFDB","#FEA7B7","#DADAF6","#F6D7C2","#FDC8C2","#BACEF3","#A8E4E2","#D1BFAB","#F9E285","#E7B985"];}
        elseif ($id==61) {$return = ["#780232","#E1312C","#EE078F","#F66183","#F9C6CB","#DA4328","#F78A69","#FBB05C","#F8D263","#FEF26C"];}
        elseif ($id==62) {$return = ["#CFC4DE","#DEE0BF","#D6CB70","#85C963","#919757","#955499","#00929D","#9FCDCB","#C5A783","#CBBAA0"];}
        elseif ($id==63) {$return = ["#EBBBA4","#F3A99C","#FC8682","#BE4D2F","#E3AB3D","#E3F6F4","#C5EDE4","#AED9C8","#81BBA3","#7E6B67"];}
        elseif ($id==64) {$return = ["#F8EDCF","#F6F7BE","#E4E8CF","#BAC6B2","#655643","#F3F7F8","#F8F8EC","#FBF0FE","#BECDD0","#625567"];}
        elseif ($id==65) {$return = ["#F7E49A","#FECE79","#F8A656","#F48170","#F38193","#F391BC","#8B8BC3","#94CAE3","#97D1A9","#CAE089"];}
        elseif ($id==66) {$return = ["#EFD31A","#F4AE1A","#F36B28","#EF4924","#EC468B","#B856A1","#915BA6","#518BC9","#27BEB6","#A1CD49"];}
        elseif ($id==67) {$return = ["#F6A77E","#F4B98F","#F8EB9C","#A9D9CB","#F5A3BA","#F8C2D0","#EB8B7F","#66D2DE","#9CDBEA","#F9C8C4"];}
        elseif ($id==68) {$return = ["#F5F088","#B2B3B5","#CFD3D4","#E3E98F","#F59B9B","#7AD0CF","#C3D9F1","#FDD6AB","#98D091","#D3BEDD"];}
        elseif ($id==69) {$return = ["#77B2D4","#74CDAF","#D1E5AA","#B8E5EB","#F9DD93","#8DC7EC","#E6CEE4","#72CBC9","#F7B8D3","#ACD684"];}
        elseif ($id==70) {$return = ["#F4EEBA","#F2CBAA","#EEB5AA","#EDB8C8","#D1AFD0","#AAA5CD","#A6C1DF","#A7D5E5","#ABD4C2","#CADEB9"];}
        elseif ($id==71) {$return = ["#FFE7A7","#F6ABA6","#BF9EC9","#92CAEF","#9ED1B0","#FEE396","#F5978F","#B98DBE","#7CB6DF","#8FC2AB"];}
        elseif ($id==72) {$return = ["#F8A98E","#E9A5CE","#95B1D9","#96D5CC","#EEF0AF","#F8A07C","#E895BF","#8198CD","#79CDC6","#E9EA9B"];}
        elseif ($id==73) {$return = ["#F2F2F2","#1E1E20","#AEAEB0","#175AA0","#FED351","#796B6A","#01BBCA","#E46C6D","#F49402","#5FE5F1"];}
        elseif ($id==74) {$return = ["#F6D258","#EFCEC5","#D1AF94","#97D5E0","#88B14B","#EF562D","#D13076","#5587A2","#5C7148","#0C4C8A"];}
        elseif ($id==75) {$return = ["#FF6E61","#7E3830","#C1877B","#ECAA87","#D5D819","#FE6F61","#7C5D99","#A32759"];}
        elseif ($id==76) {$return = ["#058DC7","#50B432","#ED561B","#DDDF00","#24CBE5","#64E572","#FF9655","#FFF263","#6AF9C4"];}

        return $return;
    }
}

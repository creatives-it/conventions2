<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    protected $request;
    /**
     * GetProvinceExtension constructor.
     * @param EntityManagerInterface $em
     * @param RequestStack $request
     */
    public function __construct(EntityManagerInterface $em, RequestStack $request) {
        $this->em = $em;
        $this->request = $request;
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('camelToSpace', [$this, 'convertCamelCaseToHaveSpacesFilter']),
            new TwigFilter('intlNum', [$this, 'convertTelToInternationalNumberFilter']),
            new TwigFilter('jours2mois', [$this, 'jours2moisFilter']),
        );
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('taux', [$this, 'tauxFunction']),
            new TwigFunction('getControllerName', [$this, 'getControllerNameFunction']),
            new TwigFunction('getActionName', [$this, 'getActionNameFunction'])
        );
    }

    /*
     * Converts camel case string to have spaces
     */
    public function convertCamelCaseToHaveSpacesFilter($camelCaseString)
    {
        $pattern = '/(([A-Z]{1}))/';
        return preg_replace_callback(
            $pattern,
            function ($matches) {return " " .$matches[0];},
            $camelCaseString
        );
    }

    /**
     * @param $number
     * @return string
     */
    public function convertTelToInternationalNumberFilter($number) {
        //$return = $number;
        //$return = str_replace('-', '', $return);
        $return = preg_replace('/[^A-Za-z0-9]/', '', $number); // Removes special chars.
        if (strpos($return, '0') === 0) {
            $return = substr($return,1); // Remove 0 at the beginning
        }
        $ind = '212';
        return $ind.$return;
    }

    /**
     * @param $number
     * @return string
     */
    public function jours2moisFilter($number) {
        if ($number === '') return null;
        $return = $nbJours = '';
        //$nbMois = round($number/30,0,PHP_ROUND_HALF_DOWN);
        $nbMois = floor($number/30);
        if ($nbMois > 0) $return = $nbMois.' ش ';
        if ($number > $nbMois*30) $nbJours = ($number - $nbMois*30).' يوم';

        return $return.$nbJours;
    }


    /**
     * Get controller name
     */
    public function getControllerNameFunction()
    {
        $regexp = "#Controller\\\([a-zA-Z]*)Controller#";
        $results = array();
        //$request = $this->requestStack->getCurrentRequest();
        preg_match($regexp, $this->request->getCurrentRequest()->get('_controller'), $results);
        //return $results;
        if (!empty($results)) return strtolower($results[1]);
        return '';
    }
    /**
     * Get action name
     */
    public function getActionNameFunction()
    {
        $regexp = "#::([a-zA-Z]*)Action#";
        $results = array();
        preg_match($regexp, $this->request->getCurrentRequest()->get('_controller'), $results);
        return $results[1];
    }

    public function getName()
    {
        return 'app_extension';
    }
}
<?php
require 'bootstrap.php';



/**
 * ZweiundfuenfzigaPlugin.class.php
 *
 * ...
 *
 * @author  asudau@uos.de
 * @version 0.1a
 */

class ZweiundfuenfzigaPlugin extends AbstractStudIPAdministrationPlugin {

				
    public function __construct() {
       parent::__construct();

      
	$this->setupNavigation();

	PageLayout::addStylesheet($this->getPluginUrl() . '/css/style.css');
	PageLayout::addScript($this->getPluginUrl() . '/highcharts/js/highcharts.js');
	PageLayout::addScript($this->getPluginUrl() . '/highcharts/js/modules/exporting.js');

    }

    public function initialize () {

    
        PageLayout::addStylesheet($this->getPluginURL().'/assets/style.css');
        PageLayout::addScript($this->getPluginURL().'/assets/application.js');
    }

    public function perform($unconsumed_path) {
        $this->setupAutoload();
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'show'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }

    private function setupAutoload() {
        if (class_exists("StudipAutoloader")) {
            StudipAutoloader::addAutoloadPath(__DIR__ . '/models');
        } else {
            spl_autoload_register(function ($class) {
                include_once __DIR__ . $class . '.php';
            });
        }
    }

    private function setupNavigation() {
	  
	$navigation = new PluginNavigation();
        $navigation->setDisplayname('52a Statistiken');
        $this->setNavigation($navigation);
        $top_navigation = new PluginNavigation();
        $top_navigation->setDisplayname('52a Statistiken');
        $this->setTopnavigation($top_navigation);
    }

    function get_license_shortened($index){
	$license_shortened = array(	
				0 => "Frei von Rechten Dritter",
				1 => "Nicht frei von Rechten Dritter",
			       2 => "Ungeklärt",
				4 => "Individuelle Lizenz liegt vor",
				5 => "Campuslizenz, etc.",
			       6 => "$52a: Text",
			       7 => "Public Domain",
				8 => "Schutzfirst abgelaufen",
				9 => "CC",
				10 => "Open Access",
			       12 => "Eigene: Rechte vorbehalten",
			       13 => "Eigene: CC",
			       14 => "Eigene: CC",
			       15 => "Eigene: Public Domain",
				18 => "$52a: Abbildung",
				19 => "$52a: Musikstück",
				20 => "$52a: Kinofilm",
				21 => "$52a: Notenedition",);

	return $license_shortened[$index];

    }


}

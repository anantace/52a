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

    }

    public function initialize () {

		PageLayout::addStylesheet($this->getPluginUrl() . '/css/style.css');
		PageLayout::addScript($this->getPluginUrl() . '/highcharts/js/highcharts.js');
		PageLayout::addScript($this->getPluginUrl() . '/highcharts/js/modules/exporting.js');
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
			       6 => "§52a: Text",
			       7 => "Public Domain",
				8 => "Schutzfirst abgelaufen",
				9 => "CC",
				10 => "Open Access",
			       12 => "Eigene: Rechte vorbehalten",
			       13 => "Eigene: CC",
			       14 => "Eigene: CC",
			       15 => "Eigene: Public Domain",
				18 => "§52a: Abbildung",
				19 => "§52a: Musikstück",
				20 => "§52a: Kinofilm",
				21 => "§52a: Notenedition",);

	return $license_shortened[$index];

    }

     function get_licenses(){
	$licenses = array(	
				0 => "Frei von Rechten Dritter",
				1 => "Nicht frei von Rechten Dritter",
			       2 => "Ungeklärt",
				4 => "Individuelle Lizenz liegt vor",
				5 => "Campuslizenz, etc.",
			       6 => "§52a: Text",
			       7 => "Public Domain",
				8 => "Schutzfirst abgelaufen",
				9 => "CC",
				10 => "Open Access",
			       12 => "Eigene: Rechte vorbehalten",
			       13 => "Eigene: CC",
			       14 => "Eigene: CC",
			       15 => "Eigene: Public Domain",
				18 => "§52a: Abbildung",
				19 => "§52a: Musikstück",
				20 => "§52a: Kinofilm",
				21 => "§52a: Notenedition",);
	return $licenses;
     }

     function get_license_group($prot, $index){
	
	 	$groups = array(	
				0 => array(0, "Frei von Rechten Dritter"), 
				1 => array(1, "Nicht frei von Rechten Dritter"),
			       2 => array(2, "Ungeklärt"),
				4 => array(4, "Lizenz liegt vor"),		//"Individuelle Lizenz liegt vor",
				5 => array(4, "Lizenz liegt vor"),		//"Campuslizenz, etc.",
			       6 => array(6, "§52a: Text"),
			       7 => array(7, "Frei nutzbar"),			//"Public Domain",
				8 => array(7, "Frei nutzbar"),			//"Schutzfirst abgelaufen",
				9 => array(7, "Frei nutzbar"),			//"CC",
				10 => array(7, "Frei nutzbar"),			//"Open Access",
			       12 => array(12, "Eigene Werke"),					//"Eigene: Rechte vorbehalten",
			       13 => array(12, "Eigene Werke"),					//"Eigene: CC",
			       14 => array(12, "Eigene Werke"),					//"Eigene: CC",
			       15 => array(12, "Eigene Werke"),					//"Eigene: Public Domain",
				18 => array(18, "§52a - kein Text"),               	//"$52a: Abbildung",
				19 => array(18, "§52a - kein Text"),  			//"$52a: Musikstück",
				20 => array(18, "§52a - kein Text"),  			//"$52a: Kinofilm",
				21 => array(18, "§52a - kein Text"),);			//"$52a: Notenedition",);
		
		return $groups[$prot][$index];

	}

	function get_license_group_ids(){
	
	 	$groups = array(	
				0 => 0, 
				1 => 1,
			       2 => 2,
				3 => 4,
				4 => 6,		
				5 => 7,		
			       6 => 12,
			       7 => 18);			
		
		return $groups;

	}


	function get_perms(){

		$ZweiundfuenfzigaPlugin_perms = array('autor', 'tutor', 'dozent');
		return $ZweiundfuenfzigaPlugin_perms;
	}

}

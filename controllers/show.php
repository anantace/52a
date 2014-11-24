<?php

class ShowController extends StudipController {


    

    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
    }

    public function before_filter(&$action, &$args) {

        $this->set_layout($GLOBALS['template_factory']->open('layouts/base_without_infobox'));
//      PageLayout::setTitle('');

    }

    public function index_action($inst, $perms, $sem_classes) {
	
	if ($inst == ""){
		$inst = "all";
	}
	if ($perms == ""){
		$perms = "all";
	}
	if ($sem_classes == ""){
		$sem_classes = "all";
	}


	$instArray = explode(" ", $inst);
	$permsArray = explode(" ", $perms);
	$semClassArray = explode(" ", $sem_classes);

	$licenses = $this->getLicenseCount($instArray, $permsArray, $semClassArray);
	
	$this->licenses = $licenses;
	$this->document_sum = $this->sum_entries($licenses, 'count');	
	$this->document_sum_known_licenses = $this->sum_entries_known_license($licenses, 'count');
	$this->institutes = DBQueries::getInstitutes();
	$this->seminar_classes = DBQueries::getSemClasses();

    }

	
    public function randomDocuments_action() {
	
	$this->answer = 'nichts';
	$this->documents = DBQueries::getRandomDocuments();		
    }


    // customized #url_for for plugins
    function url_for($to)
    {
        $args = func_get_args();

        # find params
        $params = array();
        if (is_array(end($args))) {
            $params = array_pop($args);
        }

        # urlencode all but the first argument
        $args = array_map('urlencode', $args);
        $args[0] = $to;

        return PluginEngine::getURL($this->dispatcher->plugin, $params, join('/', $args));
    } 


    function sum_entries($array, $field){
	$sum = 0;
	foreach ($array as $entry){
		$sum += $entry[$field];
	}
	return $sum;
    }

    function sum_entries_known_license($array, $field){
	$sum = 0;
	foreach ($array as $entry){
		if ($entry['prot'] != '2'){
			$sum += $entry[$field];
		}
	}
	return $sum;
    }

    function getLicenseCount($inst, $perms, $semClasses){
	return DBQueries::getLicenseCount($inst, $perms, $semClasses);
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

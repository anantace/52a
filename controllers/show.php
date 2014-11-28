<?php

class ShowController extends StudipController {


    

    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
    }

    public function before_filter(&$action, &$args) {

        $this->set_layout($GLOBALS['template_factory']->open('layouts/base_without_infobox'));

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

    public function instCompare_action($inst){
	
	//sidebar
	$all_institutes = DBQueries::getInstitutes();
	$this->institutes = $all_institutes;
	$this->seminar_classes = DBQueries::getSemClasses();

	$instArray = explode(" ", $inst);

	//for highchart index and to define order for series-data
	$selected_institutes = array();				

	//for SQL Query in case of empty Institutes-Selection
	if ($inst == "" || $inst == "all" ){
		$inst = "all";   					
		$selected_institutes = $all_institutes;     		
		$instArray = explode(" ", $inst);

	} else $selected_institutes = DBQueries::getInstitutesByID($instArray);
	

	$licenses = DBQueries::getLicenseCountForInstitutes($instArray);
	
	//presort for highchart-series
	$inst_results = array();
	foreach($licenses as $li){
		$inst_results[$li[inst]][$li[prot]] = $li[count];	
	}

	$this->compared_institutes = $selected_institutes;
	$this->institute_results = $inst_results;

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

  

}

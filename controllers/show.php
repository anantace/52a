<?php

class ShowController extends StudipController {

    public $group_licenses = false;
    

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

	$institutes = DBQueries::getInstitutes();
	$grouped_institutes = array();

	foreach($institutes as $i){
		if($i[id] == $i[fakultaets_id]){
			$grouped_institutes[$i[fakultaets_id]]['name'] = $i[name];
			$grouped_institutes[$i[fakultaets_id]]['id'] = $i[id];
		} else $grouped_institutes[$i[fakultaets_id]]['institutes'][$i[id]] = array('inst_id' =>$i[id], 'inst_name' => $i[name]);
	}

	
	$licenses_grouped = array();
	$licenses_grouped = $this->group_result($licenses, "prot", "count");


	$this->instArray = $instArray;
	$this->permsArray = $permsArray;
	$this->semClassArray = $semClassArray;

	$this->licenses = $licenses;
	$this->licenses_grouped = $licenses_grouped;
	$this->document_sum = $this->sum_entries($licenses, 'count');	
	$this->document_sum_known_licenses = $this->sum_entries_known_license($licenses, 'count');
	$this->institutes = $grouped_institutes;
	$this->seminar_classes = DBQueries::getSemClasses();

    }



    public function instCompare_action($inst, $perms, $sem_classes){

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

	
	//sidebar
	$institutes = DBQueries::getInstitutes();
	$grouped_institutes = array();

	foreach($institutes as $i){
		if($i[id] == $i[fakultaets_id]){
			$grouped_institutes[$i[fakultaets_id]]['name'] = $i[name];
			$grouped_institutes[$i[fakultaets_id]]['id'] = $i[id];
		} else $grouped_institutes[$i[fakultaets_id]]['institutes'][$i[id]] = array('inst_id' =>$i[id], 'inst_name' => $i[name]);
	}


	$this->institutes = $grouped_institutes;
	$this->seminar_classes = DBQueries::getSemClasses();


	//for highchart index and to define order for series-data
	$selected_institutes = array();				

	//for SQL Query in case of empty Institutes-Selection
	if ($inst == "" || $inst == "all" ){
		$inst = "all";   					
		$selected_institutes = $all_institutes;     		
		$instArray = explode(" ", $inst);

	} else $selected_institutes = DBQueries::getInstitutesByID($instArray);
	

	$licenses = DBQueries::getLicenseCountForInstitutes($instArray, $permsArray, $semClassArray);
	
	//presort for highchart-series
	$inst_results = array();
	$inst_results_grouped = array();

	foreach($licenses as $li){
		$inst_results[$li[inst]][$li[prot]] = $li[count];
		$inst_results_grouped[$li[inst_id]][$this->get_license_group($li[prot], 0)] += $li[count];	
	}

	$this->instArray = $instArray;
	$this->permsArray = $permsArray;
	$this->semClassArray = $semClassArray;

	$this->selected_institutes_ids = $instArray;

	$this->compared_institutes = $selected_institutes;
	$this->institute_results = $inst_results;
	$this->institute_results_grouped = $inst_results_grouped;


    }



    public function fakCompare_action($inst, $perms, $sem_classes){

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


	//sidebar
	$institutes = DBQueries::getFaculties();
	$grouped_institutes = array();

	foreach($institutes as $i){
		if($i[id] == $i[fakultaets_id]){
			$grouped_institutes[$i[fakultaets_id]]['name'] = $i[name];
			$grouped_institutes[$i[fakultaets_id]]['id'] = $i[id];
		} else $grouped_institutes[$i[fakultaets_id]]['institutes'][$i[id]] = array('inst_id' =>$i[id], 'inst_name' => $i[name]);
	}
	$this->institutes = $grouped_institutes;
	$this->seminar_classes = DBQueries::getSemClasses();


	//for highchart index and to define order for series-data
	$selected_institutes = array();				

	//for SQL Query in case of empty Faculty-Selection
	if ($inst == "" || $inst == "all" ){
		$inst = "all";   					
		$selected_institutes = DBQueries::getFaculties();    		
		$instArray = explode(" ", $inst);

	} else $selected_institutes = DBQueries::getFacultiesByID($instArray);
	

	$licenses = DBQueries::getLicenseCountForFaculties($instArray, $permsArray, $semClassArray);
	
	//presort for highchart-series
	$inst_results = array();
	$inst_results_grouped = array();

	foreach($licenses as $li){
		$inst_results[$li[fak_id]][$li[prot]] = $li[count];
		$inst_results_grouped[$li[fak_id]][$this->get_license_group($li[prot], 0)] += $li[count];	
	}

	$this->instArray = $instArray;
	$this->permsArray = $permsArray;
	$this->semClassArray = $semClassArray;

	$this->selected_institutes_ids = $instArray;

	$this->compared_institutes = $selected_institutes;
	$this->institute_results = $inst_results;
	$this->institute_results_grouped = $inst_results_grouped;


    }



     public function semClassCompare_action($inst, $perms, $sem_classes){

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

	
	//sidebar
	$all_semClasses = DBQueries::getSemClasses();
	$institutes = DBQueries::getInstitutes();
	$grouped_institutes = array();

	foreach($institutes as $i){
		if($i[id] == $i[fakultaets_id]){
			$grouped_institutes[$i[fakultaets_id]]['name'] = $i[name];
			$grouped_institutes[$i[fakultaets_id]]['id'] = $i[id];
		} else $grouped_institutes[$i[fakultaets_id]]['institutes'][$i[id]] = array('inst_id' =>$i[id], 'inst_name' => $i[name]);
	}

	$this->institutes = $grouped_institutes;
	$this->seminar_classes = $all_semClasses;


	//for highchart index and to define order for series-data
	$selected_semClasses = array();				

	//for SQL Query in case of empty SemClass-Selection
	if ($sem_classes == "" || $sem_classes == "all" ){
		$sem_classes = "all";   					
		$selected_semClasses = $all_semClasses;     		
		$semClassArray = explode(" ", $sem_classes);

	} else $selected_semClasses = DBQueries::getSemClassesByID($semClassArray);


	$licenses = DBQueries::getLicenseCountForSemClasses($instArray, $permsArray, $semClassArray);
	
	//presort for highchart-series
	$semClass_results = array();
	$semClass_results_grouped = array();

	foreach($licenses as $li){
		$semClass_results[$li[id]][$li[prot]] = $li[count];
		$semClass_results_grouped[$li[id]][$this->get_license_group($li[prot], 0)] += $li[count];	
	}

	$this->instArray = $instArray;
	$this->permsArray = $permsArray;
	$this->semClassArray = $semClassArray;

	$this->selected_semClass_ids = $semClassArray;

	$this->compared_semClasses = $selected_semClasses;
	$this->semClass_results = $semClass_results;
	$this->semClass_results_grouped = $semClass_results_grouped;


    }




    public function permsCompare_action($inst, $perms, $sem_classes){
	
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
	$permsArray = explode(" ", chop($perms));
	$semClassArray = explode(" ", $sem_classes);


	//sidebar
	$institutes = DBQueries::getInstitutes();
	$grouped_institutes = array();

	foreach($institutes as $i){
		if($i[id] == $i[fakultaets_id]){
			$grouped_institutes[$i[fakultaets_id]]['name'] = $i[name];
			$grouped_institutes[$i[fakultaets_id]]['id'] = $i[id];
		} else $grouped_institutes[$i[fakultaets_id]]['institutes'][$i[id]] = array('inst_id' =>$i[id], 'inst_name' => $i[name]);
	}
	$this->institutes = $grouped_institutes;
	$this->seminar_classes = DBQueries::getSemClasses();


	//for highchart index and to define order for series-data
	$selected_perms = array();				

	//for SQL Query in case of empty Perms-Selection
	if ($perms == "" || $perms == "all" ){
		$perms = "all";   					
		$selected_perms = array('autor', 'dozent', 'tutor', 'admin', 'root');     		
		$permsArray = explode(" ", chop($perms));

	} else $selected_perms = $permsArray;  //??
	

	$result = DBQueries::getLicenseCountForPerms($instArray, $permsArray, $semClassArray);
	
	//$result_grouped = array();
	//$result_grouped = $this->group_result($result, "prot", "count", "status");

	
	//presort for highchart-series
	$perms_results = array();
	$perms_results_grouped = array();

	foreach($result as $rs){
		if ($rs[status]!= NULL){
			$perms_results[$rs[status]][$rs[prot]] = $rs[count];	
			$perms_results_grouped[$rs[status]][$this->get_license_group($rs[prot], 0)] += $rs[count];
		} else {
			if($rs[perms] == 'admin' || $rs[perms] == 'root'){
				$perms_results[$rs[perms]][$rs[prot]] = $rs[count];	
				$perms_results_grouped[$rs[perms]][$this->get_license_group($rs[prot], 0)] += $rs[count];
			}

		}
	}
	


	$this->instArray = $instArray;
	$this->permsArray = $permsArray;
	$this->semClassArray = $semClassArray;
	
	//$this->selected_institutes_ids = $permsArray;
	$this->selected_perms = $permsArray;
	$this->perms = array('autor', 'tutor', 'dozent', 'admin', 'root');

	$this->compared_perms = $selected_perms;
	$this->perms_results = $perms_results;
	$this->perms_results_grouped = $perms_results_grouped;


    }

    public function uploads_action($inst, $perms, $sem_classes){

	
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
	

	$licenseArray = explode(" ", $license); 

	$uploads = DBQueries::getDailyUploads($instArray, $permsArray, $semClassArray);
	$uploads_week = DBQueries::getWeeklyUploads($instArray, $permsArray, $semClassArray);
	$reports = DBQueries::getReportsCount($instArray, $permsArray, $semClassArray);
	$reports_week = DBQueries::getReportsCountWeek($instArray, $permsArray, $semClassArray);
	$months = DBQueries::getMonths($instArray, $permsArray, $semClassArray);
	$weeks = DBQueries::getWeeks($instArray, $permsArray, $semClassArray);


	//calculate total uploads per month
	$uploads_total = array();
	foreach($uploads as $ul){
		$uploads_total[$ul[month]] += $ul[count];		
	}
	//calculate total uploads per week
	$uploads_total_week = array();
	foreach($uploads_week as $ul){
		$uploads_total_week[$ul[week]] += $ul[count];	
	}



	//presort for highchart-series
	$uploads_prot = array();
	$uploads_prot_week = array();
	$uploads_prot_grouped = array();
	$uploads_prot_grouped_week = array();

	foreach($uploads as $ul){
		$uploads_prot[$ul[month]][$ul[prot]] = $ul[count];
		$uploads_prot_grouped[$ul[month]][$this->get_license_group($ul[prot], 0)] += $ul[count];
	}
	foreach($uploads_week as $ul){
		$uploads_prot_week[$ul[week]][$ul[prot]] = $ul[count];
		$uploads_prot_grouped_week[$ul[week]][$this->get_license_group($ul[prot], 0)] += $ul[count];
	}


	$reports_sorted = array();
	foreach($reports as $r){
		$reports_sorted[$r[month]][$r[report_status]] = $r[count];	
	}
	$reports_sorted_week = array();
	foreach($reports_week as $rw){
		$reports_sorted_week[$rw[week]][$rw[report_status]] = $rw[count];	
	}

	$institutes = DBQueries::getInstitutes();
	$grouped_institutes = array();

	foreach($institutes as $i){
		if($i[id] == $i[fakultaets_id]){
			$grouped_institutes[$i[fakultaets_id]]['name'] = $i[name];
			$grouped_institutes[$i[fakultaets_id]]['id'] = $i[id];
		} else $grouped_institutes[$i[fakultaets_id]]['institutes'][$i[id]] = array('inst_id' =>$i[id], 'inst_name' => $i[name]);
	}


	$this->instArray = $instArray;
	$this->permsArray = $permsArray;
	$this->semClassArray = $semClassArray;


	$this->uploads_total = $uploads_total;
	$this->uploads_total_week = $uploads_total_week;
	$this->reports = $reports_sorted;
	$this->reports_week = $reports_sorted_week;
	$this->months = $months;
	$this->weeks = $weeks;	
	$this->uploads = $uploads_prot;
	$this->uploads_week = $uploads_prot_week;
	$this->uploads_grouped = $uploads_prot_grouped;
	$this->uploads_grouped_week = $uploads_prot_grouped_week;
	$this->institutes = $grouped_institutes;
	$this->seminar_classes = DBQueries::getSemClasses();

    }

     public function semCompare_action($inst, $perms, $sem_classes){

	
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
	
	$uploads = DBQueries::getSemUploads($instArray, $permsArray, $semClassArray);


	//presort for highchart-series
	$uploads_sem = array();

	foreach($uploads as $ul){
		$uploads_sem[$ul[sem]]= $ul[count];
	}

	$institutes = DBQueries::getInstitutes();
	$grouped_institutes = array();

	foreach($institutes as $i){
		if($i[id] == $i[fakultaets_id]){
			$grouped_institutes[$i[fakultaets_id]]['name'] = $i[name];
			$grouped_institutes[$i[fakultaets_id]]['id'] = $i[id];
		} else $grouped_institutes[$i[fakultaets_id]]['institutes'][$i[id]] = array('inst_id' =>$i[id], 'inst_name' => $i[name]);
	}


	$this->instArray = $instArray;
	$this->permsArray = $permsArray;
	$this->semClassArray = $semClassArray;

	$this->uploads_sem = array_reverse($uploads, true);
	$this->institutes = $grouped_institutes;
	$this->seminar_classes = DBQueries::getSemClasses();

    }

     public function semComparePerms_action($inst, $perms, $sem_classes){

	
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
	
	//for highchart index and to define order for series-data
	$selected_perms = array();				

	//for SQL Query in case of empty Perms-Selection
	if ($perms == "" || $perms == "all" ){
		$perms = "all";   					
		$selected_perms = array('autor', 'dozent', 'tutor', 'admin', 'root');     		
		$permsArray = explode(" ", chop($perms));

	} else $selected_perms = $permsArray; 

	$uploads = DBQueries::getSemUploadForPerms($instArray, $permsArray, $semClassArray);

	//presort for highchart-series
	$uploads_sem = array();

	foreach($uploads as $ul){
		if($ul[status]!= NULL){
			$uploads_sem[$ul[status]][$ul[sem]] = $ul[count];	
		} else {
			if($ul[perms] == 'admin' || $ul[perms] == 'root'){
				$uploads_sem[$ul[perms]][$ul[sem]] = $ul[count];
			}
		}
	}

	$institutes = DBQueries::getInstitutes();
	$grouped_institutes = array();

	foreach($institutes as $i){
		if($i[id] == $i[fakultaets_id]){
			$grouped_institutes[$i[fakultaets_id]]['name'] = $i[name];
			$grouped_institutes[$i[fakultaets_id]]['id'] = $i[id];
		} else $grouped_institutes[$i[fakultaets_id]]['institutes'][$i[id]] = array('inst_id' =>$i[id], 'inst_name' => $i[name]);
	}


	$this->instArray = $instArray;
	$this->permsArray = $permsArray;
	$this->semClassArray = $semClassArray;

	$this->selected_perms = $permsArray;
	$this->perms = array('autor', 'tutor', 'dozent', 'admin', 'root');

	$this->semester = array_reverse(DBQueries::getSemData(), true);
	$this->compared_perms = $selected_perms;
	$this->uploads_sem = array_reverse($uploads_sem, true);
	$this->institutes = $grouped_institutes;
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

    function selected($inst, $selected){
		if(in_array($inst, $selected)){
			return 'checked = "checked"';
		} else return "";
	}
  
    function group_result($result_array, $license_id, $value, $optDatafield = NULL){
	$return_array = array();

	foreach ($result_array as $r){ 
		$return_array[$this->get_license_group($r[$license_id], 0)][$value] += $r[$value];	
		$return_array[$this->get_license_group($r[$license_id], 0)][$license_id] = $r[$license_id]; 
		if($optDatafield){
		$return_array[$this->get_license_group($r[$license_id], 0)][$value] += $r[$value];	
		$return_array[$this->get_license_group($r[$license_id], 0)][$license_id] = $r[$license_id];
		$return_array[$this->get_license_group($r[$license_id], 0)][$optDatafield] = $r[$optDatafield];
		}
	}

   	return $return_array;
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

}

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

    public function index_action() {
	
	$licenses = DBQueries::getLicenseCount();
	$this->licenses = $licenses;
	$this->document_sum = $this->sum_entries($licenses, 'count');	
	$this->document_sum_known_licenses = $this->sum_entries_known_license($licenses, 'count');

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


}

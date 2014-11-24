<?php

require_once 'lib/classes/DBManager.class.php';

class DBQueries {

	public function __construct() {
	}

	function getRandomDocuments(){
		
		$query = "SELECT d.name AS name, CONCAT(d.dokument_id, d.filename) AS url
			FROM dokumente d
			LEFT JOIN seminare s ON d.seminar_id = s.Seminar_id
			ORDER BY RAND( )
			LIMIT 30";
                            
		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$documents = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $documents;
	}

	function getLicenseCount(){
		$query = "SELECT COUNT(*) as count, document_licenses.name as name, dokumente.protected as prot 
			FROM `dokumente` LEFT JOIN document_licenses ON (dokumente.protected = license_id) 
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200'
			GROUP BY dokumente.protected ORDER BY count DESC";

		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;
	}
 }
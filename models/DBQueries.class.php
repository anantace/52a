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

	function getLicenseCount($institutes, $perms, $sem_classes){

		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
		}
		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}



		$query = "SELECT COUNT(*) as count, document_licenses.name as name, dokumente.protected as prot 
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id
			WHERE dokumente.protected > 1
			$sql_perms	
			$sql_inst
			$sql_sem_classes		
			GROUP BY dokumente.protected ORDER BY count DESC";
		
		/**
		$query = "SELECT COUNT(*) as count, document_licenses.name as name, dokumente.protected as prot 
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id			
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200'
			$sql_perms	
			$sql_inst
			$sql_sem_classes
			GROUP BY dokumente.protected ORDER BY count DESC";
		**/


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;
	}

	function getInstitutes(){

		$query = "SELECT i.name AS name, i.Institut_id AS id
			FROM Institute i";
                            
		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$institute = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $institute;
	}

	function getSemClasses(){

		$query = "SELECT name, id			
			FROM sem_classes";
                            
		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$sem_classes = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $sem_classes;
	}

	function getLicenseCountForInstitutes($institutes){

		$sql_inst = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}


		$query = "SELECT COUNT(*) as count, document_licenses.name as name, dokumente.protected as prot, Institute.Name as inst, Institute.Institut_id as inst_id
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN Institute ON seminare.Institut_id = Institute.Institut_id
			WHERE dokumente.protected > 0	
			$sql_inst		
			GROUP BY dokumente.protected, Institute.Name ORDER BY prot DESC, inst_id DESC";		

		/**
		$query = "SELECT COUNT(*) as count, document_licenses.name as name, dokumente.protected as prot, seminare.Institut_id as inst 
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
						
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200'	
			$sql_inst		
			GROUP BY dokumente.protected ORDER BY count DESC";
		**/


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;
	}

	function getInstitutesByID($ids){

		$query = "SELECT i.name AS name, i.Institut_id AS id
			FROM Institute i WHERE i.Institut_id IN ('" . implode("','", $ids) . "')";
                            
		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$institute = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $institute;

	}

 }
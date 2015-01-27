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
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}
		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}



		$query = "SELECT COUNT(*) as count, document_licenses.name as name, dokumente.protected as prot 
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id

			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200' AND sem_types.class > 0

			$sql_perms	
			$sql_inst
			$sql_sem_classes		
			GROUP BY dokumente.protected ORDER BY count DESC";
		
		/**		
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200'
		**/


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;
	}


	function getInstitutes(){

		$query = "SELECT i.name AS name, i.Institut_id AS id, i.fakultaets_id
			FROM Institute i";
                            
		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$institute = $statement->fetchAll(PDO::FETCH_ASSOC);
	
		return $institute;
	}

	function getFaculties(){

		$query = "SELECT i.name AS name, i.Institut_id AS id, i.fakultaets_id
			FROM Institute i WHERE i.Institut_id = i.fakultaets_id";
                            
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


	function getLicenseCountForInstitutes($institutes, $perms, $sem_classes){

		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}

		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}


		$query = "SELECT COUNT(*) as count, document_licenses.name as name, dokumente.protected as prot, Institute.Name as inst, Institute.Institut_id as inst_id
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN Institute ON seminare.Institut_id = Institute.Institut_id
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id
					   
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200' AND sem_types.class > 0
	
		       $sql_perms
			$sql_inst
			$sql_sem_classes		
			GROUP BY dokumente.protected, Institute.Name ORDER BY prot DESC, inst_id DESC";		

		/**
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200'	
		**/


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;
	}

	function getLicenseCountForFaculties($institutes, $perms, $sem_classes){

		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}

		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}


		$query = "SELECT COUNT(*) as count, document_licenses.name as name, dokumente.protected as prot, Institute.Name as inst, Institute.fakultaets_id as fak_id
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN Institute ON seminare.Institut_id = Institute.Institut_id
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id
					   
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200' AND sem_types.class > 0 
	
		       $sql_perms
			$sql_inst
			$sql_sem_classes		
			GROUP BY Institute.fakultaets_id, dokumente.protected ORDER BY prot DESC";		

		/**
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200'	
		**/


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;
	}



	function getLicenseCountForPerms($institutes, $perms, $sem_classes){

		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}

		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}


		$query = "SELECT COUNT(*) as count, document_licenses.name as name, dokumente.protected as prot, su.status, au.perms
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN Institute ON seminare.Institut_id = Institute.Institut_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id


			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200' AND sem_types.class > 0

		
			$sql_perms	
			$sql_inst
			$sql_sem_classes	
			GROUP BY dokumente.protected, su.status ORDER BY su.status ASC, count DESC";		

		/**	
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200'	
		**/


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;
	}


	function getLicenseCountForSemClasses($institutes, $perms, $sem_classes){

		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}
		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}


		$query = "SELECT COUNT(*) as count, document_licenses.name as name, dokumente.protected as prot, sem_classes.id as id, sem_classes.name as classname
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN Institute ON seminare.Institut_id = Institute.Institut_id
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id
					   LEFT JOIN sem_classes ON sem_classes.id = sem_types.class
					   
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200' AND sem_types.class > 0
	
		       $sql_perms
			$sql_inst
			$sql_sem_classes		
			GROUP BY dokumente.protected, sem_classes.id ORDER BY prot DESC, id DESC";		

		/**
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200'	
		**/


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;
	}


	function getDailyUploads($institutes, $perms, $sem_classes){

		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}
		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}
			

		$query = "SELECT COUNT(*) as count, document_licenses.name as name, dokumente.protected as prot, FROM_UNIXTIME(dokumente.mkdate, '%y/%m') AS month
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id

			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200' AND sem_types.class > 0

			$sql_perms	
			$sql_inst
			$sql_sem_classes		
			GROUP BY MONTH(FROM_UNIXTIME(dokumente.mkdate)), YEAR(FROM_UNIXTIME(dokumente.mkdate)), dokumente.protected
			ORDER BY month ASC";

		/**				
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200'	
		**/


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;

	}


	function getWeeklyUploads($institutes, $perms, $sem_classes){

		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}
		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}
			

		$query = "SELECT COUNT(*) as count, document_licenses.name as name, dokumente.protected as prot, CONCAT(WEEK(FROM_UNIXTIME(dokumente.mkdate), 3), '/', FROM_UNIXTIME(dokumente.mkdate, '%y')) AS week
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id

			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200' AND sem_types.class > 0
	
			$sql_perms	
			$sql_inst
			$sql_sem_classes		
			GROUP BY CONCAT(WEEK(FROM_UNIXTIME(dokumente.mkdate), 3), '/', FROM_UNIXTIME(dokumente.mkdate, '%y')), dokumente.protected
			ORDER BY FROM_UNIXTIME(dokumente.mkdate, '%y'), WEEK(FROM_UNIXTIME(dokumente.mkdate), 3) ASC";

		/**
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200'	
		**/


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;

	}


	function getSemUploads($institutes, $perms, $sem_classes){

		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}
		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}
			

		$query = "SELECT COUNT(*) as count, sd.semester_id as sem_id, sd.name as sem
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN semester_data sd ON (FROM_UNIXTIME(dokumente.mkdate) BETWEEN FROM_UNIXTIME(sd.beginn) AND FROM_UNIXTIME(sd.ende))				   
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id
	
			WHERE dokumente.protected > -1 AND sem_types.class > 0

			$sql_perms	
			$sql_inst
			$sql_sem_classes		
			GROUP BY sem_id
			ORDER BY sd.beginn DESC LIMIT 0, 20";

		/**		
			SELECT document_licenses.name as name, dokumente.protected as prot, sd.semester_id as sem_id, sd.name as sem, FROM_UNIXTIME(dokumente.mkdate) as date
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN semester_data sd ON (dokumente.mkdate BETWEEN sd.beginn AND sd.ende)
                                           LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id

			WHERE dokumente.protected > 2
			AND sd.name NOT IN ('WS 2014/15')
			
			ORDER BY sd.beginn DESC
		
		**/


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;

	}


	function getSemUploadForPerms($institutes, $perms, $sem_classes){

		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}
		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}


		$query = "SELECT COUNT(*) as count, sd.semester_id as sem_id, sd.name as sem, su.status, au.perms
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN semester_data sd ON (FROM_UNIXTIME(dokumente.mkdate) BETWEEN FROM_UNIXTIME(sd.beginn) AND FROM_UNIXTIME(sd.ende))				   
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id
	
			WHERE dokumente.protected > -1 AND sem_types.class > 0

			$sql_perms	
			$sql_inst
			$sql_sem_classes		
			GROUP BY sem_id, su.status
			ORDER BY sd.beginn DESC LIMIT 0, 100";		


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;
	}



	function getMonths($institutes, $perms, $sem_classes){
		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}
		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}
			

		$query = "SELECT FROM_UNIXTIME(dokumente.mkdate, '%y/%m') AS id, FROM_UNIXTIME(dokumente.mkdate, '%M') AS name
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id


			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200' AND sem_types.class > 0

			$sql_perms	
			$sql_inst
			$sql_sem_classes		
			GROUP BY MONTH(FROM_UNIXTIME(dokumente.mkdate)), YEAR(FROM_UNIXTIME(dokumente.mkdate)) 
			ORDER BY id ASC";

		/**	
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200' 	
		**/


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;

	}


	function getWeeks($institutes, $perms, $sem_classes){
		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}
		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}
			

		$query = "SELECT CONCAT(WEEK(FROM_UNIXTIME(dokumente.mkdate), 3), '/', FROM_UNIXTIME(dokumente.mkdate, '%y')) AS id, WEEK(FROM_UNIXTIME(dokumente.mkdate), 3) AS name
			FROM `dokumente` LEFT JOIN document_licenses ON dokumente.protected = license_id 
					   LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id


			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200' AND sem_types.class > 0
			
			$sql_perms	
			$sql_inst
			$sql_sem_classes		
			GROUP BY CONCAT(WEEK(FROM_UNIXTIME(dokumente.mkdate), 3), '/', FROM_UNIXTIME(dokumente.mkdate, '%y')) 
			ORDER BY FROM_UNIXTIME(dokumente.mkdate, '%y'), WEEK(FROM_UNIXTIME(dokumente.mkdate), 3) ASC";

		/**
			WHERE dokumente.protected > 1 AND dokumente.mkdate>= '1412935200'	
		**/


		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;

	}



	function getReportsCount($institutes, $perms, $sem_classes){

		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}
		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}



		$query = "SELECT COUNT(*) as count, dr.status as report_status, FROM_UNIXTIME(dokumente.mkdate, '%y/%m') as month, TIMESTAMPDIFF(MINUTE,dokumente.mkdate,dr.mkdate)as 'Minuten zwischen Upload und Meldung'
			FROM `dokumente` LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN document_reports dr ON dokumente.user_id = dr.user_id
					   AND dokumente.seminar_id = dr.seminar_id AND dokumente.dokument_id = dr.document_id
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id

			WHERE dokumente.protected = 6 AND dokumente.mkdate>= '1412935200' AND sem_types.class > 0
			$sql_perms	
			$sql_inst
			$sql_sem_classes			
			GROUP BY month, dr.status ORDER BY month ASC, count DESC";
		

		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;
	}


	function getReportsCountWeek($institutes, $perms, $sem_classes){

		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}
		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}

		$query = "SELECT COUNT(*) as count, dr.status as report_status, CONCAT(WEEK(FROM_UNIXTIME(dokumente.mkdate), 3), '/', FROM_UNIXTIME(dokumente.mkdate, '%y')) AS week, TIMESTAMPDIFF(MINUTE,dokumente.mkdate,dr.mkdate)as 'Minuten zwischen Upload und Meldung'
			FROM `dokumente` LEFT JOIN seminar_user su ON dokumente.user_id = su.user_id
					   AND dokumente.seminar_id = su.seminar_id
					   LEFT JOIN auth_user_md5 au ON dokumente.user_id = au.user_id
					   LEFT JOIN document_reports dr ON dokumente.user_id = dr.user_id
					   AND dokumente.seminar_id = dr.seminar_id AND dokumente.dokument_id = dr.document_id
					   LEFT JOIN seminare ON seminare.Seminar_id = dokumente.seminar_id
					   LEFT JOIN sem_types ON seminare.status = sem_types.id

			WHERE dokumente.protected = 6 AND dokumente.mkdate>= '1412935200' AND sem_types.class > 0

			$sql_perms	
			$sql_inst
			$sql_sem_classes			
			GROUP BY CONCAT(WEEK(FROM_UNIXTIME(dokumente.mkdate), 3), '/', FROM_UNIXTIME(dokumente.mkdate, '%y')), dr.status 
			ORDER BY FROM_UNIXTIME(dokumente.mkdate, '%y'), WEEK(FROM_UNIXTIME(dokumente.mkdate), 3) ASC, count DESC";

		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$list = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $list;
	}


	function getSemData(){

		$query = "SELECT * FROM `semester_data`
			ORDER BY beginn DESC LIMIT 0, 20";

		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$semData = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $semData;

	}


	function getAbandonedReports(){
		
		$sql_perms = "";
		$sql_inst = "";
		$sql_sem_classes = "";


		if ($institutes[0] != "all"){
			$sql_inst = "AND seminare.Institut_id IN ('" . implode("','", $institutes) . "')";
		}
		if ($perms[0] != "all"){
			if (!in_array('admin', $perms)){		//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung
				$sql_perms = "AND su.status IN ('" . implode("','", $perms) . "')";
			} else if (count($perms)==1){		//user ist Systemadmin
				$sql_perms = "AND au.perms IN ('admin')";
			} else 					//user ist Dozent, Tutor oder Teilnehmer der Veranstaltung oder Systemadmin
				$sql_perms = "AND ((au.perms IN ('admin', 'root')) OR (su.status IN ('" . implode("','", $perms) . "')))";
			
		}
		if ($sem_classes[0] != "all"){
			$sql_sem_classes = "AND sem_types.class IN ('" . implode("','", $sem_classes) . "')";
		}



		$query = "SELECT COUNT(*) AS count, dokumente.protected as prot  
			FROM `document_reports` 
				LEFT JOIN dokumente ON dokumente.Dokument_id = document_reports.document_id 

			WHERE dokumente.protected != 6 AND dokumente.mkdate>= '1412935200' AND sem_types.class > 0

			$sql_perms	
			$sql_inst
			$sql_sem_classes			
			GROUP BY dokumente.protected";
		

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

	function getFacultiesByID($ids){

		$query = "SELECT i.name AS name, i.Institut_id AS id
			FROM Institute i WHERE i.Institut_id IN ('" . implode("','", $ids) . "') AND i.Institut_id = i.fakultaets_id";
                            
		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$institute = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $institute;

	}



	function getSemClassesByID($ids){

		$query = "SELECT sc.name, sc.id			
			FROM sem_classes sc WHERE sc.id IN ('" . implode("','", $ids) . "')";
                            
		$statement = DBManager::get()->prepare($query);
		$statement->execute();
		$semClasses = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $semClasses;

	}


 }
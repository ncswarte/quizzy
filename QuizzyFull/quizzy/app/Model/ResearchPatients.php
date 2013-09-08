<?php

class ResearchPatients extends Model {

	public $useTable = 'researchpatients';

	// "Default" load all
	public function loadAllData() {
		return $this->find('all');
	}
	
	// Load a specific research's patient list
	public function loadResearchPatients( $researchID ) {
		$temp = $this->find('all', array(
			'conditions' => array('ResearchPatients.researchid' => $researchID) ));
			
		$tempRet = array();
		foreach ( $temp as $currPat ) {
			array_push($tempRet, $currPat['ResearchPatients']['patID'] );
		}
		
		return $tempRet;
	}
	
	// Is a specific patient part of a specific research?
	public function isPatientOfResearch( $patID, $researchID ) {

		$temp = $this->find('all', array(
			'conditions' => array('ResearchPatients.patID' => $patID, 'ResearchPatients.researchID' => $researchID) ));
		
		if( sizeof($temp) == 1 ) {
			return true;
		} else {
			return false;
		}
	}

}

?>

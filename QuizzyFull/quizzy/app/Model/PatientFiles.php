<?php

class PatientFiles extends Model {

	public $useTable = 'patientfiles';
	public $primaryKey = 'fileID';

	// "Default" load all
	public function loadAllData() {
		return $this->find('all');
		
	}
	
	// Return all patient's files
	public function loadPatientFiles( $patID = null ) {

		$temp = $this->find('all', array(
			'conditions' => array('PatientFiles.patID' => $patID) ));
		return $temp;
	}
	
	// Return specific file ID data
	public function loadFileID( $fileID = null ) {

		$temp = $this->find('all', array(
			'conditions' => array('PatientFiles.fileID' => $fileID) ));
		
		return $temp;
	}

}

?>

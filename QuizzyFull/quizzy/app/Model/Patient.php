<?php

class Patient extends Model {

	public $useTable = 'patient';
	public $primaryKey = 'patID';
	public $virtualFields = array(
		'id' => "Patient.patID"
	);

	// "Default" load all
	public function loadAllData() {
		return $this->find('all');
	}
	
	// Load a specific patient with all info
	public function loadPatient( $patID ) {
        return $this->find('all', array(
            'conditions' => array('Patient.patID' => $patID) ));
    }
	
	// Load an array of patients
	public function loadPatientList( $arrList ) {
		return ( $this->find('all', array( 'conditions' => array(
				"Patient.patID" => $arrList )
		)));
	}
	
	// Load the patients with ID, [Firstname Lastname] for convenience
	public function loadPatientsAsList() {
		return $this->find('all', array(
			'fields' => array('Patient.patID', 'CONCAT(Patient.patFirstname," ", Patient.patLastname) as patName')));
    }

}

?>

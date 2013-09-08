<?php

class Research extends Model {

	public $useTable = 'research';
	public $primaryKey = 'researchID';
	public $virtualFields = array(
		'id' => "Research.researchID"
	);
	// public $hasMany = array(
        // 'Quiz' => array(
            // 'className' => 'Quiz' ) );

	// "Default" load all
	public function loadAllData() {
		return $this->find('all');
	}
	
	// Load a specific research
	public function loadResearch( $researchID ) {
        return $this->find('all', array(
            'conditions' => array('Research.researchID' => $researchID) ));
    }
}

?>

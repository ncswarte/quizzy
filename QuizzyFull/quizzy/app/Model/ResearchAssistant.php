<?php

class ResearchAssistant extends Model {

	public $useTable = 'researchassistants';

	// "Default" load all
	public function loadAllData() {
		return $this->find('all');
	}
	
	// Return all patient's quizzes
	public function loadResearcheAssistants( $researchID ) {
		return $this->find('all', array(
			'conditions' => array('ResearchAssistant.researchID' => $researchID) ));
	}
	
	// Return all patient's quizzes
	public function loadAssistantResearches( $assistantID ) {
		return $this->find('all', array(
			'conditions' => array('ResearchAssistant.assistantID' => $assistantID) ));
	}
}

?>

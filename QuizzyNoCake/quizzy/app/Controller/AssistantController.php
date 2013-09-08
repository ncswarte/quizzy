<?php

class AssistantController extends AppController {

	public function isAuthorized($user) {
		// Only patients can access this page my dear!
		//		if you want to debug the authorization functions - debug( 'Assistant: isAuthorized');
		
		if( $user['role'] == 'assistant' )
			return true;
		
		return false;
	}

	public function index() {
		$this->set('title_for_layout', __('Assistant Area') );
		
		$tempID = $this->Session->read('Auth.User');
		if( $tempID == null )
			return;
		$assistantID = $tempID['id'];
		
		$this->loadModel('Research');
		$this->loadModel('ResearchAssistant');
		
		$tempResearchList = $this->ResearchAssistant->loadAssistantResearches( $assistantID );
		$tempResearchData = array();
		foreach( $tempResearchList as $currentResearch ) {
			$tempData = $this->Research->loadResearch( $currentResearch['ResearchAssistant']['researchID'] );
			// Shouldn't ever occur, just incase someone tampers with the DB data
			if( !empty($tempData) )
				$tempResearchData[$currentResearch['ResearchAssistant']['researchID']] = $this->Research->loadResearch( $currentResearch['ResearchAssistant']['researchID'] )['0'];
		}
		$this->set('arrResearches', $tempResearchData );

		if ( !empty($this->data) ) {
			$this->Session->write('User.currentResearch', $this->data['research']);
		}
		$tempSelectedResearch = $this->Session->read('User.currentResearch');
		
		// This should never happen as it won't even be listed, but just in case you can use it
		if( $this->isAssistantOnResearch( $assistantID, $tempSelectedResearch ) == false ) {
			//$this->Session->setFlash(__('You are not authorized for this research!', true));
			return;
		}
		
		if( !empty( $tempSelectedResearch ) ) {
			
			$this->loadModel('ResearchPatients');
			$this->loadModel('PatientQuiz');
			$this->loadModel('Patient');
			$this->loadModel('Quiz');
			
			$tempPatientList 		= $this->ResearchPatients->loadResearchPatients( $tempSelectedResearch) ;
			$tempQuizzes	 		= $this->Quiz->loadResearchQuizzes( $tempSelectedResearch );
			$tempPatientsQuizzes 	= array();
			foreach( $tempPatientList as $currPatient ) {
				$tempPatientsQuizzes[$currPatient] = $this->PatientQuiz->loadPatientQuizzes( $currPatient );
			}
			
			$this->set('arrPatients', ($this->Patient->loadPatientList($tempPatientList)) );
			$this->set('arrPatientsQuizzes', $tempPatientsQuizzes );
			$this->set('arrQuizzes', $tempQuizzes );
			
		} else {
			// They must choose one!
		}
	}
	
	private function isAssistantOnResearch( $assistantID, $researchID ) {
		$this->loadModel('Research');
		$this->loadModel('ResearchAssistant');
		
		$tempResearchList = $this->ResearchAssistant->loadAssistantResearches( $assistantID );
		$tempResearchData = array();
		foreach( $tempResearchList as $currentResearch ) {
			$tempData = $this->Research->loadResearch( $currentResearch['ResearchAssistant']['researchID'] );
			// Shouldn't ever occur, just incase someone tampers with the DB data
			if( !empty($tempData) )
				$tempResearchData[$currentResearch['ResearchAssistant']['researchID']] = $this->Research->loadResearch( $currentResearch['ResearchAssistant']['researchID'] )['0'];
		}
		
		if( !empty( $researchID ) && empty( $tempResearchData[$researchID] ) ) {
			$this->Session->setFlash(__('You are not authorized for this research!', true));	
			return false;
		} else {
			return true;
		}
	}
	
}

?>
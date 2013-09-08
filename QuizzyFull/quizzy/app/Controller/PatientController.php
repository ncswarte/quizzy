<?php

class PatientController extends AppController {
	
	public function isAuthorized($user) {
		// Only patients can access this page my dear!
		//		if you want to debug the authorization functions - debug( 'PatientController: isAuthorized');
		
		if( $user['role'] == 'patient' )
			return true;
		
		return false;
	}
	
	public function index() {
		
		$this->set('title_for_layout', __('Patient Area') );
		
		// TODO check if patient number exists?
		$tempID = $this->Session->read('Auth.User');
		if( $tempID == null )
			return;
		
		$patID = $tempID['username'];
		
		$this->loadModel('Patient');
		$this->loadModel('Quiz');
		$this->loadModel('PatientQuiz');
		
		$tempQuizData = array();
		foreach($this->Quiz->loadAllData() as $currQuiz ) {
			$tempQuizData[ $currQuiz['Quiz']['quizID'] ] = $currQuiz['Research']['researchName'];
		}
		
		$this->set('patInfo',  $this->Patient->loadPatient( $patID ) );
		$this->set('researchInfo', $tempQuizData );
		$tempPatientQuizList = $this->PatientQuiz->loadPatientQuizzes( $patID );
		$this->set('patQuiz',  $tempPatientQuizList );
		
		// Filter out the taken and only give it the list of actual Quiz IDs
		$tempQuizList = array();
		foreach( $tempPatientQuizList as $tempCurrQuiz ) {
			array_push( $tempQuizList, $tempCurrQuiz['0'] );
		}
		$this->set('quizInfo', $this->Quiz->loadQuizListFiltered( $tempQuizList ) );
	}
	
}

?>
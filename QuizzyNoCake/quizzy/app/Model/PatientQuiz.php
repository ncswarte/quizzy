<?php

class PatientQuiz extends Model {

	public $useTable = 'patientquiz';

	// "Default" load all
	public function loadAllData() {
		return $this->find('all');
	}
	
	// Return all patient's quizzes
	public function loadPatientQuizzes( $patID = null ) {

		$temp = $this->find('all', array(
			'conditions' => array('PatientQuiz.patID' => $patID) ));
		
		$tempRet = array();
		foreach ( $temp as $currQuiz ) {
			
			//PHP 5.4 vs Older [$temp2]
			$temp2 = array();
			array_push( $temp2 , $currQuiz['PatientQuiz']['quizID'] );
			array_push( $temp2 , $currQuiz['PatientQuiz']['quizTaken'] );
			
			// Will create entities: [Quiz ID, Taken]
			array_push($tempRet, $temp2 );
		}
		
		return $tempRet;
	}
	
	// Return ID for specific patient and quiz combo
	public function getPatientQuizID( $patID, $quizID ) {

		$temp = $this->find('all', array(
			'conditions' => array('PatientQuiz.patID' => $patID, 'PatientQuiz.quizID' => $quizID) ));
		
		return $temp['0']['PatientQuiz']['id'];
	}
	
	// Has a user taken a specific quiz?
	public function hasPatientTaken( $patID, $quizID ) {

		$temp = $this->find('all', array(
			'conditions' => array('PatientQuiz.patID' => $patID, 'PatientQuiz.quizID' => $quizID) ));
		
		// We shouldn't reach here (i.e. only query this if they've been allocated)
		if( empty( $temp ) )
			return false;
		
		// Handle the actual return...
		if( $temp['0']['PatientQuiz']['quizTaken'] == '1' ) {
			return true;
		} else {
			return false;
		}
	}

}

?>

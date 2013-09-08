<?php
class QuizController extends AppController {

	public function isAuthorized($user) {
		// Only patients can access this page my dear!
		if( $user['role'] == 'patient' )
			return true;
		
		return false;
	}

	public function index( $patID = null, $quizID = null ) {
	
		$this->set('title_for_layout', __('Quiz Area') );
		
		// Validation of Patient/Quiz
		$this->loadModel('PatientQuiz');
		
		// Patient was NOT allocated this one!
		if( is_null( $this->PatientQuiz->getPatientQuizID( $patID, $quizID ) ) ) {
			$this->Session->setFlash(__('ERROR - Unallocated quiz!', true));
			$this->redirect(array('controller' => 'Patient', 'action' => 'index' ) );
			exit;
		}
		
		// Patient already completed it!
		if( $this->PatientQuiz->hasPatientTaken( $patID, $quizID ) == true ) {
			$this->Session->setFlash(__('ERROR - Quiz already completed!', true));
			$this->redirect(array('controller' => 'Patient', 'action' => 'index' ) );
			exit;
		}
		
		// We've got some answers at hand
		if ( !empty($this->data) ) {
			$this->loadModel('Answers');
			
			// K = Question Num;	V = Response
			foreach( $this->data['answers'] as $currPartK => $currPartV ) {
				
				$thisAnswer = array( 'patID' => $this->data['patID'],
					'quizID' => $this->data['quizID'],
					'answerDate' => date('Y-m-d H:i:s'),
					'questionID' => $currPartK,
					'questionType' => '?',
					'questionAnswer' => $currPartV);
				
				$this->Answers->create();
				$this->Answers->save($thisAnswer);
			}
			
			$this->loadModel('PatientQuiz');
			$tempID = $this->PatientQuiz->getPatientQuizID($this->data['patID'], $this->data['quizID'] );
			$this->PatientQuiz->save(array( 'id' => $tempID,
					'patID' => $this->data['patID'],
					'quizID' => $this->data['quizID'],
					'quizTaken' => '1'));
			
			$this->Session->setFlash(__('Quiz saved successfully!', true));
			$this->redirect(array('controller' => 'Patient', 'action' => 'index', $patID ) );
			
		}
		
		
		// Reaching here means we need to load the Q etc.
		$this->loadModel('Quiz');
		$currentQuiz = $this->Quiz->loadQuiz( $quizID );
		$currentQuiz = $currentQuiz['0'];
		
		$this->set('patQuiz', $currentQuiz );
		$this->set('patID',   $patID );
		$this->set('quizID',  $quizID );
	}
	
}

?>
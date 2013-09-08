<?php

class AnalysisController extends AppController {

	public function isAuthorized($user) {
		// Only admin or assistants
		if( $user['role'] == "assistant" || $user['role'] == "admin" ) {
			return true;
		} else {
			return false;
		}
	}

	public function index() {
		
		$this->set('title_for_layout', __('Analysis Area') );
		$this->loadModel('Quiz');
		$this->loadModel('Patient');
		$this->loadModel('Research');
		$this->loadModel('ResearchPatients');
		$this->set('userData', $this->Auth->user() );
		
		if( $this->Session->check('User.currentResearch') == false ) {
			$this->redirect(array('controller' => $this->Auth->user()['role'], 'action' => 'index'));
		}
		$tempSelectedResearch = $this->Session->read('User.currentResearch');
		$this->set( 'researchInfo', $this->Research->loadResearch( $tempSelectedResearch )['0'] );
		
		// Load only the research's quizzes!
		$this->set('quizList', $this->Quiz->loadQuizResearchList( $tempSelectedResearch ) );
		
		// Load only the research's patients and their names...
		$temp1 = $this->Patient->loadPatientsAsList();
		$temp2 = $this->ResearchPatients->loadResearchPatients( $tempSelectedResearch );
		$tempResearchPats = array();
		foreach( $temp2 as $curr ) {
			$tempResearchPats[ $curr ] = $curr;
		}
		foreach( $temp1 as $curr => $currV ) {
			if( !isset( $tempResearchPats[$currV['Patient']['patID']] ) )
				unset( $temp1[$curr] );
		}
		$this->set('patList', $temp1 );
		
		
		//Choose filter, choose columns
		if ($this->request->is('post')) {
			
			$this->loadModel('Answers');
			
			if( $this->request->data['selType'] == "Quiz" ) {
				$this->set('answerData', $this->Answers->loadListByQuiz( $this->request->data['quiz'] ) );
				$this->set('currDisplay', 'quiz');
				$this->set('currSelected', $this->request->data['quiz'] );
			} elseif( $this->request->data['selType'] == "Users" ) {
				$this->set('answerData', $this->Answers->loadListByUser( $this->request->data['patient'] ) );
				$this->set('currDisplay', 'users');
				$this->set('currSelected', $this->request->data['patient'] );
			}
			
		} else {
			$this->set('currDisplay', '');
			$this->set('quizID', '' );
			$this->set('patID', '' );
		}
	}
}

?>
<?php

class Quiz extends Model {

	public $useTable = 'quiz';
	public $primaryKey = 'quizID';
	public $belongsTo = array(
		'Research' => array(
			'className' => 'Research',
			'foreignKey' => 'researchID' ) );
	public $hasMany = array(
		'Questions' => array(
			'className' => 'Questions',
			'foreignKey' => 'quizID' ) );
	
	// "Default" load all
	public function loadAllData() {
		return $this->find('all');
	}
	
	// Load reasearch's quizzes
	public function loadResearchQuizzes( $researchID ) {
		return $this->find('all', array(
			'conditions' => array(
				array('Quiz.researchID' => $researchID)
			)));
	}
	
	// Load specific quiz
	public function loadQuiz( $quizID = null ) {
		return $this->find('all', array(
			'conditions' => array(
				array('Quiz.quizID' => $quizID)
			)));
	}
	
	// Fetch a list of all ID, Title
	public function loadQuizList() {
		return $this->find('all', array(
			'fields' => array('Quiz.quizID', 'Quiz.quizTitle')));
	}
	
	// Fetch a list of all research quizzes with ID, Title
	public function loadQuizResearchList( $researchID ) {
		return $this->find('all', array(
			'fields' => array('Quiz.quizID', 'Quiz.quizTitle'),
			'conditions' => array( array('Quiz.researchID' => $researchID) )
		) );
	}
	
	// Fetch a list of specific ID, Title
	public function loadQuizListFiltered( $arrList ) {
		$tempReturnArray = array();
		$temp = $this->find('all', array( 
			'conditions' => array( "Quiz.quizID" => $arrList ),
			'fields' => array('Quiz.quizID', 'Quiz.quizTitle') ) );
		
		// Orginize them $arr[ quizID ] = $quiz...
		foreach( $temp as $currQuiz ) {
			$tempReturnArray[ $currQuiz['Quiz']['quizID'] ] = $currQuiz;
		}
		
		return $tempReturnArray;
	}
}

?>

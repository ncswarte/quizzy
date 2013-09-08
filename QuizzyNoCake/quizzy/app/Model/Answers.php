<?php

class Answers extends Model {

	public $name = 'Answers';
	public $useTable = 'answers';
	public $belongsTo = array(
		'MyQuestion' => array(
			'className' => 'Questions',
			'foreignKey' => 'questionID' ) );
	
	// "Default" load all
	public function loadAllData() {
		return $this->find('all');
	}
	
	// Load a set of question answers
	public function loadMultiQA ( $arrQuestionList ) {
		return $this->find('all', array( 'conditions' => array(
			"Answers.questionID" => $arrQuestionList ) ) );
	}
	
	// Load a specific quiz's answers (results)
	public function loadQuizRes ( $quizID ) {
		return $this->find('all', 
			array( 'conditions' => array("Answers.quizID" => $quizID ),
				'fields' => array("Answers.questionID", "Answers.questionAnswer", "COUNT(Answers.questionAnswer) as cnt" ),
				'group' => array("Answers.questionID", "Answers.questionAnswer")
				) );
	}

	// Load a specific patient's answers (results)
	public function loadPatRes ( $patID ) {
		return $this->find('all', 
			array( 'conditions' => array("Answers.patID" => $patID ),
				'fields' => array("Answers.questionID", "Answers.questionAnswer", "COUNT(Answers.questionAnswer) as cnt" ),
				'group' => array("Answers.questionID", "Answers.questionAnswer")
				) );
	}

	// On these we want raw data and thus, no count, no group
	public function loadListByQuestion( $arrList ) {	
		return $this->find('all', 
			array( 'conditions' => array( "Answers.questionID" => $arrList ),
				'fields' => array("Answers.questionID", "Answers.questionAnswer", "Answers.quizID", "Answers.patID" )
				) );
	}

	// Load data according to a set of quizIDs
	public function loadListByQuiz( $arrList ) {	
		return $this->find('all', 
			array( 'conditions' => array( "Answers.quizID" => $arrList ),
				'fields' => array("Answers.questionID", "Answers.questionAnswer", "Answers.quizID", "Answers.patID" )
				) );
	}	
	
	// Load data according to a set of patientIDs
	public function loadListByUser( $arrList ) {	
		return $this->find('all', 
			array( 'conditions' => array( "Answers.patID" => $arrList ),
				'fields' => array("Answers.questionID", "Answers.questionAnswer", "Answers.quizID", "Answers.patID" )
				) );
	}
}

?>

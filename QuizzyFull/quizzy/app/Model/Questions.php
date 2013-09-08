<?php

class Questions extends Model {

	public $useTable = 'questions';
	public $primaryKey = 'questionID';
	public $belongsTo = array(
		'Quiz' => array(
			'className' => 'Quiz',
			'foreignKey' => 'quizID' ) );
	
	public function loadAllData() {
		return $this->find('all');
	}
	
	public function loadSpecific( $arrList ) {
		return ( $this->find('all', array( 'conditions' => array(
				"Questions.questionID" => $arrList )
		)));
	}
	
}

?>

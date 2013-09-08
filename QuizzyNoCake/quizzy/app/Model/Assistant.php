<?php

class Assistant extends Model {

	public $useTable = 'assistant';
	public $primaryKey = 'assistantID';
	public $virtualFields = array(
		'id' => "Assistant.assistantID"
	);

	// "Default" load all
	public function loadAllData() {
		return $this->find('all');
	}
}

?>

<h2>Import quiz:</h2>
<?php
	$tempPatients = array();
	$tempPatients['NONE'] = "Please choose a quiz";
	foreach ( $arrQuizzes as $currPatient ) {
		$tempPatients[$currPatient['Quiz']['quizID']] = '['.$currPatient['Quiz']['quizID'].'] '.$currPatient['Quiz']['quizTitle'];
	}
	
	echo $this->Form->create(false ,array('url' => array('controller' => 'Admin', 'action' => 'importQuiz')));	
	echo $this->Form->input('patID', array('options' => $tempPatients, 'default' => 'NONE', 'label' => 'Available quizzes'));
	echo $this->Form->end('Import');
?>
<br />
<script language="javascript" type="text/javascript">
$(function() {
			
	// Submit handling :)
	$('#importQuizForm').submit(function(e) {
		if( $('#patID').val() == "NONE" ) {
			alert( "Please choose a quiz" );
			e.preventDefault();
			return false;
		}
	});

});
</script>

<div id="rawData" style="display: none;">
</div>
<h2>יבוא שאלון:</h2>
<?php
	$tempPatients = array();
	$tempPatients['NONE'] = "אנא בחרו שאלון";
	foreach ( $arrQuizzes as $currPatient ) {
		$tempPatients[$currPatient['Quiz']['quizID']] = '['.$currPatient['Quiz']['quizID'].'] '.$currPatient['Quiz']['quizTitle'];
	}
	
	echo $this->Form->create(false ,array('url' => array('controller' => 'Admin', 'action' => 'importQuiz')));	
	echo $this->Form->input('patID', array('options' => $tempPatients, 'default' => 'NONE', 'label' => 'שאלונים זמינים'));
	echo $this->Form->end('יבוא');
?>
<br />
<script language="javascript" type="text/javascript">
$(function() {
			
	// Submit handling :)
	$('#importQuizForm').submit(function(e) {
		if( $('#patID').val() == "NONE" ) {
			alert( "אנא בחרו שאלון" );
			e.preventDefault();
			return false;
		}
	});

});
</script>

<div id="rawData" style="display: none;">
</div>
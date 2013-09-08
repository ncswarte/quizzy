<script>
$(function() {

	var intCurrentResearch = "<?php echo $this->Session->read('User.currentResearch'); ?>";

	// Change a research selection -> re-POST please
	$('#selResearch').change( function() {
		if( $(this).val() == "NONE" )
			return;
			
		if( $(this).val() == "ADDNEW" ) {
			window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addResearch'), true ); ?>';
			return;
		}
		
		$('<form method="POST"><input type="hidden" name="research" value="' + $(this).val() + '">' + '</form>').appendTo($(document.body)).submit();
	});
	
	// Show/Hide toggler
	$('.classShowHideTable').click( function() {
		var sPreviousText = $(this).text();
		var sNewText = sPreviousText.toString().substring(0, sPreviousText.toString().lastIndexOf(' ') );
		if( $(this).next('div').is(':visible') ) {
			$(this).text( sNewText + " [Show]");
		} else {
			$(this).text( sNewText + " [Hide]");
		}
		$(this).next('div').toggle();
	});
	
	$('.aRemoveQuiz').on( 'click', function() {
		var strRemoveQuiz = $(this).attr('id').toString();
		strRemoveQuiz = strRemoveQuiz.replace('idRemoveQuiz', '');
		var resQ = confirm("Are you sure you would like to delete this quiz?");
		
		if( resQ == true ) {
			$("#rawData").append('<form id="exportform" action="<?php echo Router::url(array('controller' => 'Admin', 'action' => 'deleteQuiz'), true ); ?>" method="post"><input type="hidden" id="fldQuizID" name="fldQuizID" /></form>');
			$("#fldQuizID").val( strRemoveQuiz );
			$("#exportform").submit().remove();
		}
	});

	if( intCurrentResearch != "" ) {
		$('#selResearch').val( intCurrentResearch );
	} else {
		$('#divResearchData').text('No research selected');
	}
});

</script>

<div id="divSelectResearch">Currently working on: 
	<select id="selResearch">
		<option value="NONE">Plase choose a research!</option>
	<?php
		foreach ( $arrResearches as $currResearch ) {
			echo '<option value="'.$currResearch['Research']['researchID'].'">'.$currResearch['Research']['researchName'].'</option>';
		}
	?>
		<option value="NONE">------------</option>
		<option value="ADDNEW">Add a new research</option>
	</select>
</div>

<div id="divResearchData">
	
<!-- START of Patient List -->
<div id="divPatientList">
	<h2 class="classShowHideTable">Patient List: [Show]</h2>
	<div class="divShowHide" style="display: none;">
	<table style="width: 60%; border: 1px solid gray;">
		<tr><th>Patient ID</th><th>Name</th><th>Quiz</th><th>Completed?</th><th>Quizes</th><th>Profile</th></tr>
<?php
	
	if( count( $arrPatients ) == 0 ) 
		echo "\t\t".'<tr><td colspan="6">NONE</td></tr>'."\n";
	
	// Print patient list
	foreach ($arrPatients as $currPat) {
	
		if( !isset($arrPatientsQuizzes[$currPat['Patient']['patID']]) || count($arrPatientsQuizzes[$currPat['Patient']['patID']]) < 1 ) {
			echo "\t\t".'<tr><td>'.$currPat['Patient']['patID'].'</td><td>'.$currPat['Patient']['patFirstname'].' '.$currPat['Patient']['patLastname'].'</td>';
			echo '<td colspan="2" style="text-align: center; font-weight: bold;">None defined</td>';
			echo '<td>'.$this->Html->link( 'Change', array('controller' => 'Admin', 'action' => 'patientQuiz', $currPat["Patient"]["patID"]) ).'</td>';
			echo '<td>'.$this->Html->link( 'Edit', array('controller' => 'Admin', 'action' => 'addPatient', $currPat["Patient"]["patID"]) ).'</td>';
			echo '</tr>'."\n";
		} else {
			echo "\t\t".'<tr><td rowspan="'.sizeof($arrPatientsQuizzes[$currPat['Patient']['patID']]).'">'.$currPat['Patient']['patID'].'</td><td rowspan="'.sizeof($arrPatientsQuizzes[$currPat['Patient']['patID']]).'">'.$currPat['Patient']['patFirstname'].' '.$currPat['Patient']['patLastname'].'</td>';
			foreach( $arrPatientsQuizzes[$currPat['Patient']['patID']] as $currQ ) {
				echo '<td>'.$currQ['0'].'</td>';
				if( $currQ['1'] == '1' ) {
					echo '<td>Yes</td>';
				} else {
					echo '<td>No</td>';
				}

				echo '<td>'.$this->Html->link( 'Change', array('controller' => 'Admin', 'action' => 'patientQuiz', $currPat["Patient"]["patID"]) ).'</td>';
				echo '<td>'.$this->Html->link( 'Edit', array('controller' => 'Admin', 'action' => 'addPatient', $currPat["Patient"]["patID"]) ).'</td>';
				echo '</tr>'."\n";
			}
		}
	}
?>
	</table>
	<button type="button" onClick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addPatient'), true ); ?>'; return false;">Add new patient</button>
	<button type="button" onClick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'importPatient'), true ); ?>'; return false;">Import patient</button>
	<button type="button" onClick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'managePatients'), true ); ?>'; return false;">Manage patients</button>
	<br/><br/>
	</div>
</div>
<!-- END of Patient List -->
	
<hr><br/><br/>

<!-- START of Quiz List -->
<div id="divQuizList">
	<h2 class="classShowHideTable">Quiz List: [Show]</h2>
	<div class="divShowHide" style="display: none;">
	<table style="width: 50%; border: 1px solid gray;">
	<tr><th>Quiz ID</th><th>Quiz Title</th><th>Remove</th></tr>
	
	<?php
	
	if( count( $arrQuizzes ) == 0 ) 
		echo "\t".'<tr><td colspan="3">NONE</td></tr>'."\n";
		
	// Print quiz list
	foreach ($arrQuizzes as $currPat) {
		echo "\t\t".'<tr><td>'.$currPat['Quiz']['quizID'].'</td><td>'.$currPat['Quiz']['quizTitle'].'</td><td><a style="cursor: pointer" class="aRemoveQuiz" id="idRemoveQuiz'.$currPat['Quiz']['quizID'].'" target="_blank">Remove</a></td></tr>'."\n";
	}
	?>
	</table>
	<button type="button" onclick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addQuiz'), true ); ?>'; return false;">Add new quiz</button>
	<br/><br/>
	</div>
</div>
<!-- END of Quiz List -->
	
<hr><br/><br/>

<!-- START of Assistant List -->
<div id="divAssistantList">
	<h2 class="classShowHideTable">Assistant List: [Show]</h2>
	<div class="divShowHide" style="display: none;">
	<table style="width: 50%; border: 1px solid gray;">
	<tr><th>Assistant ID</th><th>Name</th><th>Profile</th></tr>
	
	<?php
	
	if( count( $arrAssistants ) == 0 ) 
		echo "\t".'<tr><td colspan="3">NONE</td></tr>'."\n";
		
	// Print the list
	foreach ($arrAssistants as $currAssist) {
		echo "\t\t".'<tr><td>'.$currAssist['ResearchAssistant']['assistantID'].'</td><td>'.$arrAllAssistants[$currAssist['ResearchAssistant']['assistantID']].'</td><td>'.$this->Html->link( 'Edit', array('controller' => 'Admin', 'action' => 'addAssistant', $currAssist['ResearchAssistant']['assistantID']) ).'</td></tr>'."\n";
	}
	?>
	</table>
	<button type="button" onClick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addAssistant'), true ); ?>'; return false;">Add new assistant</button>
	<button type="button" onClick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'manageAssistants'), true ); ?>'; return false;">Manage Assistants</button>
	<br/><br/>
	</div>
</div>
<!-- END of Assistant List -->

<hr><br/><br/>

<!-- START of Analysis List -->
<div id="divAnalysis">
	<h2 onClick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Analysis', 'action' => 'index'), true ); ?>'; return false;" style="cursor: pointer;">Quiz Data Analysis</a></h2>
</div>
<!-- END of Analysis List -->

</div>
<div id="rawData" style="display: none;">
</div>
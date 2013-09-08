<script>
$(function() {

	var intCurrentResearch = "<?php echo $this->Session->read('User.currentResearch'); ?>";

	// Change a research selection -> re-POST please
	$('#selResearch').change( function() {
		if( $(this).val() == "NONE" )
			return;
			
		if( $(this).val() == "ADDNEW" ) {
			window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addResearch'), false ); ?>';
			return;
		}
		
		$('<form method="POST"><input type="hidden" name="research" value="' + $(this).val() + '">' + '</form>').appendTo($(document.body)).submit();
	});
	
	// Show/Hide toggler
	$('.classShowHideTable').click( function() {
		var sPreviousText = $(this).text();
		var sNewText = sPreviousText.toString().substring(0, sPreviousText.toString().lastIndexOf(' ') );
		if( $(this).next('div').is(':visible') ) {
			$(this).text( sNewText + " [הצג]");
		} else {
			$(this).text( sNewText + " [הסתר]");
		}
		$(this).next('div').toggle();
	});

	if( intCurrentResearch != "" ) {
		$('#selResearch').val( intCurrentResearch );
	} else {
		$('#divResearchData').text('לא נבחר מחקר');
	}
});

</script>

<div id="divSelectResearch">מחקר נוכחי:
	<select id="selResearch">
		<option value="NONE">בחרו מחקר!</option>
	<?php
		foreach ( $arrResearches as $currResearch ) {
			echo '<option value="'.$currResearch['Research']['researchID'].'">'.$currResearch['Research']['researchName'].'</option>';
		}
	?>
		<option value="NONE">------------</option>
		<option value="ADDNEW">הוספת מחקר חדש</option>
	</select>
</div>

<div id="divResearchData">

<!-- START of Patient List -->
<div id="divPatientList">
	<h2 class="classShowHideTable">רשימת נבדקים: [הצג]</h2>
	<div class="divShowHide" style="display: none;">
	<table style="width: 60%; border: 1px solid gray;">
		<tr><th>מזהה נבדק</th><th>שם</th><th>שאלון</th><th>הושלם?</th><th>שאלונים</th><th>פרופיל</th></tr>
<?php
	
	if( count( $arrPatients ) == 0 ) 
		echo "\t\t".'<tr><td colspan="6">NONE</td></tr>'."\n";
	
	// Print patient list
	foreach ($arrPatients as $currPat) {
	
		if( !isset($arrPatientsQuizzes[$currPat['Patient']['patID']]) || count($arrPatientsQuizzes[$currPat['Patient']['patID']]) < 1 ) {
			echo "\t\t".'<tr><td>'.$currPat['Patient']['patID'].'</td><td>'.$currPat['Patient']['patFirstname'].' '.$currPat['Patient']['patLastname'].'</td>';
			echo '<td colspan="2" style="text-align: center; font-weight: bold;">טרם הוגדרו</td>';
			echo '<td>'.$this->Html->link( 'שינוי', array('controller' => 'Admin', 'action' => 'patientQuiz', $currPat["Patient"]["patID"]) ).'</td>';
			echo '<td>'.$this->Html->link( 'עריכה', array('controller' => 'Admin', 'action' => 'addPatient', $currPat["Patient"]["patID"]) ).'</td>';
			echo '</tr>'."\n";
		} else {
			echo "\t\t".'<tr><td rowspan="'.sizeof($arrPatientsQuizzes[$currPat['Patient']['patID']]).'">'.$currPat['Patient']['patID'].'</td><td rowspan="'.sizeof($arrPatientsQuizzes[$currPat['Patient']['patID']]).'">'.$currPat['Patient']['patFirstname'].' '.$currPat['Patient']['patLastname'].'</td>';
			foreach( $arrPatientsQuizzes[$currPat['Patient']['patID']] as $currQ ) {
				echo '<td>'.$currQ['0'].'</td>';
				if( $currQ['1'] == '1' ) {
					echo '<td>כן</td>';
				} else {
					echo '<td>לא</td>';
				}

				echo '<td>'.$this->Html->link( 'שינוי', array('controller' => 'Admin', 'action' => 'patientQuiz', $currPat["Patient"]["patID"]) ).'</td>';
				echo '<td>'.$this->Html->link( 'עריכה', array('controller' => 'Admin', 'action' => 'addPatient', $currPat["Patient"]["patID"]) ).'</td>';
				echo '</tr>'."\n";
			}
		}
	}
?>
	</table>
	<button type="button" onClick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addPatient'), true ); ?>'; return false;">הוספת נבדק חדש</button>
	<button type="button" onClick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'importPatient'), true ); ?>'; return false;">יבוא נבדק</button>
	<button type="button" onClick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'managePatients'), true ); ?>'; return false;">ניהול נבדקים</button>
	<br/><br/>
	</div>
</div>
<!-- END of Patient List -->
	
<hr><br/><br/>

<!-- START of Quiz List -->
<div id="divQuizList">
	<h2 class="classShowHideTable">רשימת שאלונים: [הצג]</h2>
	<div class="divShowHide" style="display: none;">
	<table style="width: 50%; border: 1px solid gray;">
	<tr><th>מזהה שאלון</th><th>כותרת שאלון</th><th>View - do we want it and/or a delete?</th></tr>
	
	<?php
	
	if( count( $arrQuizzes ) == 0 ) 
		echo "\t".'<tr><td colspan="3">NONE</td></tr>'."\n";
		
	// Print quiz list
	foreach ($arrQuizzes as $currPat) {
		echo "\t\t".'<tr><td>'.$currPat['Quiz']['quizID'].'</td><td>'.$currPat['Quiz']['quizTitle'].'</td><td><a href="#" target="_blank">Click here</a></td></tr>'."\n";
	}
	?>
	
	</table>
	<button type="button" onclick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addQuiz'), true ); ?>'; return false;">הוספת שאלון חדש</button>
	<br/><br/>
	</div>
</div>
<!-- END of Quiz List -->
	
<hr><br/><br/>

<!-- START of Assistant List -->
<div id="divAssistantList">
	<h2 class="classShowHideTable">רשימת עוזרי מחקר: [הצג]</h2>
	<div class="divShowHide" style="display: none;">
	<table style="width: 50%; border: 1px solid gray;">
	<tr><th>מזהה עוזר</th><th>שם</th><th>פרופיל</th></tr>
	
	<?php
	
	if( count( $arrAssistants ) == 0 ) 
		echo "\t".'<tr><td colspan="3">NONE</td></tr>'."\n";
		
	// Print the list
	foreach ($arrAssistants as $currAssist) {
		echo "\t\t".'<tr><td>'.$currAssist['ResearchAssistant']['assistantID'].'</td><td>'.$arrAllAssistants[$currAssist['ResearchAssistant']['assistantID']].'</td><td>'.$this->Html->link( 'עריכה', array('controller' => 'Admin', 'action' => 'addAssistant', $currAssist['ResearchAssistant']['assistantID']) ).'</td></tr>'."\n";
	}
	?>
	</table>
	<button type="button" onclick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'addAssistant'), true ); ?>'; return false;">הוספת עוזר</button>
	<button type="button" onClick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'manageAssistants'), true ); ?>'; return false;">ניהול עוזרים</button>
	<br/><br/>
	</div>
</div>
	
<!-- END of Assistant List -->

<hr><br/><br/>

<!-- START of Analysis List -->
<div id="divAnalysis">
	<h2 onClick="window.location.href='<?php echo $this->Html->url(array('controller' => 'Analysis', 'action' => 'index'), true ); ?>'; return false;" style="cursor: pointer;">ניתוח מידע שאלונים</a></h2>
</div>
<!-- END of Analysis List -->
	
</div>
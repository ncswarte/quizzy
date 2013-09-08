<h2>יבוא נבדק:</h2>
<?php
	$tempPatients = array();
	$tempPatients['NONE'] = "בחרו נבדק:";
	foreach ( $arrPatients as $currPatient ) {
		$tempPatients[$currPatient['Patient']['patID']] = '['.$currPatient['Patient']['patID'].'] '.$currPatient['Patient']['patFirstname'].' '.$currPatient['Patient']['patLastname'];
	}
	
	echo $this->Form->create(false ,array('url' => array('controller' => 'Assistant', 'action' => 'importPatient')));	
	echo $this->Form->input('patID', array('options' => $tempPatients, 'default' => 'NONE', 'label' => 'נבדקים מוגדרים'));
	echo $this->Form->end('יבוא');
?>
<br />
<script language="javascript" type="text/javascript">
$(function() {
	
	var patID = $('#PatientPatID').val();
	var tmpLink = "<?php echo Router::url(array('controller' => 'Assistant', 'action' => 'deleteFile')); ?>/" + patID + "/";
		
	// Submit handling :)
	$('#importPatientForm').submit(function(e) {
		if( $('#patID').val() == "NONE" ) {
			alert( "Please choose a patient" );
			e.preventDefault();
			return false;
		}
	});
	
  	$('#btnDelete').click(function(){
		var resQ = confirm("Are you sure you would like to delete this patient?");
		
		if( resQ == true ) {
			$("#rawData").append('<form id="exportform" action="<?php echo Router::url(array('controller' => 'Assistant', 'action' => 'deletePatient'), true ); ?>" method="post"><input type="hidden" id="fldPatientID" name="fldPatientID" /></form>');
			$("#fldPatientID").val( patID );
			$("#exportform").submit().remove();
		}
    });

});
</script>

<div id="rawData" style="display: none;">
</div>
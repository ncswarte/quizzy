<h2>Import patient:</h2>
<?php
	$tempPatients = array();
	$tempPatients['NONE'] = "Please choose a patient";
	foreach ( $arrPatients as $currPatient ) {
		$tempPatients[$currPatient['Patient']['patID']] = '['.$currPatient['Patient']['patID'].'] '.$currPatient['Patient']['patFirstname'].' '.$currPatient['Patient']['patLastname'];
	}
	
	echo $this->Form->create(false ,array('url' => array('controller' => 'Admin', 'action' => 'importPatient')));	
	echo $this->Form->input('patID', array('options' => $tempPatients, 'default' => 'NONE', 'label' => 'Available patients'));
	echo $this->Form->end('Import');
?>
<br />
<script language="javascript" type="text/javascript">
$(function() {
	
	var patID = $('#PatientPatID').val();
	var tmpLink = "<?php echo Router::url(array('controller' => 'Admin', 'action' => 'deleteFile')); ?>/" + patID + "/";
		
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
			$("#rawData").append('<form id="exportform" action="<?php echo Router::url(array('controller' => 'Admin', 'action' => 'deletePatient'), true ); ?>" method="post"><input type="hidden" id="fldPatientID" name="fldPatientID" /></form>');
			$("#fldPatientID").val( patID );
			$("#exportform").submit().remove();
		}
    });

});
</script>

<div id="rawData" style="display: none;">
</div>
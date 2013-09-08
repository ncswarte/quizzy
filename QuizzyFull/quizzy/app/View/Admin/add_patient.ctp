<h2>Add patient:</h2>
<?php
	echo $this->Form->create('Patient',array('url' => array('controller' => 'Admin', 'action' => 'addPatient')));
	echo $this->Form->hidden('Patient.patID');
	echo $this->Form->input('Patient.patFirstname', array('label' => 'First name') );
	echo $this->Form->input('Patient.patLastname', array('label' => 'Last name') );
	echo $this->Form->input('Patient.patAge', array('label' => 'Age') );
	echo $this->Form->input('Patient.patGender', array('options' => array('male' => 'Male', 'female' => 'Female'), 'default' => 'male', 'label' => 'Gender'));
	echo $this->Form->input('Patient.patStatus', array('options' => array('single' => 'Single',
		'married' => 'Married',
		'divorced' => 'Divorced',
		'widowed' => 'Widowed'), 'default' => 'single', 'label' => 'Marital Status'));
	echo $this->Form->input('Patient.patAddress', array('label' => 'Address') );
	echo $this->Form->input('Patient.patPhone', array('label' => 'Phone') );
	echo $this->Form->input('password1',array('label'=>'New password (only enter if you wish to modify existing)', 'type'=>'password', 'id' => 'fldPassword1'));
	echo $this->Form->input('password2',array('label'=>'Confirm password', 'type'=>'password',  'id' => 'fldPassword2'));
	echo $this->Html->div(null, 'NOTE: The user will be assigned a default password (123456), it can be later changed.', array('id' => 'divDefaultPassword') );
	echo $this->Form->end('Save');
	
	echo $this->Form->button('Delete', array('id' => 'btnDelete'));
?>
<br /><br /><br />
<div id="divFiles">
	<h2>Files</h2>
	<?php 
		if( empty($fileData) ) {
			echo '<h1>No files</h1>'."\n";
		} else {		
	?>
			<table>
				<tr><th>Filename:</th><th>Added:</th><th>Description:</th><th>Delete:</th></tr>
	<?php
			foreach ( $fileData as $currFile ) {
				echo '<tr><td><a href="'. Router::url('/') . 'uploads/patient/' . $currFile['PatientFiles']['fileName'] .'" target="_blank" >'.$currFile['PatientFiles']['fileName'].'</a></td><td>'.$currFile['PatientFiles']['dateAdded'].'</td><td>'.$currFile['PatientFiles']['fileDescription'].'</td><td><a style="cursor: pointer;" id="file_'.$currFile['PatientFiles']['fileName'].'" class="aRemove" for="'. $currFile['PatientFiles']['fileID'] .'">Remove?</a></td></tr>'."\n";
			}
			echo '</table>'."\n";
		}
	?>
	<h2>Add a new file:</h2>
	<?php 
	echo $this->Form->create('PatientFiles', array('url' => array('controller' => 'Admin', 'action' => 'addPatientFile'), 'type' => 'file'));
	echo $this->Form->hidden('Patient.patID');
	echo $this->Form->input('PatientFiles.fileDescription' );
	echo $this->Form->input('upload', array('type' => 'file'));
	echo $this->Form->end('Add');
	?>
</div>

<script language="javascript" type="text/javascript">
$(function() {
	
	var patID = $('#PatientPatID').val();
	var tmpLink = "<?php echo Router::url(array('controller' => 'Admin', 'action' => 'deleteFile')); ?>/" + patID + "/";
	
	if( patID.length < 1 ) {
		$('#btnDelete').hide();
		$('#fldPassword1').hide();
		$('#fldPassword2').hide();
		$('#fldPassword1').val('');
		$('#fldPassword2').val('');
		$('.input_password').hide();
		$("label[for='fldPassword1']").hide();
		$("label[for='fldPassword2']").hide();
		$('#divFiles').hide();
	} else {
		$("#divDefaultPassword").hide();
	}
	
	// Submit handling :)
  	$('#btnDelete').click(function(){
		var resQ = confirm("Are you sure you would like to delete this patient?");
		
		if( resQ == true ) {
			$("#rawData").append('<form id="exportform" action="<?php echo Router::url(array('controller' => 'Admin', 'action' => 'deletePatient'), true ); ?>" method="post"><input type="hidden" id="fldPatientID" name="fldPatientID" /></form>');
			$("#fldPatientID").val( patID );
			$("#exportform").submit().remove();
		}
    });
	
	// Remove a specific file
	$('.aRemove').click( function() {
		var tempFileID = $(this).attr('for');
		var tempFile = $(this).attr('id');
		tempFile = tempFile.toString();
		tempFile = tempFile.replace('file_', '');
		var promptConfirm = confirm("Are you sure you would like to remove '" + tempFile + "'?" );
		
		if( promptConfirm == true ) {
			document.location.href= tmpLink + tempFileID;
		} else {
			//nada
		}
	});

});
</script>

<div id="rawData" style="display: none;">
</div>
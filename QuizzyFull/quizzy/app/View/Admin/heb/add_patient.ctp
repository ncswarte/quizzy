<h2>הוספת נבדק:</h2>
<?php
	echo $this->Form->create('Patient',array('url' => array('controller' => 'Admin', 'action' => 'addPatient')));
	echo $this->Form->hidden('Patient.patID');
	echo $this->Form->input('Patient.patFirstname', array('label' => 'שם פרטי') );
	echo $this->Form->input('Patient.patLastname', array('label' => 'שם משפחה') );
	echo $this->Form->input('Patient.patAge', array('label' => 'גיל') );
	echo $this->Form->input('Patient.patGender', array('options' => array('male' => 'זכר', 'female' => 'נקבה'), 'default' => 'male', 'label' => 'מין'));
	echo $this->Form->input('Patient.patStatus', array('options' => array('single' => 'רווק/ה',
		'married' => 'נשוי/נשואה',
		'divorced' => 'גרוש/ה',
		'widowed' => 'אלמן/ה'), 'default' => 'single', 'label' => 'מצב משפחתי'));
	echo $this->Form->input('Patient.patAddress', array('label' => 'כתובת') );
	echo $this->Form->input('Patient.patPhone', array('label' => 'טלפון') );
	echo $this->Form->input('password1',array('label'=>'סיסמא חדשה (רק אם ברצונכם לשנות את הסיסמא הנוכחית)', 'type'=>'password', 'id' => 'fldPassword1'));
	echo $this->Form->input('password2',array('label'=>'סיסמא שוב', 'type'=>'password',  'id' => 'fldPassword2'));
	echo $this->Html->div(null, 'הנבדק יקבל סיסמא אוטומטית (123456) אותה ניתן לשנות בהמשך.', array('id' => 'divDefaultPassword') );
	echo $this->Form->end('שמירה');
	
	echo $this->Form->button('מחיקה', array('id' => 'btnDelete'));
?>
<br /><br /><br />
<div id="divFiles">
	<h2>קבצים</h2>
	<?php 
		if( empty($fileData) ) {
			echo '<h1>אין קבצים</h1>'."\n";
		} else {		
	?>
			<table>
				<tr><th>שם קובץ:</th><th>תאריך הוספה:</th><th>תיאור:</th><th>מחיקה:</th></tr>
	<?php
			foreach ( $fileData as $currFile ) {
				echo '<tr><td><a href="'. $this->Html->url('/') . 'uploads/patient/' . $currFile['PatientFiles']['fileName'] .'" target="_blank" >'.$currFile['PatientFiles']['fileName'].'</a></td><td>'.$currFile['PatientFiles']['dateAdded'].'</td><td>'.$currFile['PatientFiles']['fileDescription'].'</td><td><a style="cursor: pointer;" id="file_'.$currFile['PatientFiles']['fileName'].'" class="aRemove" for="'. $currFile['PatientFiles']['fileID'] .'">הסרה?</a></td></tr>'."\n";
			}
			echo '</table>'."\n";
		}
	?>
	<h2>הוספת קובץ חדש:</h2>
	<?php 
	echo $this->Form->create('PatientFiles', array('url' => array('controller' => 'Admin', 'action' => 'addPatientFile'), 'type' => 'file'));
	echo $this->Form->hidden('Patient.patID');
	echo $this->Form->input('PatientFiles.fileDescription', array('label' => 'תיאור') );
	echo $this->Form->input('upload', array('type' => 'file', 'label' => 'בחירת קובץ'));
	echo $this->Form->end('הוספה');
	?>
</div>

<script language="javascript" type="text/javascript">
$(function() {
	
	var patID = $('#PatientPatID').val();
	var tmpLink = "<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'deleteFile'), false); ?>/" + patID + "/";
	
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
		var resQ = confirm("האם אתם בטוחים שברצונכם להסיר את הנבדק?");
		
		if( resQ == true ) {
			$("#rawData").append('<form id="exportform" action="<?php echo $this->Html->url(array('controller' => 'Admin', 'action' => 'delPatient'), false ); ?>" method="post"><input type="hidden" id="fldPatientID" name="fldPatientID" /></form>');
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
		var promptConfirm = confirm("האם אתם בטוחים שברצונכם להסיר את הקובץ '" + tempFile + "'?" );
		
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
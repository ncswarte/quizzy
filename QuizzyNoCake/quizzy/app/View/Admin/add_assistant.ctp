<h2>Add Assistant:</h2>
<?php
	echo $this->Form->create('Assistant',array('url' => array('controller' => 'Admin', 'action' => 'addAssistant')));
	echo $this->Form->hidden('Assistant.assistantID');
	echo $this->Form->input('Assistant.assistantName', array('label' => 'Name', 'rows' => '1') );
	echo $this->Form->input('password1',array('label'=>'New password (only enter if you wish to modify existing)', 'type'=>'password', 'id' => 'fldPassword1'));
	echo $this->Form->input('password2',array('label'=>'Confirm password', 'type'=>'password',  'id' => 'fldPassword2'));
	echo $this->Html->div(null, 'NOTE: The user will be assigned a default password (123456), it can be later changed.', array('id' => 'divDefaultPassword') );
	echo $this->Form->end('Save');
	
	echo $this->Form->button('Delete', array('id' => 'btnDelete'));
?>
<br /><br /><br />

<script language="javascript" type="text/javascript">
$(function() {
	
	var assistantID = $('#AssistantAssistantID').val();
	
	if( assistantID.length < 1 ) {
		$('#btnDelete').hide();
		$('#fldPassword1').hide();
		$('#fldPassword2').hide();
		$('#fldPassword1').val('');
		$('#fldPassword2').val('');
		$("label[for='fldPassword1']").hide();
		$("label[for='fldPassword2']").hide();
		$('#divFiles').hide();
	} else {
		$("#divDefaultPassword").hide();
	}
	
	// Submit handling :)
  	$('#btnDelete').click(function(){
		var resQ = confirm("Are you sure you would like to delete this Assistant?");
		
		if( resQ == true ) {
			$("#rawData").append('<form id="exportform" action="<?php echo Router::url(array('controller' => 'Admin', 'action' => 'deleteAssistant'), true ); ?>" method="post"><input type="hidden" id="fldAssistantID" name="fldAssistantID" /></form>');
			$("#fldAssistantID").val( assistantID );
			$("#exportform").submit().remove();
		}
    });
	
});
</script>

<div id="rawData" style="display: none;">
</div>
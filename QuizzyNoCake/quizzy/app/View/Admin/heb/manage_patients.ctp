<h2>ניהול נבדקים:</h2>
<script language="javascript" type="text/javascript">

	function moveItem( idFrom, idTo ) {
		$("#" + idFrom + "  option:selected").appendTo("#" + idTo);
	}

$(function() {

	// Submit handling :)
  	$('#btnSubmit').click(function(){
		var strAll = "";
		
		$("#rawData").append('<form id="formSave" method="post"><input type="hidden" id="fldPatientList" name="fldPatientList" /></form>');
		$("#selPatient option").each(function() {
			strAll += $(this).val() + ",";
		});
  		
		// Update vars and submit
		$("#fldPatientList").val(strAll);
		$("#formSave").submit().remove();

    });

});
</script>
	<table style="width: 50%; border: 1px solid gray;">
		<tr><th colspan="3" style="text-align: center;">ניהול נבדקים [<?php echo $researchInfo['Research']['researchName']; ?>]:</th></tr>
		<tr><th style="text-align: center;">נבדקי מחקר:</th><th style="text-align: center;"></th><th style="text-align: center;">נבדקים נוספים:</th></tr>
		<tr>
			<td>
				<select id="selPatient" multiple="multiple" name="selPatient"> 
					<?php
						foreach ( $arrResearchPatients as $currPatient ) {
							echo "\t\t\t".'<option value="'.$currPatient['patID'].'">'.$currPatient['patID']." - ".$currPatient['patFirstname'].' '.$currPatient['patLastname'].'</option>'."\n";
						}
					?>
				</select>
			</td>
			<td style="text-align: center;">
				<input id="moveLeft" type="button" value="<-" onclick="moveItem('selPatientPool','selPatient');" /><br/>
				<input id="moveRight" type="button" value="->" onclick="moveItem('selPatient','selPatientPool');" />
			</td>
			<td>
				<select id="selPatientPool" multiple="multiple" name="selPatientPool">
					<?php
						foreach ( $arrResearchNonPatients as $currPatient ) {
							echo "\t\t\t".'<option value="'.$currPatient['patID'].'">'.$currPatient['patID']." - ".$currPatient['patFirstname'].' '.$currPatient['patLastname'].'</option>'."\n";
						}
					?>
				</select>
			</td>
		</tr>
		
		<tr><td style="text-align: center;" colspan="3"><input type="submit" id="btnSubmit" value="שמירה"></td></tr>
	</table>
	
<div id="rawData" style="display: none;">
</div>
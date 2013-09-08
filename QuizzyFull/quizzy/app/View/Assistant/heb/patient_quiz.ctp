<h2>ניהול שאלוני נבדק [<?php echo $patInfo['Patient']['patFirstname']." ".$patInfo['Patient']['patLastname']; ?>]:</h2>
<script language="javascript" type="text/javascript">

	function moveItem( idFrom, idTo ) {
		$("#" + idFrom + "  option:selected").appendTo("#" + idTo);
	}

$(function() {

	// Submit handling :)
  	$('#btnSubmit').click(function(){
		var strAll = "";
		
		$("#rawData").append('<form id="formSave" method="post"><input type="hidden" id="fldPatientID" name="fldPatientID" /><input type="hidden" id="fldQuizList" name="fldQuizList" /></form>');
		$("#selPatient option").each(function() {
			strAll += $(this).val() + ",";
		});
  		
		// Update vars and submit
		$("#fldQuizList").val(strAll);
		$("#fldPatientID").val( $("#patID").val() );
		$("#formSave").submit().remove();

    });

});
</script>
<?php
	$tempFlagUsed = array();
?>
	<input type="hidden" name="patID" id="patID" value="<?php echo $patInfo['Patient']['patID']; ?>">
	<table style="width: 50%; border: 1px solid gray;">
		<tr><th colspan="3" style="text-align: center;">ניהול שאלונים:</th></tr>
		<tr><th style="text-align: center;">שאלוני נבדק:</th><th style="text-align: center;"></th><th style="text-align: center;">שאלונים מחקר נוספים:</th></tr>
		<tr>
			<td>
				<select id="selPatient" multiple="multiple" name="selPatient"> 
					<?php
						foreach ( $listPatQ as $currQ ) {
							echo "\t\t\t".'<option value="'.$currQ[0].'">'.$currQ[0]." - ".$quizInfo[$currQ[0]]['Quiz']['quizTitle'].'</option>'."\n";
							$tempFlagUsed[$currQ[0]] = 1;
						}
					?>
					
					<?php
					
					?>
				</select>
			</td>
			<td style="text-align: center;">
				<input id="moveLeft" type="button" value="<-" onclick="moveItem('selQuizPool','selPatient');" /><br/>
				<input id="moveRight" type="button" value="->" onclick="moveItem('selPatient','selQuizPool');" />
			</td>
			<td>
				<select id="selQuizPool" multiple="multiple" name="selQuizPool">
					<?php
						foreach ( $listQuiz as $currQ ) {
							if( !isset( $tempFlagUsed[ $currQ['Quiz']['quizID'] ] ) ) {
								echo "\t\t\t".'<option value="'.$currQ['Quiz']['quizID'].'">'.$currQ['Quiz']['quizID']." - ".$currQ['Quiz']['quizTitle'].'</option>'."\n";
							}
						}
					?>
				</select>
			</td>
		</tr>
		
		<tr><td style="text-align: center;" colspan="3"><input type="submit" id="btnSubmit" value="שמירה"></td></tr>
	</table>
	
<div id="rawData" style="display: none;">
</div>
<h2>ניהול עוזרי מחקר:</h2>
<script language="javascript" type="text/javascript">

	function moveItem( idFrom, idTo ) {
		$("#" + idFrom + "  option:selected").appendTo("#" + idTo);
	}

$(function() {

	// Submit handling :)
  	$('#btnSubmit').click(function(){
		var strAll = "";
		
		$("#rawData").append('<form id="formSave" method="post"><input type="hidden" id="fldAssistantList" name="fldAssistantList" /></form>');
		$("#selAssistant option").each(function() {
			strAll += $(this).val() + ",";
		});
  		
		// Update vars and submit
		$("#fldAssistantList").val(strAll);
		$("#formSave").submit().remove();

    });

});
</script>
	<table style="width: 50%; border: 1px solid gray;">
		<tr><th colspan="3" style="text-align: center;">ניהול עוזרי מחקר [<?php echo $researchInfo['Research']['researchName']; ?>]:</th></tr>
		<tr><th style="text-align: center;">עוזרי מחקר נוכחי:</th><th style="text-align: center;"></th><th style="text-align: center;">עוזרי מחקר נוספים:</th></tr>
		<tr>
			<td>
				<select id="selAssistant" multiple="multiple" name="selAssistant"> 
					<?php
						foreach ( $arrAssistants as $currAssistant => $currAssistantV) {
							//debug( $arrAssistantsInfo[$currAssistant] );
							//debug( $currAssistant );
							//debug( $currAssistantV );
							if($currAssistantV == 1)
								echo "\t\t\t".'<option value="'.$currAssistant.'">'.$currAssistant." - ".$arrAssistantsInfo[$currAssistant].'</option>'."\n";
						}
					?>
				</select>
			</td>
			<td style="text-align: center;">
				<input id="moveLeft" type="button" value="<-" onclick="moveItem('selAssistantPool','selAssistant');" /><br/>
				<input id="moveRight" type="button" value="->" onclick="moveItem('selAssistant','selAssistantPool');" />
			</td>
			<td>
				<select id="selAssistantPool" multiple="multiple" name="selAssistantPool">
					<?php
						foreach ( $arrAssistants as $currAssistant => $currAssistantV) {
							if($currAssistantV == 0)
								echo "\t\t\t".'<option value="'.$currAssistant.'">'.$currAssistant." - ".$arrAssistantsInfo[$currAssistant].'</option>'."\n";
						}
					?>
				</select>
			</td>
		</tr>
		
		<tr><td style="text-align: center;" colspan="3"><input type="submit" id="btnSubmit" value="שמירה"></td></tr>
	</table>
	
<div id="rawData" style="display: none;">
</div>
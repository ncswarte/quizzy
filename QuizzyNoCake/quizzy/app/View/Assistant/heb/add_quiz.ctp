<h2>הוספת שאלון</h2>
<?php
	// For show current question number
	$currQNum = $prevQNum + 1;
?>
<script type="text/javascript">
	$(function() {
	
		var prevTitle 	= <?php echo json_encode($prevTitle); ?>; 
		var prevQuiz 	= <?php echo json_encode($prevQuiz); ?>; 
		var currQNum 	= <?php echo json_encode($currQNum); ?>;
		
		// Set title to previous if required
		if( prevTitle != '' ) {
			$('#fldQuizTitle').val(prevTitle);
		}
		if( prevQuiz != '' ) {
			$('#rawDataPrev').val(prevQuiz);
		}

		// Start with 'em hidden - can be done in CSS too but I'm dumb
		$('.classAdd').hide();
		
		// Start with choose
		$('#selType').val('Choose');
		
		// Question number counter
		var intCountQ = 0;
		
		function doQuestionAdd( strFlagDone ) {
			intCountQ++;
			var strToAdd = '';			
			var strThisQ = intCountQ.toString();
			var strThisType = $('#selType').val();
			var strThisQText = $('#txtQuestionText').val();
			var strTempAll = "";
			
			var arrQ = new Object();
			arrQ['questionText'] = strThisQText;
			
			switch( $('#selType').val() ) {
				case 'Textbox':
				   strToAdd += "Q" + strThisQ + ";;" + strThisQText + ";;TEXT;;TEXT;;\n";
				   arrQ['questionType'] = "TEXT";
				   arrQ['questionData'] = "TEXT";
				   break;
				
				case 'Radio':
					$("#addRadioList").children('.addItem').each(function(e) {
						var tmpVal = $(this).children('#txtTextboxVal').val();
						strTempAll += tmpVal + '@@';
					});
					strToAdd += "Q" + strThisQ + ";;" + strThisQText + ";;RADIO;;" + strTempAll + ';;\n';
					arrQ['questionType'] = "RADIO";
				    arrQ['questionData'] = strTempAll;
					break;
				   
				case 'Checkbox':
					$("#addCheckboxList").children('.addItem').each(function(e) {
						var tmpVal = $(this).children('#txtCheckboxVal').val();
						strTempAll += tmpVal + '@@';
				   });
				   strToAdd += "Q" + strThisQ + ";;" + strThisQText + ";;CHK;;" + strTempAll + ';;\n';
				   arrQ['questionType'] = "CHK";
				   arrQ['questionData'] = strTempAll;
				   break;
				   
				case 'List':
					$("#addSelectList").children('.addItem').each(function(e) {
						strTempAll += $(this).children('#txtTextboxVal').val() + '@@';
					});
					strToAdd += "Q" + strThisQ + ";;" + strThisQText + ";;SELECT;;" + strTempAll + ';;\n';
					arrQ['questionType'] = "SELECT";
				    arrQ['questionData'] = strTempAll;
					break;
				
				case 'Matrix':
					strToAdd += "Q" + strThisQ + ";;" + strThisQText + ";;MATRIX;;";
					
					var intColCount = parseInt( $("#txtMatrixCols").val() );
					
					$("#addMatrixInnerCols").children('.matrixOpt').each(function(e) {
						strToAdd += "MATRIXO=" + $(this).children('#txtOText').val() + '@@';
						strTempAll += "MATRIXO=" + $(this).children('#txtOText').val() + '@@';
					});
					
					strToAdd += "||";
					strTempAll += "||";
					
					$("#addMatrixInnerRows").children('.matrixQst').each(function(e) {
						strToAdd += "MATRIXQ=" + $(this).children('#txtQText').val() + '@@';
						strTempAll += "MATRIXQ=" + $(this).children('#txtQText').val() + '@@';
					});
					
					strToAdd += ";;\n";
					
					arrQ['questionType'] = "MATRIX";
				    arrQ['questionData'] = strTempAll;
					
					break;
			}
			
			//Previous data
			if( prevQuiz != '' )
				strToAdd = prevQuiz + strToAdd;
			
			$("#fldQuizData").val(strToAdd);
			$("#fldFlagDone").val( strFlagDone );
			$('#frmMain').submit();
				
			$('#rawData').html('<pre>' + strToAdd + '</pre>');
		}
		
		// Add button handler
		$('#btnAdd').click(function() {
			
			// Valid request?
			if( $('#selType').val() == "Choose")
				return;
				
			doQuestionAdd( '0' );	//Don't flag as done
		});
		
		
		// Save button handler
		$('#btnSave').click(function() {
			
			// Valid request?
			if( $('#selType').val() == "Choose") {
				if( prevQuiz == '' ) {
					alert("שאלון ריק, אין מה לשמור.");
					return;
				}
				
				var tmpResp = confirm("האם אתם בטוחים שברצונכם לשמור ללא הוספת השאלה הנוכחית?");
				if( tmpResp == false )
					return;
			}
				
			doQuestionAdd( '1' );	//Don't flag as done
		});
		
		
		// Remove item of radio/check
		$('.classRem').on('click', function() {
			$(this).parent().remove();
		});
		
		// Change event of the add selection
		$('#selType').on('change', function() {
			
			// Hide 'em all
			$('.classAdd').hide();
			
			// Show QuestionText
			$('#divQuestionText').show();
			$('.trQuestionText').show();
			
			// Reset 'em all - might be wasteful but let's be safe
			$(':input','.classAdd')
				.not(':button, :submit, :reset, :hidden')
				.val('')
				.removeAttr('checked')
				.removeAttr('selected');
			
			switch( $(this).val() ) {
				case 'Textbox':
					// It adds nothing!
					break;
				case 'Checkbox':
					$('#addCheckbox').show();
					break;
				case 'Radio':
					$('#addRadio').show();
					break;
				case 'List':
					$('#addSelect').show();
					break;
				case 'Matrix':
					$('#addMatrix').show();
					$('#divQuestionText').hide();		// No question for matrix
					$('.trQuestionText').hide();
					break;
			}
			
			if( $(this).val() == 'Choose') {
				$('#divQuestionText').hide();
				$('.trQuestionText').hide();
			}
			
		});
		
		
		// Click event to make the default text disappear :)
		$('#txtTextboxName, #txtCheckboxName').on('click', function() {
			if( $(this).val() == "Field Name" )
				$(this).val('');
		});
		
		
		// Click event to make the default text disappear :)
		$('#txtTextboxVal, #txtCheckboxVal').on('click', function() {
			if( $(this).val() == "Field Initial Value" )
				$(this).val('');
		});
		
		
		// Change event for radio/check/list count
		$('#selCheckboxCount, #selRadioCount, #selSelectCount').change( function() {
			var tmpCount = parseInt($(this).val());
			if( $(this).attr('id') == "selRadioCount" ) {
				$('#addRadioList').html('');
			} else if( $(this).attr('id') == "selSelectCount" ) {
				$('#addSelectList').html('');
			} else {
				$('#addCheckboxList').html('');
			}
			
			for( var i = 0; i < tmpCount; i++ ) {
				var strAdd;
				
				if( $(this).attr('id') == "selRadioCount" ) {
					strAdd = '<div class="addItem">אפשרות '+(i+1).toString()+' <input type="textbox" id="txtTextboxVal" value="ערך" /></div>';
					$('#addRadioList').append(strAdd);
				} else if( $(this).attr('id') == "selSelectCount" ) {
					strAdd = '<div class="addItem">אפשרות '+(i+1).toString()+' <input type="textbox" id="txtTextboxVal" value="ערך" /></div>';
					$('#addSelectList').append(strAdd);
				} else {
					strAdd = '<div class="addItem">אפשרות '+(i+1).toString()+' <input type="textbox" id="txtCheckboxVal" value="ערך" /></div>';
					$('#addCheckboxList').append(strAdd);
				}				
			}
			
		});
		
		
		// Matrix button create handler
		$('#btnDoMatrix').on('click', function() {
			var intMatRow = parseInt( $("#txtMatrixLines").val() );
			var intMatCol = parseInt( $("#txtMatrixCols").val() );
			
			if( isNaN(intMatRow) || isNaN(intMatCol) )
				return;
			
			$('#addMatrixInnerRows').html('');
			$('#addMatrixInnerCols').html('');
			
			for( var i = 0; i < intMatRow ; i++ ) {
				var strAdd = '<div class="matrixQst">שאלה '+(i+1).toString()+' <input type="textbox" id="txtQText" value="שאלה'+(i+1).toString()+'" /></div>';
				$('#addMatrixInnerRows').append(strAdd);
			}
			for( var i = 0; i < intMatCol ; i++ ) {
				var strAdd = '<div class="matrixOpt">אפשרות '+(i+1).toString()+' <input type="textbox" id="txtOText" value="אפשרות'+(i+1).toString()+'" /></div>';
				$('#addMatrixInnerCols').append(strAdd);
			}
		});
		
		
		// Show/hide picture div
		$('#chkPicture').change(function() {
			if( $(this).is(":checked") ) {
				$('#upload').val('');
			}
			$('#divPicture').toggle();       
		});
		
	});
</script>
<div id="buildQuiz" style="width: 50%;">
	<form id="frmMain" method="POST" enctype="multipart/form-data">
	<table>
		<tr>
			<td>כותרת השאלון:</td><td><input type="textbox" id="fldQuizTitle" name="fldQuizTitle" value="שאלון כלשהו" /></td>
		</tr>
			<input type="hidden" id="fldQuizData" name="fldQuizData" />
			<input type="hidden" id="fldFlagDone" name="fldFlagDone" />
		<tr>
			<td>Question Number:</td><td><b><?php echo $currQNum; ?><b></td>
		</tr>
		<tr>
			<td>סוג שאלה:</td><td>
				<select id="selType">
				  <option value="Choose">נא בחרו סוג שדה</option>
				  <option value="Textbox">טקסט</option>
				  <option value="Checkbox">בחירה מרובה (סימון)</option>
				  <option value="Radio">בחירה יחידה</option>
				  <option value="List">רשימה</option>
				  <option value="Matrix">מטריצה</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>תמונה:</td><td>
				<label><input type="checkbox" name="chkPicture" id="chkPicture" />הוספה?</label>
				<div id="divPicture" style="display: none;">
					<?php echo $this->Form->input('upload', array('type' => 'file')); ?>
				</div>
			</td>
		</tr>
		<tr class="trQuestionText" style="display: none;">
			<td>טקסט השאלה:</td><td>
			<div id="divQuestionText" style="display: none;">
				<input type="textbox" id="txtQuestionText" value="שאלה" />
			</div>
			</td>
		</tr>
	</table>

	<table>
		<tr id="addCheckbox" class="classAdd">
			<td colspan="2">
				<table>
					<form>
						<tr><td>כמה אפשרויות?</td><td>
						<select id="selCheckboxCount">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
						</select>
						</td></tr>
						
						<tr><td colspan="2">
							<div id="addCheckboxList"> 
								<!-- has one for the first add :) -->
								<div class="addItem">אפשרות 1 
									<input type="textbox" id="txtCheckboxVal" value="ערך" />
								</div>
							</div>
						</td></tr>
					</form>
				</table>
			</td>
		</tr>
		
		<tr id="addRadio" class="classAdd">
			<td colspan="2">
				<table>
					<form>
						<tr><td>כמה אפשרויות?</td><td>
						<select id="selRadioCount">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
						</select>
						</td></tr>
						
						<tr><td colspan="2">
							<div id="addRadioList"> 
								<!-- has one for the first add :) -->
								<div class="addItem">אפשרות 1 
									<input type="textbox" id="txtTextboxVal" value="ערך" />
								</div>
							</div>
						</td></tr>
					</form>
				</table>
			</td>
		</tr>
		
		<tr id="addSelect" class="classAdd">
			<td colspan="2">
				<table>
					<form>
						<tr><td>כמה אפשרויות?</td><td>
						<select id="selSelectCount">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
						</select>
						</td></tr>
						
						<tr><td colspan="2">
						<div id="addSelectList"> 
							<!-- has one for the first add :) -->
							<div class="addItem">אפשרות 1
								<input type="textbox" id="txtTextboxVal" value="ערך" />
							</div>
						</div>
						</td></tr>
					</form>
				</table>
			</td>
		</tr>
		
		<tr id="addMatrix" class="classAdd">
			<td colspan="2">
				<table>
					<form>
						<tr><td>כמה שורות?</td><td><input type="textbox" id="txtMatrixLines" value="" /></td></tr>
						<tr><td>כמה עמודות? </td><td><input type="textbox" id="txtMatrixCols" value="" /> </td></tr>
						<tr><td> </td><td><input type="button" id="btnDoMatrix" value="צור!" /></td></tr>
						<tr><td colspan="2">
							<div id="addMatrixInnerCols"> 
								<!-- Blank -->
							</div>
						</td></tr>
						<tr><td colspan="2">
							<div id="addMatrixInnerRows"> 
								<!-- Blank -->
							</div>
						</td></tr>
					</form>
				</table>
			</td>
		</tr>

		<tr><td><input type="button" id="btnAdd" value="הוספה" /></td>
		    <td><input type="button" id="btnSave" value="שמירה" /></td></tr>
	</table>
	</form>
</div>

<!-- Debug save data -->
<div id="rawData" style="display: none;">
</div>
<div id="rawDataPrev" style="display: none;">
</div>

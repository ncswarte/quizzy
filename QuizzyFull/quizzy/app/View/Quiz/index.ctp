<script language="javascript" type="text/javascript">

$(function() {

	var patID  = <?php echo $patID; ?>;
	var quizID = <?php echo $quizID; ?>;
	
	// Submit handling :)
  	$('#btnSubmit').click(function(){
		var flagError = 0;
		var flagText = "";
		var arrQuestions = new Array();
		
		// Input won't catch <select> so go for name as a whole
		$('[name^="answers"]').each(function() {
		
			// Select doesn't have a type property, so let's fake one
			if( $(this).prop('tagName').toString().toLowerCase() == "select" )
				$(this).attr('type', 'select');
			
			if( !arrQuestions[$(this).attr('name')] )
				arrQuestions[$(this).attr('name')] = $(this).attr('type');
			
		});
		
		for( var currQ in arrQuestions ) {
			
			switch( arrQuestions[currQ] ) {
			
				case "textbox":
					if( $("input[name='"+currQ+"']").val() == '' ) {
						flagError = 1;
						flagText = "Missing text";
					}
					break;
				
				case "radio":
					if( $("input[name='"+currQ+"']:checked").length < 1 ) {
						flagError = 1;
						flagText = "Missing selection";
					}
					break;
				
				case "checkbox":
					if( $("input[name='"+currQ+"']:checked").length < 1 ) {
						flagError = 1;
						flagText = "Missing check";
					}
					break;
				
				case "select":
					if( $("select[name='"+currQ+"']").val() == "!EMPTY!" ) {
						flagError = 1;
						flagText = "Missing select";
					}
					break;
			}
			
			if( flagError > 0 )
				break;
		}
		
		if( flagError > 0 ) {
			alert( "ERROR:\n" + flagText );
			return false;	//Cancel submit
		} else {
			//alert("all good");
		}
		
		if( confirm("Please verify your answers.\nDo you wish to submit?") == false ) {
			return false;
		}
		
		// it'll submit here...

    });

});
</script>
<h2>Quiz</h2>
<h3>Welcome to '<?php echo $patQuiz['Quiz']['quizTitle']; ?>'</h3>
<form id="frmQuiz" method="POST">
	<input type="hidden" name="patID" id="patID" value="<?php echo $patID;?>" />
	<input type="hidden" name="quizID" id="quizID" value="<?php echo $quizID;?>" />

<?php
	
	global $intQuestion;
	$intQuestion = 1;
	$flagMatrix = 0;
	$flagMatrixCurr = "";
	
	if( count($patQuiz['Questions']) < 1 ) {
		echo '<h2>Error: quiz has no questions!</h2>';
	}
	
	foreach( $patQuiz['Questions'] as $currQuestion ) {
		
		if( $currQuestion['questionType'] == "MATRIX" ) {
			
			// We're not in a matrix, gimme the header!
			if( $flagMatrix == 0 ) {
				printMatrixHead ( $currQuestion['questionData'] );
				$flagMatrixCurr = $currQuestion['questionData'];
				$flagMatrix = 1;
			}
			
			// We're in a matrix!!
			if( $flagMatrix != 0 ) {
				// New Matrix or not?
				if( $currQuestion['questionData'] != $flagMatrixCurr ) {
				
					printMatrixClose();
					$flagMatrixCurr = $currQuestion['questionData'];
					printMatrixHead ( $currQuestion['questionData'] );
				}
			}
			printMatrixQ( $currQuestion );
			
		// Not matrix...
		} else {
			// Incase one one open, close it...
			if( $flagMatrix != 0 )
				printMatrixClose();
			
			printQuestion( $currQuestion );
			$flagMatrix = 0;
		}
	}
	
	// Incase the matrix is the last Q:
	if( $flagMatrix != 0 )
		printMatrixClose();
?>
<input type="submit" id="btnSubmit" value="Save!" />
</form>
<div id="divData"></div>


<?php

function printQuestion( $qD ) {
	
	global $intQuestion;
	$tempQID = $qD['questionID'];
	echo "\n<span class=\"spanQuestion\">".$intQuestion++.". ".$qD['questionText']."</span>:";
	
	if( !empty($qD['questionImage']) ) {
		// Limit the maximum image display size
		echo '<p><img src="'. Router::url('/') . 'uploads/question/' . $qD['questionImage'].'" style="border: 1px solid gray; max-width: 85%;"/></p>'."\n";
	}
	
	switch ( $qD['questionType'] ) {
		case "TEXT":
			echo '<br/><input type="textbox" name="answers['.$tempQID.']" /><br />'."\n";
			break;
			
		case "CHK":
			echo '<br/>';
			$tempOptions = preg_split("/@@/", $qD['questionData'] );;
			foreach ( $tempOptions as $currOpt ) {
				if( strlen( $currOpt ) < 1 )
					continue;
					
				echo '<label><input name="answers['.$tempQID.']" type="checkbox" value="'.$currOpt.'" />'.$currOpt.'</label>'."\n";
			}
			break;
			
		case "RADIO":
			$tempOptions = preg_split("/@@/", $qD['questionData'] );
			foreach ( $tempOptions as $currOpt ) {
				if( strlen( $currOpt ) < 1 )
					continue;
					
				echo '<label><input name="answers['.$tempQID.']" type="radio" value="'.$currOpt.'" /> '.$currOpt.'</label>'."\n";
			}
			break;
			
		case "SELECT":
			$tempOptions = preg_split("/@@/", $qD['questionData'] );
			echo '<select name="answers['.$tempQID.']">';
			echo '<option value="!EMPTY!">PLEASE SELECT</option>'."\n";
			foreach ( $tempOptions as $currOpt ) {
				if( strlen( $currOpt ) < 1 )
					continue;
					
				echo '<option value="'.$currOpt.'">'.$currOpt.'</option>'."\n";
			}
			echo '</select>'."\n";
			break;
	}
	echo '<br/><br/>'."\n";
}


function printMatrixHead ( $arrHead ) {
	$arrHead = preg_split("/@@/", $arrHead );
	echo '<table class="tableMatrix">'."\n".'<tr>'."\n".'<th>Question:</th>'."\n";
	foreach ( $arrHead as $currMat ) {
		$currMat = preg_replace( "/^MATRIXO=/", "", $currMat);
		echo '<th class="matrixOption">'.$currMat."</th>";
	}
	echo '</tr>'."\n";
}


function printMatrixClose () {
	echo '</table>';
}


function printMatrixQ ( $qD ) {
	global $intQuestion;
	
	$arrOptions = preg_split("/@@/", preg_replace("/MATRIXO=/", "", $qD['questionData'] ) );
	$intOCount = count(preg_split("/@@/", $qD['questionData'] ));	
	$tempQID = $qD['questionID'];
	
	echo "\t".'<tr><td class="matrixQuestion"><span class="spanQuestion">'.$intQuestion++.". ".$qD['questionText']."</span>:";
	for( $i = 0 ; $i < $intOCount; $i++) {
		echo '<td><input type="radio" name="answers['.$tempQID.']" value="'.$arrOptions[$i].'"/>'."</td>";
	}
	echo '</tr>'."\n";
}

?>
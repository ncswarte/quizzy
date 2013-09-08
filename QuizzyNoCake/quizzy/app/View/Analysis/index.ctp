<h2>Answers Data [Research: <b><?php echo $researchInfo['Research']['researchName']; ?></b>]</h2>

<?php echo $this->Html->css('footable-0.1'); ?>
<?php echo $this->Html->css('footable.sortable-0.1'); ?>
<?php echo $this->Html->script('footable'); ?>
<?php echo $this->Html->script('footable.sortable'); ?>

<script language="javascript" type="text/javascript">
$(function() {
	
	var currDisplay = '<?php echo $currDisplay; ?>';
	
	$('.classAPart').hide();
	$('#selWhich').val( '!EMPTY!' );		//Firefox refresh bug
	
	if( currDisplay == 'quiz' ) {
		$('#selWhich').val( 'Quiz' );
		$('#divQuiz').show();
		$('.footable').footable();
		
	} else if( currDisplay == 'users' ) {
		$('#selWhich').val( 'Users' );
		$('#divUsers').show();
		$('.footable').footable();
	}
	
	$('#selWhich').change( function() {
		$('.classAPart').hide();
		var strWhoToShow = "#div" + $(this).val();
		$( strWhoToShow ).show();
	});
	
	$('#linkSelectAllQuiz').on( 'click', function () {
		$("[name^='quiz']").attr('checked','checked');
	});
	
	$('#linkSelectAllPat').on( 'click', function () {
		$("[name^='patient']").attr('checked','checked');
	});
	
	$('.btnExport').on('click', function() {
		var tblData = $(this).prev().attr("id");
		$("#divRaw").append('<form id="formExport" action="<?php echo $this->base; ?>/export.php" method="post" target="_blank"><input type="hidden" id="exportData" name="exportData" /></form>');
		
		var table = document.getElementById( tblData );
		var stR = '';
		for (var i = 0, row; row = table.rows[i]; i++) {
			for (var j = 0, col; col = row.cells[j]; j++) {
				
				if( !col.firstChild ) {
					// Blank will be '-' instead of blank
					stR += '-,'; 
				} else {
					if(col.innerHTML.indexOf(',') > 0 ) {
						stR += '"'+col.firstChild.nodeValue+'",'; 
					} else {
						stR += col.firstChild.nodeValue+',';
					}
				}
			}

			stR += '\n';
		}
		
		$("#exportData").val(stR);
		$("#formExport").submit().remove();
	});
});
</script>

<form method=POST>
	<div id="divWhich">Choose filter type:
		<select name="selWhich" id="selWhich">
			<option value="!EMPTY!">Choose</option>
			<option value="Quiz">Quiz</option>
			<option value="Users">Users</option>
		</select>
	</div>
	<div class="classAPart" id="divQuiz">
		<h2>Quiz info</h2>
		<form method="POST" id="frmPatient">
			<input type="hidden" name="selType" value="Quiz" />
			<?php
				if( count($quizList) == 0 )
					echo '<p>NONE</p>'."\n";
				
				foreach( $quizList as $currQuiz ) {
					$tempChecked = "";
					if( isset($currSelected[$currQuiz['Quiz']['quizID']] ) )
						$tempChecked = ' checked="checked" ';
					echo '<p><label><input type="checkbox" name="quiz['.$currQuiz['Quiz']['quizID'].']" value="'.$currQuiz['Quiz']['quizID'].'" '.$tempChecked.'>'.$currQuiz['Quiz']['quizTitle']." [".$currQuiz['Quiz']['quizID'].']</label></p>'."\n";
				}
			?>
			<a style="cursor: pointer;" id="linkSelectAllQuiz">Select all</a><br /><br />
			<input type="submit" value="Find" id="btnFindQuiz" />
		</form>
		
		<?php
			if( isset($answerData ) && $currDisplay == "quiz" ) {
				echo '<h2>Quiz Answer Data:</h2>';
				echo "<table class=\"footable\" id=\"tblQuiz\">\n<thead>\n<tr><th>QuestionID</th><th>Answer</th><th>Quiz ID</th><th>Pat ID</tr>\n</thead>\n<tbody>\n";
				
				if( empty($answerData) )
					echo '<tr><td colspan="4" style="text-align: center;">NO DATA :(</td></tr>'."\n";
				
				foreach( $answerData as $currAnswer ) {
					echo '<tr><td>'.$currAnswer['Answers']['questionID']."</td><td>".$currAnswer['Answers']['questionAnswer']."</td><td>".$currAnswer['Answers']['quizID']."</td><td>".$currAnswer['Answers']['patID']."</td></tr>\n";
				}
				echo "</tbody></table>\n";
				echo '<input type="button" class="btnExport" value="Export">'."\n<br />\n";
			}
		?>
	</div>
	<div class="classAPart" id="divUsers">
		<h2>Users info</h2>
		<form method="POST" id="frmPatient">
			<input type="hidden" name="selType" value="Users" />
			<?php
				if( count($patList) == 0 )
					echo '<p>NONE</p>'."\n";
				
				foreach( $patList as $currPat ) {
					$tempChecked = "";
					if( isset($currSelected[$currPat['Patient']['patID']] ) )
						$tempChecked = ' checked="checked" ';
					echo '<p><label><input type="checkbox" name="patient['.$currPat['Patient']['patID'].']" value="'.$currPat['Patient']['patID'].'" '.$tempChecked.'>'.$currPat['0']['patName']." [".$currPat['Patient']['patID'].']</label></p>'."\n";
				}
			?>
			<a style="cursor: pointer;" id="linkSelectAllPat">Select all</a><br /><br />
			<input type="submit" value="Find" id="btnFindUser" />
		</form>
		
		<?php
			if( isset($answerData ) && $currDisplay == "users" ) {
				echo '<h2>User Answer Data:</h2>';
				echo "<table class=\"footable\" id=\"tblUser\">\n<thead>\n<tr><th>QuestionID</th><th>Answer</th><th>Quiz ID</th><th>Pat ID</tr>\n</thead>\n<tbody>\n";
				
				if( empty($answerData) )
					echo '<tr><td colspan="4" style="text-align: center;">NO DATA :(</td></tr>'."\n";
				
				foreach( $answerData as $currAnswer ) {
					echo '<tr><td>'.$currAnswer['Answers']['questionID']."</td><td>".$currAnswer['Answers']['questionAnswer']."</td><td>".$currAnswer['Answers']['quizID']."</td><td>".$currAnswer['Answers']['patID']."</td></tr>\n";
				}
				echo "</tbody></table>\n";
				echo '<input type="button" class="btnExport" value="Export">'."\n<br />\n";
			}
		?>
	</div>
</form>

<?php

function printData( $answerData ) {
	if( isset($answerData ) ) {
		echo '<h2>Quiz Answer Data:</h2>';
		echo "<table>\n<tr><th>QuestionID</th><th>Answer</th><th>Quiz ID</th><th>Pat ID</tr>\n";
		
		if( empty($answerData) )
			echo '<tr><td colspan="4" style="text-align: center;">NO DATA :(</td></tr>'."\n";
		
		foreach( $answerData as $currAnswer ) {
			echo '<tr><td>'.$currAnswer['Answers']['questionID']."</td><td>".$currAnswer['Answers']['questionAnswer']."</td><td>".$currAnswer['Answers']['quizID']."</td><td>".$currAnswer['Answers']['patID']."</td></tr>\n";
		}
		echo "</table>\n";
	}
}
?>
<div id="divRaw">
</div>
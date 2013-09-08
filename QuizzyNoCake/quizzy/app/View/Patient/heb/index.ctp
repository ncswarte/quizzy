<br><h2>ברוכים השבים <?php echo $patInfo['0']['Patient']['patFirstname']; ?>!</h2>
<?php
	if( sizeof( $patQuiz ) < 1 ) {
		$tempPending = 'no allocated quizzes';
	} else {
		$tempPending = 0;
		
		foreach ($patQuiz as $currQuiz ) {
			if( $currQuiz['1'] == 0 )
				$tempPending++;
		}
	
		if( $tempPending == 1 ) {
			$tempPending = "ממתין לך שאלון אחד";
		} else {
			$tempPending = "ממתינים לך ".$tempPending." שאלונים";
		}
	}
	
	echo '<h3>'.$tempPending.'</h3>'."\n";
?>
<h2>שאלונים:</h2>
<table style="width: 50%; border: 1px solid gray;">
<tr>
	<th>מחקר</th><th>שם שאלון</th><th>מזהה שאלון</th><th>מצב</th>
</tr>
<?php	
	if( sizeof( $patQuiz ) < 1 ) {
		echo '<tr><td colspan="4" style="text-align:center;">אין שאלונים מוגדרים!</td></tr>';
	} else {
	
		foreach ($patQuiz as $currQuiz ) {
			$tempResearchName = $researchInfo[$currQuiz['0']];
			$tempQuizName = $quizInfo[$currQuiz['0']]['Quiz']['quizTitle'];
			if( $currQuiz['1'] == 1 ) {
				echo '<tr><td>'.$tempResearchName.'</td><td>'.$tempQuizName.'</td><td>'.$currQuiz['0'].'</td><td>הושלם</td></tr>';
			} else {
				echo '<tr><td>'.$tempResearchName.'</td><td>'.$tempQuizName.'</td><td><a href="'.$this->Html->url(array('controller' => 'Quiz', 'action' => 'index', $patInfo['0']['Patient']['patID'], $currQuiz['0']), true ).'">'.$currQuiz['0']."</a></td><td><b>טרם הושלם</b></td></tr>";
			}
		}
	}
?>
</table>
<br/><br/>
<h2>פרטי פרופיל:</h2>
<table style="width: 50%; border: 1px solid gray;">
	<tr><th colspan="2">פרופיל:</th></tr>
	<tr><td>מזהה נבדק:</td>	<td><?php echo $patInfo['0']['Patient']['patID']; ?></td></tr>
	<tr><td>שם פרטי:</td>		<td><?php echo $patInfo['0']['Patient']['patFirstname']; ?></td></tr>
	<tr><td>שם משפחה:</td>		<td><?php echo $patInfo['0']['Patient']['patLastname']; ?></td></tr>
	<tr><td>גיל:</td>			<td><?php echo $patInfo['0']['Patient']['patAge']; ?></td></tr>
	<tr><td>מין:</td>		<td><?php echo $patInfo['0']['Patient']['patGender']; ?></td></tr>
	<tr><td>כתובת:</td>		<td><?php echo $patInfo['0']['Patient']['patAddress']; ?></td></tr>
	<tr><td>טלפון:</td>			<td><?php echo $patInfo['0']['Patient']['patPhone']; ?></td></tr>
</table>
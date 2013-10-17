<br><h2>Welcome back <?php echo $patInfo['0']['Patient']['patFirstname']; ?>!</h2>
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
			$tempPending .= " pending quizz";
		} else {
			$tempPending .= " pending quizzes";
		}
	}
	
	echo '<h3>You have '.$tempPending.'</h3>'."\n";
?>
<h2>Quizzes:</h2>
<table style="width: 50%; border: 1px solid gray;">
<tr>
	<th>Research</th><th>Quiz Name</th><th>Quiz ID</th><th>Status</th>
</tr>
<?php	
	if( sizeof( $patQuiz ) < 1 ) {
		echo '<tr><td colspan="4" style="text-align:center;">NOTHING!</td></tr>';
	} else {
	
		foreach ($patQuiz as $currQuiz ) {
			$tempResearchName = $researchInfo[$currQuiz['0']];
			$tempQuizName = $quizInfo[$currQuiz['0']]['Quiz']['quizTitle'];
			if( $currQuiz['1'] == 1 ) {
				echo '<tr><td>'.$tempResearchName.'</td><td>'.$tempQuizName.'</td><td>'.$currQuiz['0'].'</td><td>Completed</td></tr>';
			} else {
				echo '<tr><td>'.$tempResearchName.'</td><td>'.$tempQuizName.'</td><td><a href="'.Router::url(array('controller' => 'Quiz', 'action' => 'index', $patInfo['0']['Patient']['patID'], $currQuiz['0']), true ).'">'.$currQuiz['0']."</a></td><td><b>Not completed/Pending</b></td></tr>";
			}
		}
	}
?>
</table>
<br/><br/>
<h2>Profile info:</h2>
<table style="width: 50%; border: 1px solid gray;">
	<tr><th colspan="2">Profile:</th></tr>
	<tr><td>Participant ID:</td>	<td><?php echo $patInfo['0']['Patient']['patID']; ?></td></tr>
	<tr><td>Firstname:</td>		<td><?php echo $patInfo['0']['Patient']['patFirstname']; ?></td></tr>
	<tr><td>Lastname:</td>		<td><?php echo $patInfo['0']['Patient']['patLastname']; ?></td></tr>
	<tr><td>Age:</td>			<td><?php echo $patInfo['0']['Patient']['patAge']; ?></td></tr>
	<tr><td>Gender:</td>		<td><?php echo $patInfo['0']['Patient']['patGender']; ?></td></tr>
	<tr><td>Address:</td>		<td><?php echo $patInfo['0']['Patient']['patAddress']; ?></td></tr>
	<tr><td>Phone:</td>			<td><?php echo $patInfo['0']['Patient']['patPhone']; ?></td></tr>
</table>
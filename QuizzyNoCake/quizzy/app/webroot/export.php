<?php
	// If you'd like to specific an application, go for it:
		// header('Content-Type: application/force-download; charset=utf-8');
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-disposition: attachment; filename=Data.csv');
	$tempData = stripslashes($_POST['exportData']);
	print $tempData;
?>

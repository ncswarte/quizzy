<h2>הוספת מחקר:</h2>
<?php
	echo $this->Form->create('Research',array('url' => array('controller' => 'Admin', 'action' => 'addResearch')));
	echo $this->Form->hidden('Research.researchID');
	echo $this->Form->input('Research.researchName', array('label' => 'שם המחקר') );
	echo $this->Form->end('שמירה');
?>
<br /><br /><br />
<div id="rawData" style="display: none;">
</div>
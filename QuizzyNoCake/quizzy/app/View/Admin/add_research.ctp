<h2>Add research:</h2>
<?php
	echo $this->Form->create('Research',array('url' => array('controller' => 'Admin', 'action' => 'addResearch')));
	echo $this->Form->hidden('Research.researchID');
	echo $this->Form->input('Research.researchName', array('label' => 'Research Name') );
	echo $this->Form->end('Save');
?>
<br /><br /><br />
<div id="rawData" style="display: none;">
</div>
<div class="users form">
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Please enter your username and password'); ?></legend>
		<?php 	echo $this->Form->input('username', array('label' => 'מזהה משתמש') );
				echo $this->Form->input('password', array('label' => 'סיסמא') );
		?>
	</fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>
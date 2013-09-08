<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
 
$currentLanguage = Configure::read('Config.language');

// Make sure that the language is hebrew & that the actual view is also hebrew [could be a view that wasn't translated]
$currentHebPos = strpos($this->viewPath, 'heb');

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		Quizzy - <?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		// Hebrew CSS [can be changed to anything you see fit :), if required ]
		if( ($currentLanguage == 'heb') && ( $currentHebPos !== false) ) {
			echo $this->Html->css('cake.generic.heb');
		} else {
			echo $this->Html->css('cake.generic');
		}
		
		echo $this->Html->script('jquery-1.10.2.min');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	
	<script type="text/javascript">
		// Monitor jQ loading :)
		window.onload = function() {
			if (window.jQuery) {  
				// jQuery is loaded  
			} else {
				// jQuery is not loaded
				alert("Error loading JQ libraries, functionallity won't be full!");
			}
		}
	</script>
	
</head>
<body>
	<div id="container">
		<div id="header">
			<div class="logo"><img src='<?php echo Router::url('/'); ?>/img/logo.png'/></div>
			<h1><?php echo $title_for_layout; 
			// From analysis we can either go admin/assistant
			if( strtolower($this->params['controller']) == "analysis" && $this->params['action'] == "index" ) {
				echo ' ['.$this->Html->link(__('Back'), array('controller' => $userData['role'], 'action' => 'index'), array('title' => 'Back to main page')).']';
			// Show the back link for any non index AND NOT for the home.ctp page :)
			//		TODO home link on home?
			} elseif( $this->params['action'] != "index" && ($this->params['controller'] != "pages" && strtolower($this->params['controller']) != "users" ) ) {
				echo ' ['.$this->Html->link(__('Back'), array('action' => 'index'), array('title' => 'Back to main page')).']';
			}
			?></h1>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false, 'language' => 'd')
				);
			?>
			<div id="divLanguage" style="float:left;">
			<?php
				echo "\t".$this->Html->link('English', $this->passedArgs + array('language' => 'eng'));
				echo ' | ';
				echo $this->Html->link('עברית', $this->passedArgs + array('language' => 'heb'))."\n";
			?>
			</div>
			<?php if($this->Session->read('Auth.User')) { ?>
				<?php echo __('Logged in as').': '; echo $this->Session->read('Auth.User')['username']; echo ' | '.$this->Html->link( __('Logout'), array('controller' => 'Users', 'action' => 'logout') ); ?>
			<?php } ?>
		</div>
	</div>
	<?php //echo $this->element('sql_dump'); ?>
	<?php 	// echo '<pre>';
			// print_r($this->params);
			// echo '</pre>';
	?>
</body>
</html>

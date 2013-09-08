<?php
	$currentLanguage = Configure::read('Config.language');
	// Make sure that the language is hebrew & that the actual view is also hebrew [could be a view that wasn't translated]
	$currentHebPos = strpos($this->viewPath, 'heb');
?>
<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title_for_layout; ?></title>
        <meta name="description" content="">
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="cleartype" content="on">

		<?php
			// Hebrew Mobile CSS [can be changed to anything you see fit :), if required ]
			if( ($currentLanguage == 'heb') && ( $currentHebPos !== false) ) {
				echo $this->Html->css('QuizzyMobile.heb');
			} else {
				echo $this->Html->css('QuizzyMobile');
			}
			echo $this->Html->meta('icon');
			
			echo $this->Html->script('jquery.mobile-1.3.2.min');
			echo $this->Html->script('jquery-1.10.2.min');
			
		?>
		
		<script type="text/javascript">
			// Monitor jQ loading :)
			window.onload = function() {
				if (window.jQuery) {  
					// jQuery is loaded  
					//alert("Yeah!");
				} else {
					// jQuery is not loaded
					alert("Error loading JQ libraries, functionallity won't be full!!");
				}
			}
		</script>
    </head>
<body>
	<div id="container">
		<div id="header">
			<h1>Quizzy Mobile</h1>
		</div>		
		<div id="content">
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		<div id="divLanguage" style="float:left;">
			<?php
				echo "\t".$this->Html->link('English', $this->passedArgs + array('language' => 'eng'));
				echo ' | ';
				echo $this->Html->link('עברית', $this->passedArgs + array('language' => 'heb'))."\n";
			?>
		</div>
		<span>
			<?php if($this->Session->read('Auth.User')) { ?>
				<?php echo __('Logged in as').': '; echo $this->Session->read('Auth.User')['username']; echo ' | '.$this->Html->link( __('Logout'), array('controller' => 'Users', 'action' => 'logout') ); ?>
			<?php } ?>
		</span>
		</div>
	</div>
</body>
</html>

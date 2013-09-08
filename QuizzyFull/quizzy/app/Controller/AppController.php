<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components = array('Cookie', 'RequestHandler', 'Session', 'Auth' => array(
		'authError' => 'You must be logged in to view this page.',
        'loginError' => 'Invalid Username or Password entered, please try again.',
		'authorize' => 'Controller'
	) );
	
	//set an alias for the newly created helper: Html<->MyHtml
	public $helpers = array('Html' => array('className' => 'MyHtml'));

	public function beforeFilter() {
	
		// Translate auth messages
		if( isset($this->Auth) ) {
			$this->Auth->loginError = __('You must be logged in to view this page.', true);
			$this->Auth->authError 	= __('Invalid Username or Password entered, please try again.', true); 
		}
		
		//rsack bugs
		$this->disableCache();
		$this->_setLanguage();
		$locale = Configure::read('Config.language');
		
		// Will only work if you have both the directory for the langauge AND the acutal view
		$tempViewFilename = Inflector::underscore($this->action);
		if ($locale && file_exists(APP.'View'.DS.$this->viewPath.DS.$locale.DS.$tempViewFilename.'.ctp')) {
			$this->viewPath = $this->viewPath. DS . $locale;
		}
		
		// Mobile device?
		if ($this->RequestHandler->isMobile()) {
			$this->is_mobile = true;
			$this->set('is_mobile', true );
			$this->autoRender = false;
		}
		
		// Auth
		$this->Auth->allow('login');
		
		// Take them to the $role/index after login :)
		$this->Auth->loginRedirect = array('controller' => ucfirst($this->Auth->user('role')), 'action' => 'index');
		
	}
	
	// Update the render layout if required
	public function afterFilter() {
		if (isset($this->is_mobile) && $this->is_mobile) {
			$this->autoRender = false;
			$this->render($this->action, 'mobile' );
		}
	}
 
	private function _setLanguage() {
		//if the cookie was previously set, and Config.language has not been set
		//write the Config.language with the value from the Cookie
		if ($this->Cookie->read('lang') && !$this->Session->check('Config.language')) {
			$this->Session->write('Config.language', $this->Cookie->read('lang'));
		
		//if the user clicked the language URL
		} else if ( isset($this->params['language']) && ($this->params['language'] !=  $this->Session->read('Config.language')) ) {
			//then update the value in Session and the one in Cookie
			$this->Session->write('Config.language', $this->params['language']);
			$this->Cookie->write('lang', $this->params['language'], false, '20 days');
		
		} else if ( isset($this->params['language']) && ($this->params['language'] ==  $this->Session->read('Config.language')) ) {
			// We need to update the configured language!
			Configure::write('Config.language', $this->Session->read('Config.language'));
		}
		
		Configure::write('Config.language', $this->Session->read('Config.language'));
	}

	// Override redirect to support languages
	public function redirect( $url, $status = NULL, $exit = true ) {
		if (!isset($url['language']) && $this->Session->check('Config.language') ) {
			
			// Fix for some URLs that don't have an array of settings
			if( gettype($url) == "array")
				$url['language'] = $this->Session->read('Config.language');
		}
		parent::redirect($url,$status,$exit);
	}
	
	// Check if is authorized
	public function isAuthorized($user) {
		// Admin can access every action
		//		if you want to debug the authorization functions - debug( 'AppController: isAuthorized');
		if (isset($user['role']) && $user['role'] === 'admin') {
			return true;
		}
		
		// Default deny
		$this->redirect('/');		
		return false;
	}
}

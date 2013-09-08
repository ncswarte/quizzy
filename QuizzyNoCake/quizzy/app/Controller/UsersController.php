<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $components = array('Auth');
	
	public function login() {
		
		$this->set('title_for_layout', __('Login') );
		
		// Already logged in?
		if ($this->Auth->user('id')) {
			if( $this->Auth->user()['role'] == 'admin' ) {
				$this->redirect(array('controller' => 'Admin', 'action' => 'index'));
			} elseif ( $this->Auth->user()['role'] == 'patient' ) {
				$this->redirect(array('controller' => 'Patient', 'action' => 'index'));
			} elseif ( $this->Auth->user()['role'] == 'assistant' ) {
				$this->redirect(array('controller' => 'Assistant', 'action' => 'index'));
			}
		}
		
	    if ($this->request->is('post')) {
	        if ($this->Auth->login()) {
				if( $this->Auth->redirect() != '/' ) {
					$this->redirect($this->Auth->redirect());   
				} else {
					if( $this->Auth->user()['role'] == 'admin' ) {
						$this->redirect(array('controller' => 'Admin', 'action' => 'index'));
					} elseif ( $this->Auth->user()['role'] == 'patient' ) {
						$this->redirect(array('controller' => 'Patient', 'action' => 'index'));
					} elseif ( $this->Auth->user()['role'] == 'assistant' ) {
						$this->redirect(array('controller' => 'Assistant', 'action' => 'index'));
					} else {
						//Stay here, something went wrong
						debug('not an admin nor a patient!');
					}
				}
	        } else {
	            $this->Session->setFlash( __('Your username/password combination was incorrect') );
	        }
	    }
	}
	
	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login', 'logout'); 
    }
	
	public function logout() {
		$this->Auth->logout();
		$this->Session->destroy();
	    $this->redirect(array('controller' => 'Users', 'action' => 'login'));
	}
}

?>
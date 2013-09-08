<?php
 
class User extends AppModel {
	
    public $validate = array(
		'username' => array(
			'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Username required.'
            )
		),
		'password' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Password is required'
			),
			'min_length' => array(
				'rule' => array('minLength', '6'),  
				'message' => 'Password must have a mimimum of 6 characters'
			)
		),
        'role' => array(
			'valid' => array(
				'rule' => array('inList', array('admin', 'assistant', 'patient')),
				'message' => 'Please enter a valid role',
				'allowEmpty' => false
			)
        )
    );
	
	public function beforeSave($options = array()) {
		// hash our password
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		
		// if we get a new password, hash it
		if (isset($this->data[$this->alias]['password_update']) && !empty($this->data[$this->alias]['password_update'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password_update']);
		}
		
		// fallback to our parent
		return parent::beforeSave($options);
    }
 
}
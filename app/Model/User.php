<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
/**
 * User Model
 *
 * @property Authority $Authority
 */
class User extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	public $validate = Array(
	    'name' => Array(
		'unique' => Array(
		    'rule' => 'isUnique',
		    'message' => 'This name is already in use.'
		)
	    ),
	    'email' => Array(
		'notEmpty' => Array(
		    'rule' => Array('notEmpty'),
		    'message' => 'Enter valid email address.'
		),
		'unique' => Array(
		    'rule' => Array('isUnique'),
		    'message' => 'This e-mail address is already in use.'
		)
	    ),
	    'password' => Array(
		'notEmpty' => Array(
		    'rule' => Array('notEmpty'),
		    'message' => 'Enter valid password.'
		),
		'length' => Array(
		    'rule' => Array('minlength', '6'),
		    'message' => 'Minimim 8 characters long.'
		)
	    ),
	    /*
	    'role' => Array(
		'valid' => Array(
		    'rule' => Array('inList', Array('admin', 'staff', 'author')),
		    'message' => '$B8"8B$rA*Br$7$F$/$@$5$$!#(B',
		    'allowEmpty' => false
		)
	    )
	     */
	);

	public function beforeSave($options = null) {
	    if (isset($this->data[$this->alias]['password'])) {
		$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
	    }
	    return true;
	}

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
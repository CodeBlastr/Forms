<?php
App::uses('FormsAppModel', 'Forms.Model');
/**
 * FormAnswer Model
 *
 * @property FormInput $FormInput
 * @property User $User
 */
class FormAnswer extends FormsAppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'FormInput' => array(
			'className' => 'Forms.FormInput',
			'foreignKey' => 'form_input_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	/**
	 * Saves answers from a Q&A form
	 * @param array $data
	 */
	public function record($data) {
		debug($data);
		break;
	}
	
}

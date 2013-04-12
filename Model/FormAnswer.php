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
	 * 
	 * @todo Move to a model !
	 * 
	 * @param array $data
	 */
	public function record($data) {

//		$form = $this->FormInput->Form->find('first', array(
//			'conditions' => array(
//				'Form.id' => $data['Form']['id']
//			)
//		));
		
		// save the answer
		$form = $this->FormInput->Form->display($data['Form']['id']);
		$formIds = Set::extract('/id', $form['FormInput']); // get IDs in the same order as they are in $data

		$i = 0;
		foreach ( $data['FormAnswer'] as $formAnswer ) {
			$data['FormAnswer'][$i]['user_id'] = CakeSession::read('Auth.User.id');
			$data['FormAnswer'][$i]['form_input_id'] = $formIds[$i];
			++$i;
		}

		if ( $this->saveMany($data['FormAnswer']) ) {
			/** @TODO :  CHANGE THIS TO BE A CALLBACK TO THE Form.foreign_model !!! */
			// save an empty grade for the teacher to grade later
			App::uses('Grade', 'Courses.Model');
			$Grade = new Grade;
			$grade['Grade']['form_id'] = $data['Form']['id'];
			$grade['Grade']['student_id'] = CakeSession::read('Auth.User.id');
			$grade['Grade']['course_id'] = $form['Form']['foreign_key'];

			if ( $Grade->save($grade) ) {
				return true;
			} else {
				die('x');
				throw new Exception('Grade did not initialize.');
			}
		} else {
			throw new Exception('Form Answers did not save.');
		}
		
	}
	
}

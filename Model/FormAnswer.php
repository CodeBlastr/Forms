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
 * @param array $data
 */
	public function record($data) {
		// save the answer
		$form = $this->FormInput->Form->display($data['Form']['id']);
		$formIds = Set::extract('/id', $form['FormInput']); // get IDs in the same order as they are in $data

		$i = 0;
		foreach ($data['FormAnswer'] as $formAnswer) {
			$data['FormAnswer'][$i]['user_id'] = CakeSession::read('Auth.User.id');
			$data['FormAnswer'][$i]['form_input_id'] = $formIds[$i];
			$data['FormAnswer'][$i]['answer'] = !empty($data['FormAnswer'][$i]['answer']) ? $data['FormAnswer'][$i]['answer'] : $formAnswer;
			++$i;
		}
		if ($this->saveMany($data['FormAnswer'])) {
			// fire a callback to Form.foreign_model if necessary
			if (!empty($form['Form']['foreign_model'])) {	
				try {
					App::uses($form['Form']['foreign_model'], ZuhaInflector::pluginize($form['Form']['foreign_model']).'.Model');
					$Model = new $form['Form']['foreign_model'];
					if(method_exists($Model,'afterFormAnswerRecord') && is_callable(array($Model,'afterFormAnswerRecord'))) {
				    	return $Model->afterFormAnswerRecord($form, $data);
					} else {
						return true;
					}
				} catch (Exception $e) {
					throw new Exception($e->getMessage());
				}
			} else {
				return true;
			}
		} else {
			throw new Exception('Form Answers did not save.');
		}
	}
	
}

<?php
App::uses('FormsAppModel', 'Forms.Model');
/**
 * Form Model
 *
 * Handles the grouping of form fieldsets, and the Form settings
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.forms.models
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class Form extends FormsAppModel {

	public $name = 'Form';

	public $validate = array(
		'name' => array('notempty'),
		'model' => array('notempty'),
	);

	public $actsAs = array('Copyable');

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $hasMany = array(
		'FormFieldset' => array(
			'className' => 'Forms.FormFieldset',
			'foreignKey' => 'form_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
			),
		'FormInput' => array(
			'className' => 'Forms.FormInput',
			'foreignKey' => 'form_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
			),
		);


/**
 * Used to create the convenience field "url".
 *
 * @param {data} 		The $this->data array.
 */
	public function add($data) {
		if (empty($data['Form']['copy']) || $data['Form']['copy'] == 'custom') {
			// create the form url convenience field
			$plugin = Inflector::underscore(Inflector::pluralize($data['Form']['plugin']));
			$controller = Inflector::underscore(Inflector::pluralize($data['Form']['model']));
			$data['Form']['url'] = '/'.$plugin.'/'.$controller.'/'.$data['Form']['action'];
			if ($this->save($data)) {
				return true;
			} else {
				throw new Exception(__('Form add failed'));
			}
		} else {
			return $this->_formTemplate($data['Form']['copy']);
		}
	}


/**
 * Used to display correct view of form inputs (add, edit, view)
 * Also used to retrieve form array for FormAnswer
 *
 * @param {id} 			id of the form to display, or an array with keys plugin(optional), and model to look up the form
 * @param {type}		valid values are add, edit, view
 * @return {array}		the data pulled from the db
 */
	public function display($id, $type = 'add') {
		if ($type == 'edit') {
			$inputConditions = array('FormInput.is_editable' => 1);
		} else if ($type == 'view') {
			$inputConditions = array('FormInput.is_visible' => 1);
		} else {
			$inputConditions = array('FormInput.is_addable' => 1);
		}

		if (is_array($id)) {
			// put model look up condition here
			if (isset($id['plugin'])) {
				$formConditions['Form.plugin'] = $id['plugin'];
			}
			if (isset($id['model'])) {
				$formConditions['Form.model'] = $id['model'];
			}
		} else {
			// put id look up conditions here
			$formConditions = array('Form.id' => $id);
		}
		$formGroup = $this->find('first', array(
			'conditions' => $formConditions,
			'contain' => array(
				'FormFieldset' => array(
					'order' => 'FormFieldset.order',
					'FormInput' => array(
						'conditions' => $inputConditions,
						'order' => 'FormInput.order',
						),
					),
				'FormInput' => array(
					'conditions' => $inputConditions,
					'order' => 'FormInput.order',
					),
				),
			));
		if (!empty($formGroup)) {
			return $formGroup;
		} else {
			return false;
		}
	}

/**
 * Pull up a form and notify notifiees
 *
 * @return bool
 */
	public function notify($data = null) {
		if (!empty($data['Form']['id'])) {
			$form = $this->find('first', array(
				'conditions' => array(
					'Form.id' => $data['Form']['id'],
					),
				'fields' => array('response_email', 'notifiees', 'response_subject', 'response_body')
			));
            if (!empty($form['Form']['response_email'])) {
            	$this->autoRespond($form, $data);
            }

			if (!empty($form['Form']['notifiees'])) {
				$notifiees = explode(',', str_replace(' ', '', $form['Form']['notifiees']));
				foreach ($notifiees as $recipient) {
					$messages = $data;
					unset($messages['Form']);
					$message = ZuhaSet::keyAsPaths($messages, array('parse' => true));
					$this->__sendMail($recipient, 'Form submission', $message);
				}
			}
			return true;
		} else {
			throw new Exception(__('Form id is null, cannot check notifications.'));
		}
	}

/**
 * Send an auto response if configured
 * @todo Make sure we are passing the FormInput.id via Form.response_email
 */
    public function autoRespond($form, $data) {
        $formInputWithEmail = $this->FormInput->find('first', array(
            'conditions' => array('id' => $form['Form']['response_email'])
        ));
		if (!empty($formInputWithEmail)) {
			$model = !empty($formInputWithEmail['FormInput']['model_override']) ? substr($formInputWithEmail['FormInput']['model_override'], 0,  strpos($formInputWithEmail['FormInput']['model_override'], '.')) : $data['Form']['model']; // get the model name even if it is in Model.X format
			$extractString = !empty($formInputWithEmail['FormInput']['model_override']) ? '/' . substr($formInputWithEmail['FormInput']['model_override'], (1 +   strpos($formInputWithEmail['FormInput']['model_override'], '.'))) . '/' . $formInputWithEmail['FormInput']['code'] : '/' . $formInputWithEmail['FormInput']['code']; // N/field OR field, ex. /8/value OR /value
	        $toEmail = Set::extract($extractString, $data[$model]);
       		$this->__sendMail($toEmail[0], $form['Form']['response_subject'], $form['Form']['response_body']);
       	}
    }


/**
 * Form Template
 *
 * Create a pre-determined form.
 * @var string
 * @return array
 */
	protected function _formTemplate($type = 'contact') {
		if ($type == 'contact') {
			$contact['Form']['name'] = 'Contact Form';
			$contact['Form']['method'] = 'file';
			$contact['Form']['plugin'] = 'Contacts';
			$contact['Form']['model'] = 'Contact';
			$contact['Form']['action'] = 'add';
			$contact['Form']['url'] = '/contacts/contacts/add';
			$contact['Form']['notifiees'] = '';
			$contact['Form']['success_message'] = 'Thank you.';
			$contact['Form']['success_url'] = '/home';
			$contact['Form']['fail_message'] = 'Please try again.';
			$contact['Form']['fail_url'] = '';

			$contact['FormInput'][0]['code'] = 'name';
			$contact['FormInput'][0]['name'] = 'Name';
			$contact['FormInput'][0]['show_label'] = 1;
			$contact['FormInput'][0]['input_type'] = 'text';
			$contact['FormInput'][0]['is_required'] = 1;
			$contact['FormInput'][0]['is_not_db_field'] = 0;
			$contact['FormInput'][0]['is_visible'] = 1;
			$contact['FormInput'][0]['is_addable'] = 1;
			$contact['FormInput'][0]['is_editable'] = 1;

			$contact['FormInput'][1]['code'] = 'contact_detail_type';
			$contact['FormInput'][1]['name'] = 'Contact Detail Type';
			$contact['FormInput'][1]['model_override'] = 'ContactDetail.0';
			$contact['FormInput'][1]['input_type'] = 'hidden';
			$contact['FormInput'][1]['default_value'] = 'Email';
			$contact['FormInput'][1]['is_not_db_field'] = 0;
			$contact['FormInput'][1]['is_visible'] = 1;
			$contact['FormInput'][1]['is_addable'] = 1;
			$contact['FormInput'][1]['is_editable'] = 1;

			$contact['FormInput'][2]['code'] = 'value';
			$contact['FormInput'][2]['name'] = 'Email';
			$contact['FormInput'][2]['show_label'] = 1;
			$contact['FormInput'][2]['model_override'] = 'ContactDetail.0';
			$contact['FormInput'][2]['input_type'] = 'text';
			$contact['FormInput'][2]['is_required'] = 1;
			$contact['FormInput'][2]['is_not_db_field'] = 0;
			$contact['FormInput'][2]['is_visible'] = 1;
			$contact['FormInput'][2]['is_addable'] = 1;
			$contact['FormInput'][2]['is_editable'] = 1;
			$contact['FormInput'][2]['validation'] = 'email';

			$contact['FormInput'][3]['code'] = 'contact_detail_type';
			$contact['FormInput'][3]['name'] = 'Contact Detail Type Notes';
			$contact['FormInput'][3]['model_override'] = 'ContactDetail.1';
			$contact['FormInput'][3]['input_type'] = 'hidden';
			$contact['FormInput'][3]['default_value'] = 'Note';
			$contact['FormInput'][3]['is_not_db_field'] = 0;
			$contact['FormInput'][3]['is_visible'] = 1;
			$contact['FormInput'][3]['is_addable'] = 1;
			$contact['FormInput'][3]['is_editable'] = 1;

			$contact['FormInput'][4]['code'] = 'value';
			$contact['FormInput'][4]['name'] = 'Comments';
			$contact['FormInput'][4]['show_label'] = 1;
			$contact['FormInput'][4]['model_override'] = 'ContactDetail.1';
			$contact['FormInput'][4]['input_type'] = 'textarea';
			$contact['FormInput'][4]['is_required'] = 1;
			$contact['FormInput'][4]['is_not_db_field'] = 0;
			$contact['FormInput'][4]['is_visible'] = 1;
			$contact['FormInput'][4]['is_addable'] = 1;
			$contact['FormInput'][4]['is_editable'] = 1;

    		$contact['FormInput'][3]['code'] = 'contact_detail_type';
			$contact['FormInput'][3]['name'] = 'Contact Detail Type Url';
			$contact['FormInput'][3]['model_override'] = 'ContactDetail.2';
			$contact['FormInput'][3]['input_type'] = 'hidden';
			$contact['FormInput'][3]['default_value'] = 'Current Url';
			$contact['FormInput'][3]['is_not_db_field'] = 0;
			$contact['FormInput'][3]['is_visible'] = 1;
			$contact['FormInput'][3]['is_addable'] = 1;
			$contact['FormInput'][3]['is_editable'] = 1;

			$contact['FormInput'][5]['code'] = 'value';
			$contact['FormInput'][5]['name'] = 'Page URL';
			$contact['FormInput'][5]['model_override'] = 'ContactDetail.2';
			$contact['FormInput'][5]['input_type'] = 'hidden';
			$contact['FormInput'][5]['system_default_value'] = 'current page url';
			$contact['FormInput'][5]['is_required'] = 0;
			$contact['FormInput'][5]['is_not_db_field'] = 0;
			$contact['FormInput'][5]['is_visible'] = 1;
			$contact['FormInput'][5]['is_addable'] = 1;
			$contact['FormInput'][5]['is_editable'] = 1;

			return $this->saveAll($contact, array('validate' => false));
		}
	}

/**
 * Copy types
 *
 * The types of forms available in $this->_formTemplate() in a form options format.
 * @return array
 */
	public function copyTypes() {
		return array(
			'contact' => 'Contact Form',
			'answers' => 'Q & A',
			'custom' => 'Custom',
			);
	}

/**
 * Available form methods
 *
 * @return array
 */
	public function methods() {
		return array(
			'post' => 'post',
			'get' => 'get',
			'file' => 'file',
			'put' => 'put',
			'delete' => 'delete'
			);
	}

}
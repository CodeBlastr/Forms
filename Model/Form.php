<?php
/**
 * Form Model
 *
 * Handles the grouping of form fieldsets, and the Form settings
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha™ Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.forms.models
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class Form extends FormsAppModel {

	var $name = 'Form';	
	var $validate = array(
		'name' => array('notempty'),
		'model' => array('notempty'),
	);
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
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
		)
	);
	

/**
 * Used to create the convenience field "url".
 *
 * @param {data} 		The $this->data array.
 */
	function add($data) {
		# create the form url convenience field
		$plugin = Inflector::underscore(Inflector::pluralize($data['Form']['plugin']));
		$controller = Inflector::underscore(Inflector::pluralize($data['Form']['model']));
		$data['Form']['url'] = '/'.$plugin.'/'.$controller.'/'.$data['Form']['action'];
		if ($this->save($data)) {
			return true;
		} else {
			return false;
		}
	}


/**
 * Used to display correct view of form inputs (add, edit, view)
 *
 * @param {id} 			id of the form to display, or an array with keys plugin(optional), and model to look up the form
 * @param {type}		valid values are add, edit, view
 * @return {array}		the data pulled from the db
 */
	function display($id, $type = 'add') { 
		if ($type == 'edit') {
			$inputConditions = array('FormInput.is_editable' => 1);
		} else if ($type == 'view') {
			$inputConditions = array('FormInput.is_visible' => 1);
		} else {
			$inputConditions = array('FormInput.is_addable' => 1);
		}
			
		if (is_array($id)) {
			# put model look up condition here
			if (isset($id['plugin'])) {
				$formConditions['Form.plugin'] = $id['plugin'];
			}
			if (isset($id['model'])) {
				$formConditions['Form.model'] = $id['model'];
			}
		} else {
			# put id look up conditions here
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
				),
			));	
		if (!empty($formGroup)) {
			return $formGroup;
		} else {
			return false;
		}
	}
	
	
}
?>
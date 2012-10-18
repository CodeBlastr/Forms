<?php
App::uses('FormsAppModel', 'Forms.Model');
/**
 * Form Fieldset Model
 *
 * Handles the grouping of formInputs.
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
 * @link          http://zuha.com Zuha™ Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.forms.models
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class FormFieldset extends FormsAppModel {

	var $name = 'FormFieldset';	
	var $validate = array(
		'name' => array('notempty'),
		'model' => array('notempty'),
	);
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		'FormInput' => array(
			'className' => 'Forms.FormInput',
			'foreignKey' => 'form_fieldset_id',
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
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Form' => array(
			'className' => 'Forms.Form',
			'foreignKey' => 'form_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	
	function getForm($options) {	
		$plugin = (!empty($options['plugin']) ? $options['plugin'].'.' : null);
		$model = (!empty($options['model']) ? $options['model'] : null);
		$type = (!empty($options['type']) ? $options['type'] : null);
		$limiter = (!empty($options['limiter']) ? $options['limiter'] : null);
		
		# get the models this model belongs to
		App::Import('Model', $plugin.$model);
		$this->$model = new $model;
		$models = array_keys($this->$model->belongsTo);
		$models[] = $model;
		
		$formFieldsets = $this->find('all', array(
			'conditions' => array(
				array(
					'OR' => array(
						array('FormFieldset.form_id' => $limiter),
						array('FormFieldset.form_id' => null),
						),
					),
				'AND' => array(
					array(
						'FormFieldset.model' => $models,
						),
					),
				),
			'order' => 'FormFieldset.order',
			'contain' => array(
				'FormInput' => array(
					'order' => 'FormInput.order',
					),
				),
			));		
		return $formFieldsets;
	}
	
	
}
?>
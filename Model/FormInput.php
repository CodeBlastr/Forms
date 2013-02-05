<?php
App::uses('FormsAppModel', 'Forms.Model');
/**
 * FormInput Model
 *
 * Handles the return of information using the formInputs model. And form input settings.
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
 * @todo		  validation for customized forms --- hmm... how would we do that? (this todo probably doesn't belong here)
 */
class FormInput extends FormsAppModel {

	public $name = 'FormInput';
	
	public $validate = array(
	    'code' => array(
			'characterCheck' => array(
		    	'rule' => '/^[a-z0-9_]{2,50}$/i',  
		        'message' => 'Only lowercase letters, integers, underscores, and min 2 and 50 characters'
				),
			'firstCodeCheck' => array(
				'rule' => array('_initialCodeCheck', 'is_duplicate'),
				'message' => 'Are you sure you want to use this name?  This field already exists in the database.',
				'on' => 'create',
				),
	    	),
		'name' => array('notempty'),
		'input_type' => array('notempty'),
		'is_unique' => array('notempty'),
		'is_required' => array('notempty'),
		'is_quicksearch' => array('notempty'),
		'is_advancedsearch' => array('notempty'),
		'is_comparable' => array('notempty'),
		'is_layered' => array('notempty'),
		); 
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $belongsTo = array(
		'FormFieldset' => array(
			'className' => 'Forms.FormFieldset',
			'foreignKey' => 'form_fieldset_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Form' => array(
			'className' => 'Forms.Form',
			'foreignKey' => 'form_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
	
	
	public function beforeValidate($options = array()) {
		if (!empty($this->data['FormInput']['name']) && empty($this->data['FormInput']['code'])) {
			$this->data['FormInput']['code'] = Inflector::underscore(strtolower($this->data['FormInput']['name']));
		}
	}
	
/** 
 * Handle the numerous tasks when adding a new form input, including altering the database.
 * 
 * @param {data} 			Data to parse and save.
 */
	public function add($data) {
		if ($this->save($data)) {
			if (!empty($data['FormInput']['is_duplicate'])) {
				// validation checks to see if the field already exists, but that does not disqualify it from working, just need to throw a warning so the user can rename if they need to.
				return true;
			} else if (!empty($data['FormInput']['is_not_db_field'])) {
				// if the field doesn't need to be saved to the database we don't need to do anything but save the input
				return true;
			} else {
				if ($this->_addField($data['FormInput'])) {
					return true;
				} else {
					# rollback form input save
					if ($this->delete($this->id)) {
						return false;
					} else {
					    throw new NotFoundException('Uncaught error code : 102398102983120398');
//						echo 'Uncaught error code : 102398102983120398';
//						break;
					} 
				}
			}
		} else {
			$errors = '';
			foreach ($this->invalidFields() as $key => $error) :
				$errors .= $error[0];
			endforeach;
			throw new Exception(__('%s', $errors));
		}
	}
	
	
/**
 * Handles a full delete of the form input, and the field from the database.
 * 
 * @param {id}		The id of the form input being deleted
 * @return {bool} 	True if deleted, false if not.
 * @todo			Need to check if the column exists, because we don't need to alter table if it doesn't.
 * @todo			And change the order, we need to verify the table was alterned before we delete the input.
 * @todo			Ummm, seems like we need to make it so that we don't go deleting necessary fields (like system fields)
 */
	public function remove($id) {
		// setup the values for deleting the field itself too
		$formInput = $this->findbyId($id);
		$this->FormFieldset->id = $formInput['FormInput']['form_fieldset_id'];
		$isNotDbField = $formInput['FormInput']['is_not_db_field'];
		$formFieldset = $this->FormFieldset->field('name');
		$modelName = $this->FormFieldset->field('model');		
		$tableName = Inflector::underscore(Inflector::pluralize($modelName));
		if ($this->delete($id)) {
			if (!$isNotDbField) {
				if ($this->query('ALTER TABLE `'.$tableName.'` DROP `'.$formInput['FormInput']['code'].'`')) {
					return true;
				} else {
					throw new NotFoundException('Uncaught error code : 5987109823409876901823');
//					echo 'Uncaught error code : 5987109823409876901823';
//					break;
				}
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	
	

/**
 * Finds all the formInputs for the specified model and type.
 *
 * @param {model}		The model the fieldset belongsTo.
 * @param {typeId}		A limiter or predefined field which can be used to change the formInputs that in the end get displayed. Refer to the enumerations table for id numbers.
 * @param {options] 	Additional directions for what formInputs to find.
 * @return 				The optionally limited formInputs for the specified model. 
 */
	public function getFormInputs($model, $typeId = null, $options = null) {		
		$formFieldsets = $this->FormFieldset->getFormFieldsets($model, $typeId);
		foreach ($formFieldsets as $formFieldset) {
			$formFieldsetIds[] = $formFieldset['FormFieldset']['id'];
		}
		$formInputs = $this->find('all', array(
			'conditions' => array(
				'FormInput.form_fieldset_id' => $formFieldsetIds,
				$options['conditions'],
				),
			'order' => 'FormInput.order',
			));
		return $formInputs;
	}
	
/**
 * Checks to see if a field (or column) already exists in the database
 *
 * @param {formInput}	The formInput to check for.  An array with at least values for 'form_fieldset_id' and 'code'.
 * @return {BOOL}		1 it does, 0 it does not
 * @todo				Add some type of error check for whether those two required data points exist.
 * @todo				Generalize and move to app model if its needed in any other model.
 */
	protected function _checkFieldExistence($formInput) {
		$tableName = $this->_getFieldInputTable($formInput['form_id'], $formInput['model_override']);
		$fieldName = $formInput['code'];
		$fieldSearch = $this->query('SHOW columns FROM '.$tableName.' LIKE "'.$fieldName.'"');
		
		// this checks to see if a field name now exists in the table that matches input info
		if (!empty($fieldSearch)) {
			return true;
		} else {
			return false;
		}
	}


/**
 * Gets the table name to use in checkFieldExistence using the inputs fieldset , or the override
 *
 * @param {fieldsetId}	The fieldset to look up the default model in.
 * @param {override}	An optional override model name.
 * @return {string}		A properly formatted table name.
 */
	protected function _getFieldInputTable($fieldsetId, $override = null) {
		// get the default model name from the fieldset
		$this->Form->id = $fieldsetId;
		$modelName = $this->Form->field('model');
		
		// check to see if the fieldset model was over ridden by the input 
		if (!empty($override)) {
			$modelName = $override;
		}			
		
		// return a model name formatted as a table name
		return Inflector::underscore(Inflector::pluralize($modelName));
	}
	
	
/**
 * Checks to see the field name already exists in this table.  Because if it does we shouldn't add it to the table, but we should see if we need to duplicate for another fieldset, so that we can reuse the same fields across multiple record types.
 *
 * @param {code}		The formInput to check for.  An array with at least values for 'form_fieldset_id' and 'code'.
 * @return {BOOL}		unless is_duplicate is set it checks to see if the field exists, if is_duplicate is set, then save.
 * @todo				Add some type of error check for whether those two required data points exist.
 * @todo				Generalize and move to app model if its needed in any other model.
 */
	protected function _initialCodeCheck($code){
		if (!empty($this->data['FormInput']['is_duplicate'])) {
			return true;
		} else {
			// this checks to see if a field name now exists in the table that matches input info, 
			// initial validation should fail if it does, so that an error can be returned.
			if($this->_checkFieldExistence($this->data['FormInput'])) {
				return false;
			} else {
				return true;
			}
		}	
	}
	
	
/** 
 * Checks to see if the table exists for the given model.
 *
 * @param {model}		The model (database table) to check for existence.
 * @return {string}		Returns the table name if it exists, otherwise false.
 */
	protected function _checkTableExistence($model) {
		$tableName = Inflector::underscore(Inflector::pluralize($model));
		// this checks to see if a table with this name exists.
		$tableSearch = $this->query('SHOW columns FROM '.$tableName);
		if (!empty($tableSearch)) {
			return $tableName;
		} else {
			return false;
		}
	}
	
	
/**
 * Builds the query for updating the database table with new formInputs. (FormFieldset = table, FormInput = field)
 *
 * @param {thisData}		Info from the save operation.
 * @param {fmInputId}		The id of the formInput being saved
 * @todo					Simplify and break up the multiple actions going on within this function.
 */
	protected function _addField($data) {
		// get the field set info
		$model = $this->_getFieldInputTable($data['form_id'], $data['model_override']);
		if($tableName = $this->_checkTableExistence($model)) {
			// it exists so we'll alter the existing table
			return $this->_alterTable($tableName, $data);
		} else {
			// the table does not exist so we need to create it, and then add the field, so use a random name
			if ($tableName = $this->_createTable($model)) {
				return $this->_alterTable($tableName, $data);
			}
		}		
	}

/**
 * Finalizes the query details from build query
 *
 * @param {tableName}		The table to modify
 * @param {fieldName}		The field to update or add
 * @param {fieldQuery}		The sub query from buildQuery
 * @return {string}			Returns the table name created, or false.				
 */
	protected function _createTable($model) {
		$tableName = Inflector::tableize($model);
		// add a new table
		$query = 'CREATE TABLE IF NOT EXISTS `'.$tableName.'` ( `id` INT( 11 ) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
		if ($this->query($query)) {
			return $tableName;
		} else {
			return false;
		}
	}
	

/**
 * Create and execute an alter table query
 *
 * @param {tableName}		The table to modify
 * @param {fieldName}		The field to update or add
 * @param {fieldQuery}		The sub query from buildQuery
 * @return {bool}			True on success of new table creation		
 */
	protected function _alterTable($tableName, $data) {
		// set the field settings
		$fieldType = $this->_getFieldType($data['input_type']);
		$nullStatus = $this->_getNullStatus($data['is_required']);
		$default = $this->_getDefault($data['default_value']);
		$unique = $this->_getUniqueKey($data['code'], $data['is_unique']);
		
		// add to the existing table
		$query = ' ALTER TABLE `'.$tableName.'`';
		// add the field
		$query .= ' ADD `'.$data['code'].'` ' . $fieldType.$nullStatus.$default.$unique;
		
		if ($this->query($query)) {
			return true;
		} else {
			return false;
		}
	}
	
/**
 * Map the field type to a mysql database field type
 *
 * @param {type}		The type of input field
 * @return {string}		A mysql database field type snippet.
 */
	protected function _getFieldType($type) {
		/*
		$this->data['input_type'] values
		text = 'VARCHAR'
		textarea = 'TEXT'
		richtext = 'TEXT'
		date = 'DATE'
		time = 'TIME'
		datetime = 'DATETIME'
		select = 'VARCHAR'
		multi-select = 'VARCHAR'
		checkbox = 'VARCHAR'
		radio = 'VARCHAR'
		hidden = 'VARCHAR'
		password = 'VARCHAR'
		file = 'VARCHAR'
		*/
		if ($type == 'text') {
			return 'VARCHAR(255) '; 
		} else if ($type == 'textarea') {
			return 'TEXT '; 
		} else if ($type == 'richtext') {
			return 'TEXT '; 
		} else if ($type == 'date') {
			return 'DATE '; 
		} else if ($type == 'time') {
			return 'TINYINT(1) '; 
		} else if ($type == 'datetime') {
			return 'DATETIME '; 
		} else if ($type == 'select') {
			return 'VARCHAR(255) '; 
		} else if ($type == 'multi-select') {
			return 'VARCHAR(255) '; 
		} else if ($type == 'checkbox') {
			return 'VARCHAR(255) ';
		} else if ($type == 'radio') {
			return 'VARCHAR(255) ';
		} else if ($type == 'hidden') {
			return 'VARCHAR(255) '; 
		} else if ($type == 'password') {
			return 'VARCHAR(255) '; 
		} else if ($type == 'file') {
			return 'VARCHAR(255) '; 
		} else {
			// break because its invalid
			return false;
		}
 	}
	
	
/**
 * Map the field null setting to a mysql query string
 *
 * @param {isRequired}	0 or 1
 * @return {string}		A mysql null snippet
 */
	protected function _getNullStatus($isRequired = null) {
		// set up the NULL value for the database field based on if its required or not
		if ($isRequired == 1) {
			return ' NOT NULL ';
		} else {
			return ' NULL ';
		}
	}
		
	
	
/**
 * Map the default value setting to a mysql default query string
 *
 * @param {defaultValue}The default value for this field
 * @return {string}		A mysql default query snippet
 */
	protected function _getDefault($defaultValue = null) {
		// input the DEFAULT value if it exists
		if ($defaultValue == null) {
			return ' ';
		} else {
			return ' DEFAULT \''.$defaultValue.'\' ';
		}
	}
	
	
	
/**
 * Map the the unique setting to a mysql query string
 *
 * @param {fieldName}	The field that should be unique
 * @param {isUnique}	Whether the field is unique or not
 * @return {string}		A mysql unique key snippet
 */
	protected function _getUniqueKey($fieldName, $isUnique = null) {
		// set up the UNIQUE index if it is a unique field
		if ($isUnique == 1) {
			return ' , ADD UNIQUE (`'.$fieldName.'`) ';
		} else {
			return ' ';
		}
	}	
	

/** 
 * The input types that a form input could be. 
 *
 * @return {array}		An array of form input types.
 */
	public function inputTypes() {
		return array(
				'text' => 'text',
				'textarea' => 'textarea',
				'date' => 'date',
				'time' => 'time',
				'datetime' => 'datetime',
				'select' => 'select',
				'checkbox' => 'checkbox',
				'radio' => 'radio',
				'hidden' => 'hidden',
				'password' => 'password',
				'file' => 'file',
				);
	}
	
/**
 * Available default values
 */
	public function systemDefaultValues () {
		return array(
			'custom' => 'Custom',
			'current user' => 'Current User',
			);
	}
			
	
}

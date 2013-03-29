<?php
App::uses('FormsAppModel', 'Forms.Model');
/**
 * FormKey Model
 *
 * Handles the usage of form keys
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
class FormKey extends FormsAppModel {

	public $name = 'FormKey';

/**
 * Create keys as needed
 *
 * @return string
 */
	public function createKey() {
		if ($this->save()) {
			if ($this->expireKeys()) {
				return $this->id;
			} else {
				$this->delete($this->id); // rollback the created key
			}
		} else {
			throw new Exception(__('Creating a key failed'));
		}
	}

/**
 * Expire any keys older than an hour
 *
 * @return bool
 */
 	public function expireKeys() {
 		if ($this->deleteAll(array('FormKey.created <' => date('Y-m-d h:i:s', strtotime('1 hour ago'))))) {
			return true;
		} else {
			throw new Exception(__('Expiring keys failed.'));
		}
 	}

/**
 * Test a key to see if it exists and expire keys while we're here
 */
	public function testKey($data) {
		if (!empty($data['FormKey']['id'])) {
			$result = $this->find('first', array(
				'conditions' => array(
					'FormKey.id' => $data['FormKey']['id'],
					)
				));
			if (!empty($result)) {
				$this->delete($result['FormKey']['id']); // delete the checked key
				$this->expireKeys();
				return true;
			} else {
				$this->expireKeys();
				throw new Exception(__('Double submission, please try refreshing the form before you submit again.'));
			}
		} else {
			$this->expireKeys();
			throw new Exception(__('Key not provided.'));
		}
	}

}
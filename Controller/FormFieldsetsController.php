<?php
/**
 * Form Fieldsets Controller
 *
 * For use in grouping field inputs (database fields) for use. 
 * Form fieldsets can literally mean a database table.  The database table where formInputs
 * will be saved.  For example : if you had a "ticket" fieldset, you may set it up
 * so that it formInputs (database fields) added to this fieldset, are added to the database 
 * table named, "tickets".  
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
 * @subpackage    zuha.app.plugin.forms.controllers
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class FormFieldsetsController extends FormsAppController {

	public $name = 'FormFieldsets';
	
	public $uses = 'Forms.FormFieldset';
	

	public function index() {
		$this->FormFieldset->recursive = 0;
		$this->set('formFieldsets', $this->paginate());
	}

	public function add($formId = null) {
		if (!empty($this->request->data)) {
			if ($this->FormFieldset->save($this->request->data)) {
				$this->Session->setFlash(__('The Fieldset has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Fieldset could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->FormFieldset->read(null, $id);
			$forms = $this->FormFieldset->Form->find('list');
			$this->set(compact('forms'));
		}
		$this->set(compact('formId'));
	}

	public function edit($id = null) {
		if (!empty($this->request->data)) {
			if ($this->FormFieldset->save($this->request->data)) {
				$this->Session->setFlash(__('The Fieldset has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Fieldset could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->FormFieldset->read(null, $id);
			$forms = $this->FormFieldset->Form->find('list');
			$this->set(compact('forms'));
		}
	}

	public function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for FormFieldset', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->FormFieldset->delete($id)) {
			$this->Session->setFlash(__('FormFieldset deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>
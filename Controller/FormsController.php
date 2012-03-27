<?php
/**
 * Form Controller
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
 * @subpackage    zuha.app.plugins.forms.controllers
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class FormsController extends FormsAppController {

	public $name = 'Forms';
	public $uses = 'Forms.Form';
	public $allowedActions = array('display', 'process');

	public function index() {
		$this->Form->recursive = 0;
		$this->set('forms', $this->paginate());
	}

	public function add() {
		if (!empty($this->request->data)) {
			if ($this->Form->add($this->request->data)) {
				$this->Session->setFlash(__('The Form has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Form could not be saved. Please, try again.', true));
			}
		}
		
		$this->set('methods', $this->Form->methods()); 
	}

/**
 * View method
 *
 */
	public function view($id = null) {
		$this->Form->id = $id;
		if (!$this->Form->exists()) {
			throw new NotFoundException(__('Invalid form.'));
		}
		$this->set(compact('id'));
	}

/**
 * Edit method
 *
 */
	public function edit($id = null, $formInputId = null) {
		$this->Form->id = $id;
		if (!$this->Form->exists()) {
			throw new NotFoundException(__('Invalid form.'));
		}
		
			
		if (!empty($this->request->data['FormInput'])) {
			# create the formInput
			try {
				$this->Form->FormInput->add($this->request->data);
				$this->Session->setFlash(__('Input Successfully Added!'));
				$this->redirect(array('controller' => 'forms', 'action' => 'edit', $this->request->data['FormInput']['form_id']));
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
				$this->set('duplicate', true);
			}
		} 
		
		if (!empty($this->request->data['Form'])) {
			try {
				$this->Form->add($this->request->data);
				$this->Session->setFlash(__('The Form has been saved', true));
				$this->redirect(array('action'=>'index'));
			} catch (Exception $e) {
				$this->Session->setFlash(__('The Form could not be saved. Please, try again.'));
			}
		}
		$formInput = !empty($formInputId) ? $this->Form->FormInput->read(null, $formInputId) : array();
		$this->request->data = array_merge($this->Form->read(null, $id), $formInput, $this->request->data);
		$this->set('methods', $this->Form->methods()); 
		$this->set('inputTypes', $this->Form->FormInput->inputTypes());
		$this->set('systemDefaultValues', $this->Form->FormInput->systemDefaultValues());
	}

/**
 * Used to display a form using requestAction in the default layout.
 *
 * @param {id}			The form id to call.
 * @return {formInputs}	Form elements within the requested fieldsets.
 */
	public function display($id, $type = 'add') {
		$this->Form->id = $id;
		if (!$this->Form->exists()) {
			throw new NotFoundException(__('Invalid form.'));
		}
		
		$formGroup = $this->Form->display($id, $type);
		if (!empty($formGroup) && isset($this->request->params['requested'])) {
			$formGroup = array_merge($formGroup, $this->_specialData()); 
        	return $formGroup;
        } else {
			return false;
		}
	}
	
	protected function _specialData() {
		return array('user_id' => $this->Session->read('Auth.User.id'));
	}
	
	
/**
 * Takes a custom form and processes it using the model->action from the form settings.
 * 
 * @todo		This could probably be moved to the Form model.
 * @todo		The duplication of the failures is probably a logic error that can be fixed or at least put into a separate function.
 */
	public function process() {
		$this->Session->delete('errors');
		if (!empty($this->request->data)) {			
			$plugin = $this->request->data['Form']['plugin'];
			$modelName = $this->request->data['Form']['model'];
			$action = $this->request->data['Form']['action'];
			$init = !empty($plugin) ? $plugin . '.' . $modelName : $modelName;
			$Model = ClassRegistry::init($init);
			# validates the data before trying to run the action
			# previously this was just $this->request->data but was changed to $this->request->data[$modelName] because 
			# I could not figure out why the category data was causing a validation failure 4/22/2011 RK
			if ($Model->saveAll($this->request->data[$modelName], array('validate' => 'only'))) {
				try {
					$result = $Model->$action($this->request->data);
					if ($result && $this->Form->notify($this->request->data)) {
						if (!empty($this->request->data['Form']['success_message'])) {
							$this->Session->setFlash($this->request->data['Form']['success_message'], true);
						} else {
							$this->Session->setFlash(__('Success!'));
						}
						if (!empty($this->request->data['Form']['success_url'])) {
							$this->redirect($this->request->data['Form']['success_url']);
						} else {
							$this->redirect($this->referer());
						}			
					} else {
						# 3rd point of failure is likely a database error
						if (!empty($this->request->data['Form']['fail_message'])) {
							$this->Session->setFlash($this->request->data['Form']['fail_message'], true);
						} else {
							$this->Session->setFlash(__('Please Try Again.'));
						}
						if (!empty($this->request->data['Form']['fail_url'])) {
							# this makes the submitted form data accessible by sessions
							$this->Session->write($Model->data);	
							$this->redirect($this->request->data['Form']['fail_url']);
						} else {
							# this makes the submitted form data accessible by sessions
							$this->Session->write($Model->data);
							$this->Session->write($Model->validationErrors);
							$this->redirect($this->referer());
						}
					}
				} catch (Exception $e) {
					# 2nd point of failure would be detected in the model exceptions
					# this makes the submitted form data accessible by sessions
					$this->Session->write($Model->data);	
					# if registration verification is required the model will return this code
					if ($e->getCode() > 500000) {
						$this->Session->setFlash($e->getMessage() . $this->request->data['Form']['success_message']);
						$this->redirect($this->request->data['Form']['success_url']);
					} else {
						if (!empty($this->request->data['Form']['fail_url'])) {
							# this makes the submitted form data accessible by sessions
							$this->Session->setFlash($e->getMessage());	
							$this->redirect($this->request->data['Form']['fail_url']);
						} else {
							# this makes the submitted form data accessible by sessions
							$this->Session->setFlash($e->getMessage() . $Model->validationErrors);
							$this->redirect($this->referer());
						}
					}	
				}	
			} else {
				# 1st point of failure, is validation
				if (!empty($this->request->data['Form']['fail_message'])) {
					$this->Session->setFlash($this->request->data['Form']['fail_message'], true);
				} else {
					$this->Session->setFlash(__('Submission has invalid data.', true));
				}
				if (!empty($this->request->data['Form']['fail_url'])) {
					# this makes the submitted form data accessible by sessions
					$this->Session->write($Model->data);	
					$this->redirect($this->request->data['Form']['fail_url']);
				} else {
					# this makes the submitted form data accessible by sessions
					$this->Session->write($Model->data);	
					$this->Session->write(array('errors' => array($modelName => $Model->validationErrors)));
					$this->redirect($this->referer());
				}
			}
		} else {
			echo 'uncaught exception : 1238740918723409723489';
			break;
		}
	}	

}
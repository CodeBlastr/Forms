<?php
App::uses('FormsAppController', 'Forms.Controller');
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
 * Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.forms.controllers
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
class FormsController extends FormsAppController {

	public $name = 'Forms';
	public $uses = 'Forms.Form';
	public $allowedActions = array('display', 'process', 'secure');

	public function index() {
		$this->Form->recursive = 0;
		$this->set('forms', $this->paginate());
	}

	public function add($type = 'default', $foreignModel = null, $foreignKey = null) {
		if (!empty($this->request->data)) {
			try {
				$this->Form->add($this->request->data);
				$this->Session->setFlash(__('The Form has been saved', true));
				if ( $type === 'formanswer' ) {
					$this->redirect(array('controller'=>'formInputs', 'action'=>'create', $this->Form->id));
				} else {
					$this->redirect(array('action'=>'index'));
				}
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
			}
		}

		$this->set('methods', $this->Form->methods());
		$this->set('copies', $this->Form->copyTypes());
		
		$this->set('foreignModel', $foreignModel);
		$this->set('foreignKey', $foreignKey);
		
		$this->view = 'add_' . $type;
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
			// create the formInput
			try {
				$this->Form->FormInput->add($this->request->data);
				$this->Session->setFlash(__('Input Successfully Added!'));
				$this->redirect(array('controller' => 'forms', 'action' => 'edit', $this->request->data['FormInput']['form_id']));
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
				$this->set('duplicate', true); // db editing disabled
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
 * Used to create the convenience field "url".
 *
 * @param {data} 		The $this->data array.
 */
	public function copy($id = null) {
		$this->Form->id = $id;
		if (!$this->Form->exists()) {
			throw new NotFoundException(__('Invalid form.'));
		}
		try {
			$this->Form->copy($id);
			$this->redirect(array('action' => 'index'));
		} catch (Exception $e) {
			debug($e->getMessage);
			break;
		}
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
 * Create a key for remote forms to use (and delete any keys older than an hour)
 * must be called like this... http://www.example.com/forms/forms/secure.json
 */
 	public function secure() {
 		try {
 			App::uses('FormKey', 'Forms.Model');
			$FormKey = new FormKey();
 			$this->set('key', $FormKey->createKey());
 		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
		}
 	}


/**
 * Takes a custom form and processes it using the model->action from the form settings.
 *
 * @todo		This could probably be moved to the Form model.
 * @todo		The duplication of the failures is probably a logic error that can be fixed or at least put into a separate function.
 */
	public function process() {
		$this->Session->delete('errors');
		if (!empty($this->request->data['Form'])) {
			$this->_checkSecurity();
			$plugin = $this->request->data['Form']['plugin'];
			$this->modelName = $this->request->data['Form']['model'];
			$action = $this->request->data['Form']['action'];
			$init = !empty($plugin) ? $plugin . '.' . $this->modelName : $this->modelName;
			$this->Model = ClassRegistry::init($init);
			// validates the data before trying to run the action
			if ($this->Model->saveAll($this->request->data[$this->modelName], array('validate' => 'only'))) {
				try {
					$result = $this->Model->$action($this->request->data);
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
						// 3rd point of failure is likely a database error
						if (!empty($this->request->data['Form']['fail_message'])) {
							$this->Session->setFlash($this->request->data['Form']['fail_message'], true);
						} else {
							$this->Session->setFlash(__('Please Try Again.'));
						}
						if (!empty($this->request->data['Form']['fail_url'])) {
							// this makes the submitted form data accessible by sessions
							$this->Session->write($this->Model->data);
							$this->redirect($this->request->data['Form']['fail_url']);
						} else {
							// this makes the submitted form data accessible by sessions
							$this->Session->write($this->Model->data);
							$this->Session->write($this->Model->validationErrors);
							$this->redirect($this->referer());
						}
					}
				} catch (Exception $e) {
					// 2nd point of failure would be detected in the model exceptions
					// this makes the submitted form data accessible by sessions
					$this->Session->write($this->Model->data);
					// if registration verification is required the model will return this code
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
							$this->Session->setFlash($e->getMessage() . $this->Model->validationErrors);
							$this->redirect($this->referer());
						}
					}
				}
			} else {
				$this->_handleValidationResponse();
			}
		} else {
			echo 'uncaught exception : 1238740918723409723489';
			break;
		}
	}

/**
 * check stats to help prevent spam
 * 
 * For in domain tests we simply set a session and make sure that the session is a certain age before allowing a form submission
 * 
 * For cross domain test you should have a valid key submitted with your form.
 * 
 * Here is an example of code you could use to get a key
 * <?php $json = json_decode(file_get_contents('http://example.com/forms/forms/secure.json')); ?> 
 * <script type="text/javascript">
 *	jQuery(document).ready(function() {
 *		jQuery(".warning").hide();
 *		jQuery("#addForm").append("<input type=\"hidden\" name=\"data[FormKey][id]\" value=\"<?php echo $json->key; ?>\" />");
 *	})
 * </script>
 */
 	protected function _checkSecurity() {
 		// defined key test
        if (defined('__FORMS_KEYS') && !empty($this->request->data['FormKey']['key'])) {
            $success = false;
            $keys = unserialize(__FORMS_KEYS);
            if (!empty($keys['key'][0])) {
                foreach ($keys['key'] as $key) {
                    if ($this->request->data['FormKey']['key'] == $key) {
                        $success = true;
                        break;
                    }
                }
                if ($success === true) {
                    return $success;
                } else {
        			echo 'uncaught exception : 727273487128347123';
    				break; 	
                }
            }
        }
		// cross domain test
 		if (!empty($this->request->data['FormKey']['id'])) {
 			App::uses('FormKey', 'Forms.Model');
			$FormKey = new FormKey();
 			if ($success = $FormKey->testKey($this->request->data)) {
 				return $success;
 			} else {
				echo 'uncaught exception : 8638678967189768976123894';
				break; 				
 			}
 		}	
		
		// in domain test
 		$statsEntry = $this->Session->read('Stats.entry');
 		$time = time() - base64_decode($statsEntry);
		if ($time > 10 && $time < 100001) {
	    	return true;
		} else {
			echo 'uncaught exception : 329857769196719876928723';
			break;
		}
        echo 'uncaught exception : 2394982379428374';
        break;
 	}


/**
 * Handle responding to invalid form data
 *
 * @param null
 * @access protected
 * @return null
 */
	protected function _handleValidationResponse() {
		$message = ' Validation Error(s) : ';
		foreach($this->Model->validationErrors as $field => $error) {
			$message .= $error[0];
		}
		// 1st point of failure, is validation
		if (!empty($this->request->data['Form']['fail_message'])) {
			$this->Session->setFlash($this->request->data['Form']['fail_message'] . $message, true);
		} else {
			$this->Session->setFlash(__($message, true));
		}
		if (!empty($this->request->data['Form']['fail_url'])) {
			// this makes the submitted form data accessible by sessions
			$this->Session->write($this->Model->data);
			$this->redirect($this->request->data['Form']['fail_url']);
		} else {
			// this makes the submitted form data accessible by sessions
			$this->Session->write($this->Model->data);
			$this->Session->write(array('errors' => array($this->modelName => $this->Model->validationErrors)));
			$this->redirect($this->referer());
		}
	}

}
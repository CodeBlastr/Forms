<?php
App::uses('FormsController', 'Forms.Controller');

/**
 * Form Model Case
 *
 * @package forms
 * @subpackage plugin.forms.tests.cases.controllers
 */
class FormModel extends CakeTestModel {

/**
 * useTable
 *
 * @var string
 */
	public $useTable = 'forms';
}


/**
 * TestFormsController
 *
 * @package forms
 * @subpackage plugins.forms.tests.cases.controllers
 */
class TestFormsController extends FormsController {
/**
 * Fixtures
 *
 * @var array
	public $fixtures = array(
		'core.aco',
		'core.aro',
		'core.aros_aco',
		);
 */
 
/**
 * Override controller method for testing
 * @todo find way to not rewrite code here
 *
 */
	public function redirect($url, $status = null, $exit = true) {
		if (!empty($this->request->params['isAjax'])) {
			return $this->setAction('short_list', $this->Favorite->model);
		} else if (isset($this->viewVars['status']) && isset($this->viewVars['message'])) {
			$this->Session->setFlash($this->viewVars['message'], 'default', array(), $this->viewVars['status']);
			$this->redirectUrl = $url;
		}
	}

/**
 * Override controller method for testing
 *
 * @param string $action 
 * @param string $layout 
 * @param string $file 
 * @return void
 */
	public function _handleValidationResponse() {
		$this->modelName = 'Form';
		$this->Model = new FormModel;
		$this->Model->validationErrors['username'] = array('Test username already taken.');
		parent::_handleValidationResponse();
	} 
}

/**
 * FavoritesControllerTestCase
 *
 * @package favorites
 * @subpackage favorites.tests.cases.controllers
 */
class FormsControllerTestCase extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 
	public $fixtures = array(
		'plugin.favorites.favorite',
		'core.article',
		'core.user');
*/
/**
 * startTest
 *
 * @return void
 */
	public function startTest() {
		$this->Forms = new TestFormsController(new CakeRequest());
		$this->Forms->constructClasses();
	}

/**
 * endTest
 *
 * @return void
 */
	public function endTest() {
		unset($this->Forms);
		ClassRegistry::flush();
	}
	

/**
 * testInvalidFormSubmission
 *
 * @return void
 */
	public function testInvalidFormSubmission() {
		$this->Forms->_handleValidationResponse();
		$this->assertEqual('Test username already taken.', $this->Forms->Session->read('errors.Form.username.0'));
	}
	
}

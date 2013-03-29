<?php
App::uses('FormAnswer', 'Model');

/**
 * FormAnswer Test Case
 *
 */
class FormAnswerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.form_answer', 'app.form_input', 'app.user');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FormAnswer = ClassRegistry::init('FormAnswer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FormAnswer);

		parent::tearDown();
	}

}

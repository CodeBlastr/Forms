<?php
App::uses('FormKey', 'Forms.Model');

/**
 * FormKey Test Case
 *
 */
class FormKeyTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.Forms.form_key',
		);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FormKey = ClassRegistry::init('Forms.FormKey');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FormKey);

		parent::tearDown();
	}

/**
 * testCreateKey method
 *
 * @return void
 */
	public function testCreateKey() {
		$result = $this->FormKey->createKey();
		$this->assertEqual($result, $this->FormKey->id); // returns the id, so should match the id
	}

/**
 * testExpireKeys method
 *
 * @return void
 */
	public function testExpireKeys() {
		$result = $this->FormKey->expireKeys();
		$this->assertTrue($result); // true should be returned
		
		$result = $this->FormKey->find('all', array(
			'conditions' => array(
				'FormKey.created >' => date('Y-m-d h:i:s', strtotime('1 hour ago')),
				)
			));
			
		$this->assertEmpty($result); // nothing should exist which is older than an hour
	}
	

/**
 * testTestKey method
 *
 * @return void
 */
	public function testTestKey() {
		$key = $this->FormKey->createKey(); // get a key
		$result = $this->FormKey->testKey(array('FormKey' => array('id' => $key))); // test a key
		$this->assertTrue($result); // should return true
		
		$result = $this->FormKey->find('first', array('conditions' => array('FormKey.id' => $key)));
		$this->assertTrue(empty($result)); // should not be there after being tested
	}
}

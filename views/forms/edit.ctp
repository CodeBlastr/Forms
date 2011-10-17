<?php
/**
 * Form Admin Edit View
 *
 * The view for adding forms.
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2010, Zuha Foundation Inc. (http://zuhafoundation.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha™ Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.forms.views
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
?>

<div class="forms add-form">
<h1><?php __('Edit a Form');?></h1>
<?php echo $form->create('Form');?>
	<fieldset>
 		<legend><?php __('Add Form');?></legend>
	<?php
		echo $form->input('Form.id');
		echo $form->input('Form.name'); 
	?>
    </fieldset>
    <fieldset>
    	<legend><?php __('Settings');?></legend>
     <?php
		echo $form->input('Form.method');
		echo $form->input('Form.action', array('after' => ' (ex: add, edit, view, save, remove)'));
		echo $form->input('Form.plugin'); 
		echo $form->input('Form.model', array('after' => ' (camel case model name)' )); 
		echo $form->input('Form.success_message'); 
		echo $form->input('Form.success_url'); 
		echo $form->input('Form.fail_message'); 
		echo $form->input('Form.fail_url'); 
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<?php 
// set the contextual menu items
$menu->setValue(array(
	array(
		'heading' => 'Forms',
		'items' => array(
			$this->Html->link(__('List Forms', true), array('action' => 'index')),
			)
		),
	array(
		'heading' => 'Form Fieldsets',
		'items' => array(
			$this->Html->link(__('New Form Fieldset', true), array('controller' => 'form_fieldsets', 'action' => 'edit')),
			$this->Html->link(__('Show Fieldsets', true), array('controller' => 'form_fieldsets', 'action' => 'index')),
			$this->Html->link(__('Show System Fieldsets', true), array('controller' => 'form_fieldsets', 'action' => 'index', 'system' => 1)),
			)
		),
	array(
		'heading' => 'Form Inputs',
		'items' => array(
			$this->Html->link(__('New FormInputs', true), array('controller' => 'form_inputs', 'action' => 'add')),
			$this->Html->link(__('List FormInputs', true), array('controller' => 'form_inputs', 'action' => 'index')),
			)
		),
	)
);
?>

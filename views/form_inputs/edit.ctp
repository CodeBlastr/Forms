<?php
/**
 * Form Inputs Admin Edit View
 *
 * The view for editing form inputs.
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

<div class="formInputs form">
<h1><?php __('Manage Form Input');?></h1>
<?php echo $form->create('Form Input');?>
	<fieldset>
 		<legend><?php __('Edit Form Input');?></legend>
	<?php
		echo $form->input('FormInput.id');
		echo $form->input('FormInput.form_fieldset_id'); 
		echo $form->input('FormInput.code'); 
		echo $form->input('FormInput.name'); 
		echo $form->input('FormInput.model_override'); 
		echo $form->input('FormInput.input_type'); 
		echo $form->input('FormInput.system_default_value', array('empty' => true, 'options' => array('current user' => 'current user'))); 
		echo $form->input('FormInput.default_value'); 
		echo $form->input('FormInput.option_values', array('after' => ' One option per line')); 
		echo $form->input('FormInput.option_names', array('after' => ' must have the same number of lines')); 
		echo $form->input('FormInput.before'); 
		echo $form->input('FormInput.separator'); 
		echo $form->input('FormInput.after'); 
		echo $form->input('FormInput.multiple', array('after' => ' valid values are 1 or checkbox')); 
		echo $form->input('FormInput.max_length'); 
		echo $form->input('FormInput.legend', array('after' => ' text above radio input types')); 
		echo $form->input('FormInput.rows'); 
		echo $form->input('FormInput.columns'); 
		echo $form->input('FormInput.empty_text', array('after' => ' text for null value in select drop downs')); 
		echo $form->input('FormInput.time_format', array('after' => ' valid values are 12, 24, and none')); 
		echo $form->input('FormInput.date_format', array('after' => ' valid values are DMY, MDY, YMD and NONE')); 
		echo $form->input('FormInput.min_year', array('after' => ' valid value is a 4 digit year')); 
		echo $form->input('FormInput.max_year', array('after' => ' valid value is a 4 digit year')); 
		echo $form->input('FormInput.minute_interval', array('after' => ' time between minutes in minute drop down, ie. 15, will show 00 : 15 : 30 : 45')); 
		echo $form->input('FormInput.div_id', array('type' => 'text', 'after' => ' a custom id for the div around this input')); 
		echo $form->input('FormInput.div_class', array('after' => ' a custom class for the div around this input')); 
		echo $form->input('FormInput.error_message', array('after' => ' a custom error message for this input')); 
		echo $form->input('FormInput.is_unique'); 
		echo $form->input('FormInput.is_system'); 
		echo $form->input('FormInput.is_required'); 
		echo $form->input('FormInput.is_quicksearch'); 
		echo $form->input('FormInput.is_advancedsearch'); 
		echo $form->input('FormInput.is_comparable'); 
		echo $form->input('FormInput.is_layered'); 
		echo $form->input('FormInput.layer_order'); 
		echo $form->input('FormInput.is_not_db_field'); 
		echo $form->input('FormInput.is_visible'); 
		echo $form->input('FormInput.is_addable'); 
		echo $form->input('FormInput.is_editable'); 
		echo $form->input('FormInput.order'); 
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<?php 
// set the contextual menu items
$menu->setValue(array(
	array(
		'heading' => 'FormInputs',
		'items' => array(
			$this->Html->link(__('Delete', true), array('action' => 'delete', $form->value('FormInput.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('FormInput.id'))),
			$this->Html->link(__('List FormInputs', true), array('action' => 'index')),
			)
		),
	array(
		'heading' => 'Form Fieldsets',
		'items' => array(
			$this->Html->link(__('New FormFieldset', true), array('controller' => 'form_fieldsets', 'action' => 'edit')),
			$this->Html->link(__('List FormFieldsets', true), array('controller' => 'form_fieldsets', 'action' => 'index')),
			)
		),
	array(
		'heading' => 'Forms',
		'items' => array(
			$this->Html->link(__('New Form', true), array('controller' => 'forms', 'action' => 'add')),
			$this->Html->link(__('List Forms', true), array('controller' => 'forms', 'action' => 'index')),
			)
		),
	)
);
?>

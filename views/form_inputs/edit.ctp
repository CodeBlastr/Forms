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
<h1><?php echo __('Manage Form Input');?></h1>
<?php echo $this->Form->create('Form Input');?>
	<fieldset>
 		<legend><?php echo __('Edit Form Input');?></legend>
	<?php
		echo $this->Form->input('FormInput.id');
		echo $this->Form->input('FormInput.form_fieldset_id'); 
		echo $this->Form->input('FormInput.code'); 
		echo $this->Form->input('FormInput.name'); 
		echo $this->Form->input('FormInput.model_override'); 
		echo $this->Form->input('FormInput.input_type'); 
		echo $this->Form->input('FormInput.system_default_value', array('empty' => true, 'options' => array('current user' => 'current user'))); 
		echo $this->Form->input('FormInput.default_value'); 
		echo $this->Form->input('FormInput.option_values', array('after' => ' One option per line')); 
		echo $this->Form->input('FormInput.option_names', array('after' => ' must have the same number of lines')); 
		echo $this->Form->input('FormInput.before'); 
		echo $this->Form->input('FormInput.separator'); 
		echo $this->Form->input('FormInput.after'); 
		echo $this->Form->input('FormInput.multiple', array('after' => ' valid values are 1 or checkbox')); 
		echo $this->Form->input('FormInput.max_length'); 
		echo $this->Form->input('FormInput.legend', array('after' => ' text above radio input types')); 
		echo $this->Form->input('FormInput.rows'); 
		echo $this->Form->input('FormInput.columns'); 
		echo $this->Form->input('FormInput.empty_text', array('after' => ' text for null value in select drop downs')); 
		echo $this->Form->input('FormInput.time_format', array('after' => ' valid values are 12, 24, and none')); 
		echo $this->Form->input('FormInput.date_format', array('after' => ' valid values are DMY, MDY, YMD and NONE')); 
		echo $this->Form->input('FormInput.min_year', array('after' => ' valid value is a 4 digit year')); 
		echo $this->Form->input('FormInput.max_year', array('after' => ' valid value is a 4 digit year')); 
		echo $this->Form->input('FormInput.minute_interval', array('after' => ' time between minutes in minute drop down, ie. 15, will show 00 : 15 : 30 : 45')); 
		echo $this->Form->input('FormInput.div_id', array('type' => 'text', 'after' => ' a custom id for the div around this input')); 
		echo $this->Form->input('FormInput.div_class', array('after' => ' a custom class for the div around this input')); 
		echo $this->Form->input('FormInput.error_message', array('after' => ' a custom error message for this input')); 
		echo $this->Form->input('FormInput.is_unique'); 
		echo $this->Form->input('FormInput.is_system'); 
		echo $this->Form->input('FormInput.is_required'); 
		echo $this->Form->input('FormInput.is_quicksearch'); 
		echo $this->Form->input('FormInput.is_advancedsearch'); 
		echo $this->Form->input('FormInput.is_comparable'); 
		echo $this->Form->input('FormInput.is_layered'); 
		echo $this->Form->input('FormInput.layer_order'); 
		echo $this->Form->input('FormInput.is_not_db_field'); 
		echo $this->Form->input('FormInput.is_visible'); 
		echo $this->Form->input('FormInput.is_addable'); 
		echo $this->Form->input('FormInput.is_editable'); 
		echo $this->Form->input('FormInput.order'); 
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
<?php 
// set the contextual menu items
echo $this->Element('context_menu', array('menus' => array(
	array(
		'heading' => 'FormInputs',
		'items' => array(
			$this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('FormInput.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('FormInput.id'))),
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
	)));
?>

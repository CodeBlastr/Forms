<?php
/**
 * Form Inputs Admin Add View
 *
 * The view for adding form inputs.
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
	<fieldset>
	<?php echo $form->create('FormInput');?>
 		<legend><?php __('Add Form Input');?></legend>
        <fieldset>
 		<legend><?php __('How should the field appear in the form?');?></legend>
	<?php
		echo (isset($duplicate) ? $form->input('is_duplicate', array('type' => 'hidden', 'value' => '1')) : ''); 
		echo $form->input('FormInput.name', array('label' => 'Field Label')); 
		echo $form->input('FormInput.form_fieldset_id'); 
		echo $form->input('FormInput.order'); 
		echo $form->input('FormInput.input_type'); 
		echo $form->input('FormInput.is_visible'); 
		echo $form->input('FormInput.is_addable'); 
		echo $form->input('FormInput.is_editable'); 
			?>
	        <fieldset>
	 		<legend><?php __('Text field options');?></legend>
	        <?php 
			# for text fields
			echo $form->input('FormInput.min_length'); 
			echo $form->input('FormInput.max_length'); 
			?>
	        </fieldset>
	        <fieldset>
	 		<legend><?php __('Textarea field options');?></legend>
	        <?php 
			# for textarea fields
			echo $form->input('FormInput.rows'); 
			echo $form->input('FormInput.columns');
			?>
	        </fieldset>
	        <fieldset>
	 		<legend><?php __('Selects, checkboxes, and multi-selects, and radio sets options');?></legend>
	        <?php  
			# for selects, checkboxes, and multi-selects, and radio sets
			echo $form->input('FormInput.legend', array('after' => ' text above radio input types')); 
			echo $form->input('FormInput.multiple', array('after' => ' valid values are 1 or checkbox')); 
			echo $form->input('FormInput.empty_text', array('after' => ' text for null value in select drop downs')); 
			echo $form->input('FormInput.option_values', array('after' => ' One option per line')); 
			echo $form->input('FormInput.option_names', array('after' => ' must have the same number of lines')); 
			?>
	        </fieldset>
	        <fieldset>
	 		<legend><?php __('Date field options');?></legend>
	        <?php 
			echo $form->input('FormInput.time_format', array('after' => ' valid values are 12, 24, and none')); 
			echo $form->input('FormInput.date_format', array('after' => ' valid values are DMY, MDY, YMD and NONE')); 
			echo $form->input('FormInput.min_year', array('after' => ' valid value is a 4 digit year')); 
			echo $form->input('FormInput.max_year', array('after' => ' valid value is a 4 digit year')); 
			echo $form->input('FormInput.minute_interval', array('after' => ' time between minutes in minute drop down, ie. 15, will show 00 : 15 : 30 : 45')); 
			?>
	    	</fieldset>
    	</fieldset>
        <fieldset>
 		<legend><?php __('How should the information be treated in the database?');?></legend>
     <?php
		echo $form->input('FormInput.code', array('after' => 'The actual database column name if applicable.')); 
		echo $form->input('FormInput.is_not_db_field'); 
		echo $form->input('FormInput.model_override'); 
	?>
    	</fieldset>
        <fieldset>
 		<legend><?php __('Would you like ajax validation rules and messages?');?></legend>
     <?php
		echo $form->input('FormInput.is_required'); 
		echo $form->input('FormInput.validation', array('type' => 'select', 'label' => 'Validation Type', 'options' => array('email' => 'email', 'number' => 'number'), 'empty' => true)); 
		echo $form->input('FormInput.validation_message', array('after' => ' not currently used, but will be available in future versions')); 
	?>
    	</fieldset>
        <fieldset>
 		<legend><?php __('Should the field be prepopulated with any data?');?></legend>
     <?php
		echo $form->input('FormInput.system_default_value', array('empty' => true, 'options' => array('current user' => 'current user'))); 
		echo $form->input('FormInput.default_value'); 
	?>
    	</fieldset>
        <fieldset>
 		<legend><?php __('Do you want anything around the input (usually for help text)?');?></legend>
     <?php
		echo $form->input('FormInput.before'); 
		echo $form->input('FormInput.separator'); 
		echo $form->input('FormInput.after'); 
		echo $form->input('FormInput.div_id', array('type' => 'text', 'after' => ' a custom id for the div around this input')); 
		echo $form->input('FormInput.div_class', array('after' => ' a custom class for the div around this input')); 
		echo $form->input('FormInput.error_message', array('after' => ' a custom error message for this input')); 
	?>
    	</fieldset>
        <fieldset>
 		<legend><?php __('Mostly unused field options for future use.');?></legend>
     <?php
		echo $form->input('FormInput.is_unique'); 
		echo $form->input('FormInput.is_system'); 
		echo $form->input('FormInput.is_quicksearch'); 
		echo $form->input('FormInput.is_advancedsearch'); 
		echo $form->input('FormInput.is_comparable'); 
		echo $form->input('FormInput.is_layered'); 
		echo $form->input('FormInput.layer_order'); 
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

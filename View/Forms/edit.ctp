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

<div class="forms form">
    <h3><?php echo __('Form preview'); ?></h3>
	<div class="formPreview" style="border: 1px dashed #999; padding: 1em 1em 3em 1em;"><!-- move to system.css, inline due to repo updates -->
	<?php echo $this->Element('forms', array('id' => $this->request->data['Form']['id'], 'preview' => true), array('plugin' => 'forms')); ?>
    </div>
	<?php echo $this->Form->create('Form');?>
	<fieldset>
    	<legend class="toggleClick"><?php echo __('Edit %s Settings', $this->request->data['Form']['name']);?></legend>
    	<?php
		echo $this->Form->input('Form.id');
		echo $this->Form->input('Form.name');
		echo $this->Form->input('Form.method');
		echo $this->Form->input('Form.plugin'); 
		echo $this->Form->input('Form.model', array('placeholder' => 'Camel case model name' )); 
		echo $this->Form->input('Form.action', array('placeholder' => 'Ex. add, edit, view, save, remove'));
		echo $this->Form->input('Form.success_message'); 
		echo $this->Form->input('Form.success_url'); 
		echo $this->Form->input('Form.fail_message'); 
		echo $this->Form->input('Form.fail_url');
		echo $this->Form->input('Form.notifiees', array('type' => 'text', 'label' => 'Email(s) to notify of submissions', 'placeholder' => 'Separate emails by commas'));
		echo $this->Form->end('Submit'); ?>
	</fieldset>
</div>


<div class="formInputs form">
	<?php echo $this->Form->create('FormInput');?>
    <fieldset>
 	<legend><?php echo __('Add a form input.');?></legend>
	<?php
		echo $this->Form->hidden('FormInput.id');
		echo $this->Form->hidden('FormInput.form_id', array('value' => $this->request->data['Form']['id']));
		echo $this->Form->hidden('FormInput.is_visible', array('value' => 1));
		echo $this->Form->hidden('FormInput.is_addable', array('value' => 1));
		echo $this->Form->hidden('FormInput.is_editable', array('value' => 1));
		
		echo (isset($duplicate) ? $this->Form->input('FormInput.is_duplicate', array('type' => 'hidden', 'value' => '1')) : '');
		echo $this->Form->input('FormInput.name', array('label' => 'Label'));
		echo $this->Form->input('FormInput.input_type');
		echo $this->Form->input('FormInput.system_default_value', array('label' => 'Default Value', 'empty' => __('-- Empty --')));
		echo $this->Form->input('FormInput.default_value', array('type' => 'text', 'label' => 'Custom Default Value'));?>
	        <fieldset>
	 		<legend class="toggleClick"><?php echo __('Advanced text field options');?></legend>
	        <?php
			# for text fields
			echo $this->Form->input('FormInput.min_length');
			echo $this->Form->input('FormInput.max_length'); ?>
	        </fieldset>
	        <fieldset>
	 		<legend class="toggleClick"><?php echo __('Advanced textarea field options');?></legend>
	        <?php
			# for textarea fields
			echo $this->Form->input('FormInput.rows');
			echo $this->Form->input('FormInput.columns'); ?>
	        </fieldset>
	        <fieldset>
	 		<legend class="toggleClick"><?php echo __('Advanced select, checkbox, multi-select, and radio options');?></legend>
	        <?php
			# for selects, checkboxes, and multi-selects, and radio sets
			echo $this->Form->input('FormInput.legend', array('placeholder' => ' text above radio input types'));
			echo $this->Form->input('FormInput.multiple', array('placeholder' => ' valid values are 1 or checkbox'));
			echo $this->Form->input('FormInput.empty_text', array('placeholder' => ' text for null value in select drop downs'));
			echo $this->Form->input('FormInput.option_values', array('placeholder' => ' One option per line'));
			echo $this->Form->input('FormInput.option_names', array('placeholder' => ' must have the same number of lines')); ?>
	        </fieldset>
	        <fieldset>
	 		<legend class="toggleClick"><?php echo __('Advanced date time options');?></legend>
	        <?php
			echo $this->Form->input('FormInput.time_format', array('placeholder' => ' valid values are 12, 24, and none'));
			echo $this->Form->input('FormInput.date_format', array('placeholder' => ' valid values are DMY, MDY, YMD and NONE'));
			echo $this->Form->input('FormInput.min_year', array('placeholder' => ' valid value is a 4 digit year'));
			echo $this->Form->input('FormInput.max_year', array('placeholder' => ' valid value is a 4 digit year'));
			echo $this->Form->input('FormInput.minute_interval', array('placeholder' => ' time between minutes in minute drop down, ie. 15, will show 00 : 15 : 30 : 45')); ?>
	    	</fieldset>
    	</fieldset>
        <fieldset>
 		<legend><?php echo $this->Form->input('FormInput.is_db_field', array('type' => 'checkbox', 'label' => 'Should values be stored in database?', 'div' => false));?></legend>
    	<?php
		echo $this->Form->hidden('FormInput.is_not_db_field');
		echo $this->Form->input('FormInput.code', array('placeholder' => 'Unique database field name'));
		echo $this->Form->input('FormInput.model_override', array('label' => 'Which database table? (Model name)', 'placeholder' => __('Currently %s', $this->request->data['Form']['model']))); ?>
    	</fieldset>
        <fieldset>
 		<legend><?php echo $this->Form->input('FormInput.is_required', array('label' => 'Is this field required?', 'div' => false)); ?></legend>
     	<?php
		echo $this->Form->input('FormInput.validation', array('type' => 'select', 'label' => 'Validation Type', 'options' => array('email' => 'email', 'number' => 'number'), 'empty' => true));
		// echo $this->Form->input('FormInput.validation_message', array('placeholder' => ' not currently used, but will be available in future versions'));
		// echo $this->Form->input('FormInput.error_message', array('placeholder' => ' a custom error message for this input')); ?>
    	</fieldset>
        <fieldset>
 		<legend class="toggleClick"><?php echo __('Advanced input appearance options.');?></legend>
    	<?php
		echo $this->Form->input('FormInput.placeholder', array('placeholder' => 'Shows inside the input, and disappears on focus'));
		echo $this->Form->input('FormInput.show_label', array('label' => 'Display the Label?', 'checked' => 'checked'));
		echo $this->Form->input('FormInput.before', array('placeholder' => 'Shows before the input label', 'div' => array('class' => 'input text clear')));
		echo $this->Form->input('FormInput.separator', array('placeholder' => 'Shows between the label and the input'));
		echo $this->Form->input('FormInput.after', array('placeholder' => 'Shows after the input'));
		echo $this->Form->input('FormInput.div_id', array('type' => 'text', 'placeholder' => ' a custom id for the div around this input'));
		echo $this->Form->input('FormInput.div_class', array('placeholder' => ' a custom class for the div around this input')); ?>
    	</fieldset>
        <?php /* fieldset>
 		<legend><?php echo __('Mostly unused field options for future use.');?></legend>
    	<?php
		echo $this->Form->input('FormInput.is_unique');
		echo $this->Form->input('FormInput.is_system');
		echo $this->Form->input('FormInput.is_quicksearch');
		echo $this->Form->input('FormInput.is_advancedsearch');
		echo $this->Form->input('FormInput.is_comparable');
		echo $this->Form->input('FormInput.is_layered');
		echo $this->Form->input('FormInput.layer_order'); ?>
		</fieldset */ ?>
<?php echo $this->Form->end('Submit');?>
</div>


<?php 
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Forms',
		'items' => array(
			$this->Html->link(__('List Forms', true), array('action' => 'index'), array('class' => 'index')),
			)
		),
	array(
		'heading' => 'Form Fieldsets',
		'items' => array(
			$this->Html->link(__('New Fieldset', true), array('controller' => 'form_fieldsets', 'action' => 'add', $this->request->data['Form']['id']), array('class' => 'add')),
			$this->Html->link(__('Show Fieldsets', true), array('controller' => 'form_fieldsets', 'action' => 'index'), array('class' => 'index')),
			)
		),
	array(
		'heading' => 'Form Inputs',
		'items' => array(
			$this->Html->link(__('New Input', true), array('controller' => 'form_inputs', 'action' => 'add', $this->request->data['Form']['id']), array('class' => 'add')),
			$this->Html->link(__('List Inputs', true), array('controller' => 'form_inputs', 'action' => 'index'), array('class' => 'index')),
			)
		),
	))); ?>
    
<script type="text/javascript">
advancedOptions()
databaseOptions()
requiredOptions()
defaultValue()

$("#FormInputEditForm").change( function() {
	advancedOptions()
	databaseOptions()
	requiredOptions()
	defaultValue()
})


function advancedOptions() {
	$("#FormInputMinLength").parent().parent().hide();
	$("#FormInputRows").parent().parent().hide();
	$("#FormInputLegend").parent().parent().hide();
	$("#FormInputTimeFormat").parent().parent().hide();
	
	var val = $("#FormInputInputType").val()
	
	if (val == "text") {
		$("#FormInputMinLength").parent().parent().show()
	}
	if (val == "textarea") {
		$("#FormInputRows").parent().parent().show()
	}
	if (val == "select" || val == "select" || val == "radio" || val == "checkbox") {
		$("#FormInputLegend").parent().parent().show()
	}
	if (val == "date" || val == "time" || val == "datetime") {
		$("#FormInputTimeFormat").parent().parent().show()
	}
}

function databaseOptions() {
	$("#FormInputCode").parent().hide()
	$("#FormInputModelOverride").parent().hide()
	$("#FormInputIsNotDbField").val(0)
	if ($("#FormInputIsDbField").is(':checked')) {
		$("#FormInputIsNotDbField").val(1)
		$("#FormInputCode").parent().show()
		$("#FormInputModelOverride").parent().show()
	}
}

function requiredOptions() {
	$("#FormInputValidation").parent().hide()
	if ($("#FormInputIsRequired").is(':checked')) {
		$("#FormInputValidation").parent().show()
	}
}

function defaultValue() {
	$("#FormInputDefaultValue").parent().hide()
	if ($("#FormInputSystemDefaultValue").val() == "custom") {
		$("#FormInputDefaultValue").parent().show()
	}
}		
</script>


<style type="text/css">
/*  Move this to mobi when done!!! */
input[type=checkbox] {
	margin: 4px 6px 7px 0;
}
form div.input.checkbox {
	padding: 2em 0.4em 0;
}
.clear {
	clear: both;
}
form label.error {
	float: left;
}
form .editFormSpan {
	position: relative;
	display: block;
	width: 1px;
	height: 1px;
}
form .editFormSpan .editFormInput {
	position: absolute;
	top: 1.6em;
	left: -1.4em;
	text-indent: -3000px;
	width: 23px;
	height: 23px;
	background: url('/img/admin/icons/icon-sprite.png') -581px -116px no-repeat;
}
</style>

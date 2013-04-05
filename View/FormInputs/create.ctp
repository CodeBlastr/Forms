<?php
echo $this->Html->css('/forms/css/formInputCreate.css', null, array('inline' => false));
echo $this->Html->script('http://code.jquery.com/ui/1.10.2/jquery-ui.js', array('inline' => false));
echo $this->Html->script('/forms/js/formInputCreate.js', array('inline' => false));
?>

<div id="formBuilder" class="row-fluid">
	<div class="span4">
		<div id="usableInputs">
			<b>Elements</b><hr/>
			<div class="usableInput" id="label"><label>Abc</label></div>
			<div class="usableInput" id="checkbox"><input type="checkbox" disabled="disabled"> <label>Checkbox</label></div>
			<div class="usableInput" id="radio"><input type="radio" disabled="disabled"> <label>Radio Button</label></div>
			<div class="usableInput" id="text"><label>Text Line</label> <input type="text" disabled="disabled"></div>
			<div class="usableInput" id="textarea"><label>Textarea</label> <textarea disabled="disabled"></textarea></div>
			<div class="usableInput" id="fileselect"><label>Upload a File</label> <input type="file" disabled="disabled"></div>
		</div>
	</div>
	<div class="span5">
		<div id="formInputs"></div>
	</div>
	<div class="span3">
		<div id="inputOptions">
			<?php
			echo $this->Form->create('FormInput');
			echo $this->Form->submit('Save Form Inputs');
			echo $this->Form->end();
			?>
		</div>
	</div>
</div>


<div id="formMaster" style="display:none;">
	<?php
	// How should the field appear in the form?
	echo (isset($duplicate) ? $this->Form->input('is_duplicate', array('type' => 'hidden', 'value' => '1')) : '');
	echo $this->Form->input('FormInput.name', array('label' => 'Label', 'class'=>'FormInputName'));
	echo $this->Form->input('FormInput.show_label', array('value' => '1', 'checked' => true, 'label' => 'Display the Label?'));
	echo $this->Form->input('FormInput.expected_value', array('label' => 'Expected Answer'));
	echo $this->Form->hidden('FormInput.form_id', array('value' => $formId));
	echo $this->Form->hidden('FormInput.order');
	echo $this->Form->hidden('FormInput.input_type');
	echo $this->Form->hidden('FormInput.is_visible', array('value' => '1', 'checked' => true));
	echo $this->Form->hidden('FormInput.is_addable', array('value' => '1', 'checked' => true));
	echo $this->Form->hidden('FormInput.is_editable', array('value' => '1', 'checked' => true));

	// Text field options
	echo $this->Html->tag('div', $this->Form->input('FormInput.min_length', array('class' => 'input-mini', 'min' => 0, 'div' => array('class' => 'span6')))
			. $this->Form->input('FormInput.max_length', array('class' => 'input-mini', 'min' => 0, 'div' => array('class' => 'span6')))
			. $this->Form->input('FormInput.placeholder', array('placeholder' => 'this is a "placeholder"'))
			, array('class' => 'textConfig hiddenConfig')
	);

	// Textarea field options
	echo $this->Html->tag('div', $this->Form->input('FormInput.rows', array('class' => 'input-mini', 'min' => 0, 'div' => array('class' => 'span6')))
			. $this->Form->input('FormInput.columns', array('class' => 'input-mini', 'min' => 0, 'div' => array('class' => 'span6')))
			, array('class' => 'textareaConfig hiddenConfig')
	);

	// Selects, checkboxes, and multi-selects, and radio sets options
	echo $this->Html->tag('div', $this->Form->input('FormInput.legend', array('after' => '<small>text above radio input types</small>'))
			. $this->Form->input('FormInput.multiple', array('options' => array('1' => 'Yes', 'checkbox' => 'Checkbox'), 'empty' => true ))//, 'after' => ' valid values are 1 or checkbox'))
			. $this->Form->hidden('FormInput.empty_text', array('after' => '<small>text for null value in select drop downs</small>', 'empty' => '-- select one --'))
			. $this->Form->input('FormInput.option_values', array('class'=>'FormInputOptionValues hidden', 'label' => false))
			. $this->Form->input('FormInput.option_names', array('class'=>'FormInputOptionNames') )//, array('after' => '<small>must have the same number of lines</small>'))
			, array('class' => 'multipleConfig hiddenConfig')
	);

	// Date field options
	echo $this->Html->tag('div', $this->Form->input('FormInput.time_format', array('options' => array('12' => '12', '24' => '24'), 'empty' => true))
			. $this->Form->input('FormInput.date_format', array('options' => array('DMY' => 'DMY', 'MDY' => 'MDY', 'YMD' => 'YMD'), 'empty' => true))
			. $this->Form->input('FormInput.min_year', array('class' => 'input-mini', 'after' => ' valid value is a 4 digit year'))
			. $this->Form->input('FormInput.max_year', array('class' => 'input-mini', 'after' => ' valid value is a 4 digit year'))
			. $this->Form->input('FormInput.minute_interval', array('class' => 'input-mini', 'after' => '<small>time between minutes in minute drop down, ie. 15, will show 00 : 15 : 30 : 45</small>'))
			, array('class' => 'dateConfig hiddenConfig')
	);

	// How should the information be treated in the database?
	echo $this->Form->hidden('FormInput.code', array('value' => 'answer'));
	echo $this->Form->hidden('FormInput.is_not_db_field', array('value' => '1'));
	echo $this->Form->hidden('FormInput.is_duplicate', array('value' => '1'));
	echo $this->Form->hidden('FormInput.model_override', array('value' => 'FormAnswer'));

	// Would you like ajax validation rules and messages?
	echo $this->Form->input('FormInput.is_required', array('value' => '1', 'checked' => true));
	echo $this->Form->hidden('FormInput.validation', array('type' => 'select', 'label' => 'Validation Type', 'options' => array('email' => 'email', 'number' => 'number'), 'empty' => true));
	echo $this->Form->hidden('FormInput.validation_message', array('after' => '<small>not currently used, but will be available in future versions</small>'));

	echo $this->Html->tag('fieldset',
			// Should the field be prepopulated with any data?
			$this->Html->tag('legend', 'Advanced Options', array('class' => 'toggleClick'))
			. $this->Form->input('FormInput.system_default_value', array('empty' => true, 'options' => array('current user' => 'current user')))
			. $this->Form->input('FormInput.default_value')

			// Do you want anything around the input (usually for help text)?
			. $this->Form->input('FormInput.before')
			. $this->Form->input('FormInput.separator')
			. $this->Form->input('FormInput.after')
			. $this->Form->input('FormInput.div_id', array('type' => 'text', 'after' => '<small>a custom id for the div around this input</small>'))
			. $this->Form->input('FormInput.div_class', array('after' => '<small>a custom class for the div around this input</small>'))
			. $this->Form->input('FormInput.error_message', array('after' => '<small>a custom error message for this input</small>'))
	);

	// Mostly unused field options for future use.
	echo $this->Form->hidden('FormInput.is_unique');
	echo $this->Form->hidden('FormInput.is_system');
	echo $this->Form->hidden('FormInput.is_quicksearch');
	echo $this->Form->hidden('FormInput.is_advancedsearch');
	echo $this->Form->hidden('FormInput.is_comparable');
	echo $this->Form->hidden('FormInput.is_layered');
	echo $this->Form->hidden('FormInput.layer_order');
	?>
</div>


<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
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

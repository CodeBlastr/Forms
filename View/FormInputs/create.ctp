<?php
echo $this->Html->css('/forms/css/formInputCreate.css', null, array('inline' => false));
echo $this->Html->script('http://code.jquery.com/ui/1.10.2/jquery-ui.js', array('inline' => false));
echo $this->Html->script('/forms/js/formInputCreate.js', array('inline' => false));
?>

<div id="formBuilder" class="row-fluid">
	<div class="span4">
		<div id="usableInputs">
			<b>Elements</b><hr/>
			<div class="usableInput" id="textbox">Abc</div>
			<div class="usableInput" id="checkbox"><input type="checkbox" disabled="disabled"> Checkbox</div>
			<div class="usableInput" id="radio"><input type="radio" disabled="disabled"> Radio Button</div>
			<div class="usableInput" id="textline">Text Line <input type="text" disabled="disabled"></div>
			<div class="usableInput" id="textarea">Textarea <textarea disabled="disabled"></textarea></div>
			<div class="usableInput" id="fileselect">Upload a File <input type="file" disabled="disabled"></div>
		</div>
	</div>
	<div class="span6">
		<div id="formInputs"></div>
	</div>
	<div class="span2">
		<div id="inputOptions">
			<?php
			echo $this->Form->create('FormInput');
			echo $this->Form->submit('Save Form');
			echo $this->Form->end();
			?>
		</div>
	</div>
</div>


<div id="formMaster" style="display:none;">
	<?php
	// How should the field appear in the form?
	echo (isset($duplicate) ? $this->Form->input('is_duplicate', array('type' => 'hidden', 'value' => '1')) : '');
	echo $this->Form->input('FormInput.name', array('label' => 'Field Label'));
	echo $this->Form->input('FormInput.show_label', array('label' => 'Display the Label?'));
	echo $this->Form->hidden('FormInput.form_fieldset_id');
	echo $this->Form->input('FormInput.order', array('class' => 'input-mini'));
	echo $this->Form->hidden('FormInput.input_type');
	echo $this->Form->input('FormInput.is_visible');
	echo $this->Form->input('FormInput.is_addable');
	echo $this->Form->input('FormInput.is_editable');

	// Text field options
	echo $this->Html->tag('div', $this->Form->input('FormInput.min_length', array('class' => 'input-mini', 'min' => 0))
			. $this->Form->input('FormInput.max_length', array('class' => 'input-mini', 'min' => 0))
			. $this->Form->input('FormInput.placeholder', array('placeholder' => 'this is a "placeholder"'))
			, array('class' => 'textConfig hiddenConfig')
	);

	// Textarea field options
	echo $this->Html->tag('div', $this->Form->input('FormInput.rows', array('class' => 'input-mini', 'min' => 0))
			. $this->Form->input('FormInput.columns', array('class' => 'input-mini', 'min' => 0))
			, array('class' => 'textareaConfig hiddenConfig')
	);

	// Selects, checkboxes, and multi-selects, and radio sets options
	echo $this->Html->tag('div', $this->Form->input('FormInput.legend', array('after' => ' text above radio input types'))
			. $this->Form->input('FormInput.multiple', array('after' => ' valid values are 1 or checkbox'))
			. $this->Form->input('FormInput.empty_text', array('after' => ' text for null value in select drop downs'))
			. $this->Form->input('FormInput.option_values', array('after' => ' One option per line'))
			. $this->Form->input('FormInput.option_names', array('after' => ' must have the same number of lines'))
			, array('class' => 'multipleConfig hiddenConfig')
	);

	// Date field options
	echo $this->Html->tag('div', $this->Form->input('FormInput.time_format', array('options' => array('12' => '12', '24' => '24'), 'empty' => true))
			. $this->Form->input('FormInput.date_format', array('options' => array('DMY' => 'DMY', 'MDY' => 'MDY', 'YMD' => 'YMD'), 'empty' => true))
			. $this->Form->input('FormInput.min_year', array('class' => 'input-mini', 'after' => ' valid value is a 4 digit year'))
			. $this->Form->input('FormInput.max_year', array('class' => 'input-mini', 'after' => ' valid value is a 4 digit year'))
			. $this->Form->input('FormInput.minute_interval', array('class' => 'input-mini', 'after' => ' time between minutes in minute drop down, ie. 15, will show 00 : 15 : 30 : 45'))
			, array('class' => 'dateConfig hiddenConfig')
	);

	// How should the information be treated in the database?
	echo $this->Form->input('FormInput.code', array('after' => 'The actual database column name if applicable.'));
	echo $this->Form->input('FormInput.is_not_db_field');
	echo $this->Form->input('FormInput.model_override');

	// Would you like ajax validation rules and messages?
	echo $this->Form->input('FormInput.is_required');
	echo $this->Form->input('FormInput.validation', array('type' => 'select', 'label' => 'Validation Type', 'options' => array('email' => 'email', 'number' => 'number'), 'empty' => true));
	echo $this->Form->input('FormInput.validation_message', array('after' => ' not currently used, but will be available in future versions'));

	// Should the field be prepopulated with any data?
	echo $this->Form->input('FormInput.system_default_value', array('empty' => true, 'options' => array('current user' => 'current user')));
	echo $this->Form->input('FormInput.default_value');

	// Do you want anything around the input (usually for help text)?
	echo $this->Form->input('FormInput.before');
	echo $this->Form->input('FormInput.separator');
	echo $this->Form->input('FormInput.after');
	echo $this->Form->input('FormInput.div_id', array('type' => 'text', 'after' => ' a custom id for the div around this input'));
	echo $this->Form->input('FormInput.div_class', array('after' => ' a custom class for the div around this input'));
	echo $this->Form->input('FormInput.error_message', array('after' => ' a custom error message for this input'));

	// Mostly unused field options for future use.
	echo $this->Form->input('FormInput.is_unique');
	echo $this->Form->input('FormInput.is_system');
	echo $this->Form->input('FormInput.is_quicksearch');
	echo $this->Form->input('FormInput.is_advancedsearch');
	echo $this->Form->input('FormInput.is_comparable');
	echo $this->Form->input('FormInput.is_layered');
	echo $this->Form->input('FormInput.layer_order');
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

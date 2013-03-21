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
		<div id="formInputs" style="border: 1px solid #ccffcc; height: 300px;">
			
		</div>
	</div>
	<div class="span2">
		<div id="inputOptions">
			
		</div>
	</div>
</div>

<style type="text/css">
.usableInput {
	padding: 10px 5px 5px 5px;
	background: #fff;
}
.usableInput:hover {
	border: 1px solid #ccc;
	border-top: 5px solid #ccc;
	padding: 5px 4px 4px 4px;
	border-radius: 5px;
	cursor: move;
}
.usableInput *:hover, .usableInput input:hover  {cursor: move !important;}
.configuring {
	border: 1px solid #ccc;
	border-top: 5px solid #ccc;
	padding: 5px 4px 4px 4px;
	border-radius: 5px;
}
</style>

<script type="text/javascript" src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(
	function () {
		
		var inputs = [];
		var newId;

		$("#formInputs").sortable({
			stop: function(event, ui) {
				console.log(event.type);
//				console.log(ui);
				ui.item.attr('id', newId); /* @todo: need to not do this when we are just re-sorting */
				$("#formInputs .usableInput").each(function(index, element) {
					/* @todo: need to apply the correct names to this fields actual set of config fields */
					//$(this).attr('name', 'data[FormInput]['+index+']');
				});
				$('#'+newId).click();
			},
			receive: function(event, ui) {
				var currentInputTypeId = $(ui.sender).attr('id');
				if ( inputs[currentInputTypeId] === undefined ) {
					inputs[currentInputTypeId] = 0
				} else {
					inputs[currentInputTypeId] = inputs[currentInputTypeId] + 1;
				}
				newId = currentInputTypeId + '_' + inputs[currentInputTypeId];
//				console.log(ui);
			}
		});
		$(".usableInput").draggable({
			containment : "#formBuilder",
			cursor : 'move',
			cursorAt : {left:5},
			grid : [10,10],
			zIndex : 100,
			snap : true,
			//snapMode : 'outer',
			helper : 'clone',
			connectToSortable : '#formInputs',
			revert: function( event, ui ) {
				$(this).data("uiDraggable").originalPosition = {top : 0, left : 0};
				return !event;
			},
			stop: function( event, ui ) {}
		});
		$("#formInputs").droppable({
			accept : ".usableInput",
			hoverclass : "acceptable"
		});
		$("#formInputs").on("click", '.usableInput', function(event){
			var configPanel;
			$("#formInputs .usableInput").removeClass('configuring');
			$(this).addClass('configuring');
			var type = $(this).attr('id').split("_")[0];
			var typeId = $(this).attr('id').split("_")[1];
			if ( $.inArray(type, ['checkbox', 'radio']) !== -1 ) {
				type = 'multiple';
			}
			// show new config panel of type: type
			if ( $("#config_"+type).is('*') ) {
				configPanel = $("#config_"+type).html();
			} else {
				configPanel = '';
			}
			$("#inputOptions").html(configPanel);
		});
	});
//]]>
</script>








<div class="formInputs form">
	<fieldset>
	<?php echo $this->Form->create('FormInput');?>
 		<legend><?php echo __('Add Form Input');?></legend>
        <fieldset>
			<legend><?php echo __('How should the field appear in the form?');?></legend>
			<?php
			echo (isset($duplicate) ? $this->Form->input('is_duplicate', array('type' => 'hidden', 'value' => '1')) : '');
			echo $this->Form->input('FormInput.name', array('label' => 'Field Label'));
			echo $this->Form->input('FormInput.show_label', array('label' => 'Display the Label?'));
			echo $this->Form->input('FormInput.form_fieldset_id');
			echo $this->Form->input('FormInput.order');
			echo $this->Form->input('FormInput.input_type');
			echo $this->Form->input('FormInput.is_visible');
			echo $this->Form->input('FormInput.is_addable');
			echo $this->Form->input('FormInput.is_editable');
			?>
	        <fieldset id="config_textline">
				<legend><?php echo __('Text field options');?></legend>
				<?php
				// for text fields
				echo $this->Form->input('FormInput.min_length', array('class' => 'input-mini', 'min' => 0));
				echo $this->Form->input('FormInput.max_length', array('class' => 'input-mini', 'min' => 0));
				echo $this->Form->input('FormInput.placeholder', array('placeholder' => 'this is a "placeholder"'));
				echo $this->Form->submit('Apply');
				?>
	        </fieldset>
	        <fieldset id="config_textarea">
				<legend><?php echo __('Textarea field options');?></legend>
				<?php
				// for textarea fields
				echo $this->Form->input('FormInput.rows', array('class' => 'input-mini', 'min' => 0));
				echo $this->Form->input('FormInput.columns', array('class' => 'input-mini', 'min' => 0));
				echo $this->Form->submit('Apply');
				?>
	        </fieldset>
	        <fieldset id="config_multiple">
				<legend><?php echo __('Selects, checkboxes, and multi-selects, and radio sets options');?></legend>
				<?php
				// for selects, checkboxes, and multi-selects, and radio sets
				echo $this->Form->input('FormInput.legend', array('after' => ' text above radio input types'));
				echo $this->Form->input('FormInput.multiple', array('after' => ' valid values are 1 or checkbox'));
				echo $this->Form->input('FormInput.empty_text', array('after' => ' text for null value in select drop downs'));
				echo $this->Form->input('FormInput.option_values', array('after' => ' One option per line'));
				echo $this->Form->input('FormInput.option_names', array('after' => ' must have the same number of lines'));
				echo $this->Form->submit('Apply');
				?>
	        </fieldset>
	        <fieldset id="config_dates">
				<legend><?php echo __('Date field options');?></legend>
				<?php
				echo $this->Form->input('FormInput.time_format', array('after' => ' valid values are 12, 24, and none'));
				echo $this->Form->input('FormInput.date_format', array('after' => ' valid values are DMY, MDY, YMD and NONE'));
				echo $this->Form->input('FormInput.min_year', array('after' => ' valid value is a 4 digit year'));
				echo $this->Form->input('FormInput.max_year', array('after' => ' valid value is a 4 digit year'));
				echo $this->Form->input('FormInput.minute_interval', array('after' => ' time between minutes in minute drop down, ie. 15, will show 00 : 15 : 30 : 45'));
				echo $this->Form->submit('Apply');
				?>
	    	</fieldset>
    	</fieldset>
        <fieldset>
			<legend><?php echo __('How should the information be treated in the database?');?></legend>
			<?php
			echo $this->Form->input('FormInput.code', array('after' => 'The actual database column name if applicable.'));
			echo $this->Form->input('FormInput.is_not_db_field');
			echo $this->Form->input('FormInput.model_override');
			?>
    	</fieldset>
        <fieldset>
			<legend><?php echo __('Would you like ajax validation rules and messages?');?></legend>
			<?php
			echo $this->Form->input('FormInput.is_required');
			echo $this->Form->input('FormInput.validation', array('type' => 'select', 'label' => 'Validation Type', 'options' => array('email' => 'email', 'number' => 'number'), 'empty' => true));
			echo $this->Form->input('FormInput.validation_message', array('after' => ' not currently used, but will be available in future versions'));
			?>
    	</fieldset>
        <fieldset>
			<legend><?php echo __('Should the field be prepopulated with any data?');?></legend>
			<?php
			echo $this->Form->input('FormInput.system_default_value', array('empty' => true, 'options' => array('current user' => 'current user')));
			echo $this->Form->input('FormInput.default_value');
			?>
    	</fieldset>
        <fieldset>
			<legend><?php echo __('Do you want anything around the input (usually for help text)?');?></legend>
			<?php
			echo $this->Form->input('FormInput.before');
			echo $this->Form->input('FormInput.separator');
			echo $this->Form->input('FormInput.after');
			echo $this->Form->input('FormInput.div_id', array('type' => 'text', 'after' => ' a custom id for the div around this input'));
			echo $this->Form->input('FormInput.div_class', array('after' => ' a custom class for the div around this input'));
			echo $this->Form->input('FormInput.error_message', array('after' => ' a custom error message for this input'));
			?>
    	</fieldset>
        <fieldset>
			<legend><?php echo __('Mostly unused field options for future use.');?></legend>
			<?php
			echo $this->Form->input('FormInput.is_unique');
			echo $this->Form->input('FormInput.is_system');
			echo $this->Form->input('FormInput.is_quicksearch');
			echo $this->Form->input('FormInput.is_advancedsearch');
			echo $this->Form->input('FormInput.is_comparable');
			echo $this->Form->input('FormInput.is_layered');
			echo $this->Form->input('FormInput.layer_order');
			?>
		</fieldset>
<?php echo $this->Form->end('Submit');?>
	</fieldset>
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

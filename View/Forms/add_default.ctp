<?php
/**
 * Form Admin Add View
 *
 * The view for adding forms.
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2012, Zuha Foundation Inc. (http://zuhafoundation.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.forms.views
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 * @todo		  Make it so that code is a drop down where you can pick from fields in that model. OR add a new one.
 */
?>

<div class="forms add-form">
	<h1><?php echo __('Create a Form'); ?></h1>
	<?php echo $this->Form->create('Form'); ?>
	<fieldset>
		<legend><?php echo __('What type of form?'); ?></legend>
		<?php
		echo $this->Form->input('Form.copy', array('label' => false, 'empty' => '--Select--'));
		?>
    </fieldset>
	<fieldset>
		<legend><?php echo __('Add Form'); ?></legend>
		<?php
		echo $this->Form->input('Form.id');
		echo $this->Form->input('Form.name');
		?>
    </fieldset>
    <fieldset>
		<legend><?php echo __('Advanced Settings'); ?></legend>
		<?php
		echo $this->Form->input('Form.method');
		echo $this->Form->input('Form.action', array('placeholder' => ' (ex: add, edit, view, save, remove)'));
		echo $this->Form->input('Form.save_db', array('type' => 'checkbox'));
		echo $this->Form->input('Form.plugin');
		//echo $this->Form->input('Form.model', array('placeholder' => ' (camel case model name)'));
		echo $this->Form->input('Form.success_message');
		echo $this->Form->input('Form.success_url');
		echo $this->Form->input('Form.fail_message');
		echo $this->Form->input('Form.fail_url');
		echo $this->Form->input('Form.notifiees', array('type' => 'text', 'label' => 'Email(s) to notify of submissions', 'placeholder' => 'Separate emails by commas'));
		?>
	</fieldset>
	<?php echo $this->Form->end('Submit'); ?>
</div>
<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
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
)));
?>



<script type="text/javascript">

	$("#FormCopy").change(function() {
		formType();
	});

	function formType() {
		$("#FormName").parent().parent().hide();
		$("#FormMethod").parent().parent().hide();

		var val = $("#FormCopy").val();

		if (val === "custom") {
			$("#FormName").parent().parent().show();
			$("#FormMethod").parent().parent().show();
		}
	}

	formType();
</script>

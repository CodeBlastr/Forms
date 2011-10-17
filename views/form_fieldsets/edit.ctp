<?php
/**
 * Form Fieldset Admin Edit View
 *
 * The view for adding field sets.
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

<div class="formFieldsets form">
<h1><?php __('Manage Form Fieldset');?></h1>
<?php echo $this->Form->create('FormFieldset', array('action' => 'edit'));?>
	<fieldset>
 		<legend><?php __('Fieldset');?></legend>
	<?php
		echo $this->Form->input('FormFieldset.id');
		echo $this->Form->input('FormFieldset.form_id'); 
		echo $this->Form->input('FormFieldset.name'); 
		echo $this->Form->input('FormFieldset.legend', array('after' => ' Leave empty to remove legend tag')); 
		echo $this->Form->input('FormFieldset.model'); 
		echo $this->Form->input('FormFieldset.order'); 
	?>
    </fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
<?php 
// set the contextual menu items
$this->Menu->setValue(array(
	array(
		'heading' => 'Form Fieldsets',
		'items' => array(
			$this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('FormFieldset.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('FormFieldset.id'))),
			$this->Html->link(__('List FormInputs', true), array('action' => 'index')),
			)
		),
	array(
		'heading' => 'FormInputs',
		'items' => array(
			$this->Html->link(__('New FormInputs', true), array('controller' => 'formInputs', 'action' => 'edit')),
			$this->Html->link(__('List FormInputss', true), array('controller' => 'formInputs', 'action' => 'index')),
			)
		),
	)
);
?>

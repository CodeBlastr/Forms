<?php
/**
 * Form Fieldset Admin Index View
 *
 * The view for a list of field sets.
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

<div class="formFieldsets index">
<h2><?php __('Form Fieldsets');?></h2>
<p>
<?php
echo $this->Paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('id');?></th>
	<th><?php echo $this->Paginator->sort('form_id');?></th>
	<th><?php echo $this->Paginator->sort('name');?></th>
	<th><?php echo $this->Paginator->sort('legend');?></th>
	<th><?php echo $this->Paginator->sort('model');?></th>
	<th><?php echo $this->Paginator->sort('order');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($formFieldsets as $formFieldset):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $formFieldset['FormFieldset']['id']; ?>
		</td>
		<td>
			<?php echo $formFieldset['Form']['name']; ?>
		</td>
		<td>
			<?php echo $formFieldset['FormFieldset']['name']; ?>
		</td>
		<td>
			<?php echo $formFieldset['FormFieldset']['legend']; ?>
		</td>
		<td>
			<?php echo $formFieldset['FormFieldset']['model']; ?>
		</td>
		<td>
			<?php echo $formFieldset['FormFieldset']['order']; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $formFieldset['FormFieldset']['id'])); ?>
			<?php echo $this->Html->link(__('View Form Inputs', true), array('controller' => 'formInputs', 'action' => 'index', 'fieldset' => $formFieldset['FormFieldset']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $formFieldset['FormFieldset']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $formFieldset['FormFieldset']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<?php echo $this->element('paging'); ?>
<?php 
// set the contextual menu items
$this->Menu->setValue(array(
	array(
		'heading' => 'Forms',
		'items' => array(
			$this->Html->link(__('New Form', true), array('controller' => 'forms', 'action' => 'add')),
			$this->Html->link(__('List Forms', true), array('controller' => 'forms', 'action' => 'index')),
			)
		),
	array(
		'heading' => 'Form Fieldsets',
		'items' => array(
			$this->Html->link(__('New Form Fieldset', true), array('action' => 'edit')),
			)
		),
	array(
		'heading' => 'Form Inputs',
		'items' => array(
			$this->Html->link(__('New Form Inputs', true), array('controller' => 'form_inputs', 'action' => 'add')),
			$this->Html->link(__('List Form Inputs', true), array('controller' => 'form_inputs', 'action' => 'index')),
			)
		),
	)
);
?>

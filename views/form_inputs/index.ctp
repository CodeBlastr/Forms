<?php
/**
 * Form Inputs Admin Index View
 *
 * The view for a list of form inputs.
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

<div class="formInputs index">
<h2><?php __('Form Inputs');?></h2>
<p>
<?php
echo $this->Paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('id');?></th>
	<th><?php echo $this->Paginator->sort('form_fieldset_id');?></th>
	<th><?php echo $this->Paginator->sort('code');?></th>
	<th><?php echo $this->Paginator->sort('name');?></th>
	<th><?php echo $this->Paginator->sort('order');?></th>
	<th><?php echo $this->Paginator->sort('is_required');?></th>
	<th><?php echo $this->Paginator->sort('is_system');?></th>
	<th><?php echo $this->Paginator->sort('is_visible');?></th>
	<th><?php echo $this->Paginator->sort('is_advancedsearch');?></th>
	<th><?php echo $this->Paginator->sort('is_layered');?></th>
	<th><?php echo $this->Paginator->sort('is_comparable');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($formInputs as $formInput):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $formInput['FormInput']['id']; ?>
		</td>
		<td>
			<?php echo $formInput['FormFieldset']['name']; ?>
		</td>
		<td>
			<?php echo $formInput['FormInput']['code']; ?>
		</td>
		<td>
			<?php echo $formInput['FormInput']['name']; ?>
		</td>
		<td>
			<?php echo $formInput['FormInput']['order']; ?>
		</td>
		<td>
			<?php echo $formInput['FormInput']['is_required']; ?>
		</td>
		<td>
			<?php echo $formInput['FormInput']['is_system']; ?>
		</td>
		<td>
			<?php echo $formInput['FormInput']['is_visible']; ?>
		</td>
		<td>
			<?php echo $formInput['FormInput']['is_advancedsearch']; ?>
		</td>
		<td>
			<?php echo $formInput['FormInput']['is_layered']; ?>
		</td>
		<td>
			<?php echo $formInput['FormInput']['is_comparable']; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $formInput['FormInput']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $formInput['FormInput']['id']), null, sprintf(__('Are you sure you want to delete # %s, and all the data currently saved.', true), $formInput['FormInput']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<?php echo $this->element('paging'); ?>
<?php 
// set the contextual menu items
echo $this->Element('context_menu', array('menus' => array(
	array(
		'heading' => 'Form Inputs',
		'items' => array(
			$this->Html->link(__('New Form Input', true), array('action' => 'add')),
			)
		),
	array(
		'heading' => 'Form Fieldsets',
		'items' => array(
			$this->Html->link(__('New Fieldset', true), array('controller' => 'form_fieldsets', 'action' => 'edit')),
			$this->Html->link(__('List Fieldsets', true), array('controller' => 'form_fieldsets', 'action' => 'index')),
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

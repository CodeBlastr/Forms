<?php
/**
 * Form Admin Index View
 *
 * The view for a list of forms.
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

<div class="forms index">
<h2><?php echo __('Forms');?></h2>
<p> This forms plugin allows you to customize your website database.  Including system tables like project, tickets, contacts ,etc..  To use you should follow same basic form creation practices. </p>
<ol>
	<li>Create the form first - You have to have a container to house the inputs.</li>
    <li>Create fieldsets - Even if it is a simple form you have to create a field set, to contain the inputs into groups.  If you later want to reuse some field sets in a second form this will come in very helpful anyway.  It also allows you to save to multiple database tables from a single form.</li>
    <li>Create inputs - Now that you have a form and the input groups (fieldsets) you can begin adding form inputs into your field sets.</li>
</ol>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('id');?></th>
	<th><?php echo $this->Paginator->sort('name');?></th>
	<th><?php echo $this->Paginator->sort('method');?></th>
	<th><?php echo $this->Paginator->sort('plugin');?></th>
	<th><?php echo $this->Paginator->sort('model');?></th>
	<th><?php echo $this->Paginator->sort('action');?></th>
	<th class="actions"><?php echo __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($forms as $group):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $group['Form']['id']; ?>
		</td>
		<td>
			<?php echo $group['Form']['name']; ?>
		</td>
		<td>
			<?php echo $group['Form']['method']; ?>
		</td>
		<td>
			<?php echo $group['Form']['plugin']; ?>
		</td>
		<td>
			<?php echo $group['Form']['model']; ?>
		</td>
		<td>
			<?php echo $group['Form']['action']; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $group['Form']['id'])); ?>
			<?php echo $this->Html->link(__('View Form Fieldsets', true), array('controller' => 'form_fieldsets', 'action' => 'index', 'fieldset' => $group['Form']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $group['Form']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $group['Form']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<?php echo $this->Element('paging'); ?>
<?php 
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Forms',
		'items' => array(
			$this->Html->link(__('New Form', true), array('action' => 'add')),
			)
		),
	array(
		'heading' => 'Form Fieldsets',
		'items' => array(
			$this->Html->link(__('New Form Fieldset', true), array('controller' => 'form_fieldsets', 'action' => 'edit')),
			$this->Html->link(__('Show Fieldsets', true), array('controller' => 'form_fieldsets', 'action' => 'index')),
			)
		),
	array(
		'heading' => 'Form Inputs',
		'items' => array(
			$this->Html->link(__('New Form Inputs', true), array('controller' => 'form_inputs', 'action' => 'add')),
			$this->Html->link(__('List Form Inputs', true), array('controller' => 'form_inputs', 'action' => 'index')),
			)
		),
	)));
?>

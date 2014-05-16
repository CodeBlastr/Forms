<?php
/**
 * Form Index View
 *
 * The view for a list of forms.
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
 */
?>

<div class="forms index">
<h2><?php echo __('Forms');?></h2>
<p> This forms plugin allows you to customize your website database.  Including system tables like project, tickets, contacts ,etc..</p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('id', 'Template Tag');?></th>
	<th><?php echo $this->Paginator->sort('name');?></th>
	<th><?php echo $this->Paginator->sort('method');?></th>
	<th><?php echo $this->Paginator->sort('action', 'Handler');?></th>
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
			<?php echo __('&#123form: %s&#125;', $group['Form']['id']); ?>
		</td>
		<td>
			<?php echo $group['Form']['name']; ?>
		</td>
		<td>
			<?php echo $group['Form']['method']; ?>
		</td>
		<td>
			<?php echo $group['Form']['plugin'] . '.' . $group['Form']['model'] . '::' . $group['Form']['action'] . '()'; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $group['Form']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $group['Form']['id'])); ?>
			<?php echo $this->Html->link(__('Copy'), array('action' => 'copy', $group['Form']['id'])); ?>
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
			$this->Html->link(__('Add'), array('action' => 'add'), array('class' => 'add')),
			)
		),
	))); ?>

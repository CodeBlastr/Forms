<?php echo $this->Element('forms', array('id' => $id), array('plugin' => 'forms')); ?>

<?php 
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Forms',
		'items' => array(
			$this->Html->link(__('Edit'), array('action' => 'edit', $id), array('class' => 'add')),
			)
		),
	))); ?>
<?php

echo $this->Html->script('http://code.jquery.com/ui/1.10.2/jquery-ui.js', array('inline' => false));
echo $this->Html->script('/forms/js/ZuhaFormBuilder.js', array('inline' => false));

/**
 * Form Admin Edit View
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
 */
?>

<div id="formEditorWindow">
	<form>
		<fieldset>
			<div class="input">
				<label>Test</label>
				<input type="text" />
			</div>
			<div class="input">
				<label>Test 2</label>
				<input type="text" />
			</div>
		</fieldset>
		<fieldset>

			
		</fieldset>
	</form>
</div>


<div class="forms form">
	<?php echo $this->Form->create('Form');?>
	
	<fieldset>
    	<legend class="toggleClick"><?php echo __('Edit %s Settings', $this->request->data['Form']['name']);?></legend>
    	<?php
		echo $this->Form->input('Form.id');
		echo $this->Form->input('Form.name');
		echo $this->Form->input('Form.method');
		echo $this->Form->input('Form.plugin');
		echo $this->Form->input('Form.model', array('placeholder' => 'Camel case model name' ));
		echo $this->Form->input('Form.action', array('placeholder' => 'Ex. add, edit, view, save, remove'));
		echo $this->Form->input('Form.success_message');
		echo $this->Form->input('Form.success_url');
		echo $this->Form->input('Form.fail_message');
		echo $this->Form->input('Form.fail_url');
		echo $this->Form->input('Form.notifiees', array('type' => 'text', 'label' => 'Email(s) to notify of submissions', 'placeholder' => 'Separate emails by commas'));
        ?>
        <fieldset>
          <legend class="toggleClick"><?php echo __('Configure Email Auto-Responder');?></legend>
          <?php
          echo $this->Form->input('Form.response_email', array('type' => 'select')); 
          echo $this->Form->input('Form.response_subject');
          echo $this->Form->input('Form.response_body', array('type' => 'richtext', 'ckeSettings' => null));
          ?>
        </fieldset>
        <?php
        echo $this->Form->end(); 
        ?>
	</fieldset>
</div>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Forms',
		'items' => array(
			$this->Html->link(__('List Forms', true), array('action' => 'index'), array('class' => 'index')),
			)
		),
	array(
		'heading' => 'Form Fieldsets',
		'items' => array(
			$this->Html->link(__('New Fieldset', true), array('controller' => 'form_fieldsets', 'action' => 'add', $this->request->data['Form']['id']), array('class' => 'add')),
			$this->Html->link(__('Show Fieldsets', true), array('controller' => 'form_fieldsets', 'action' => 'index'), array('class' => 'index')),
			)
		),
	array(
		'heading' => 'Form Inputs',
		'items' => array(
			$this->Html->link(__('New Input', true), array('controller' => 'form_inputs', 'action' => 'add', $this->request->data['Form']['id']), array('class' => 'add')),
			$this->Html->link(__('List Inputs', true), array('controller' => 'form_inputs', 'action' => 'index'), array('class' => 'index')),
			)
		),
	))); ?>
	
<script>
	$('#formEditorWindow').zuhaFormBuilder();
</script>

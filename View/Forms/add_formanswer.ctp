<h1><?php echo __('Create a Q&A Form'); ?></h1>
<?php
echo $this->Form->create('Form');
echo $this->Form->input('Form.id');
echo $this->Form->input('Form.name');
echo $this->Form->hidden('Form.method', array('value' => 'file'));
echo $this->Form->hidden('Form.action', array('value' => 'record'));
echo $this->Form->hidden('Form.plugin', array('value' => 'Forms'));
echo $this->Form->hidden('Form.model', array('value' => 'FormAnswer'));
echo $this->Form->hidden('Form.foreign_model', array('value' => $foreignModel)); // This form belongsTo.  Likely another record, like a Course, that is owned by a User.
echo $this->Form->hidden('Form.foreign_key', array('value' => $foreignKey));
echo $this->Form->hidden('Form.success_message', array('value' => 'Thank you'));
echo $this->Form->hidden('Form.success_url', array('value' => '/'));
echo $this->Form->hidden('Form.fail_message', array('value' => 'Unable to save. Try again.'));
echo $this->Form->hidden('Form.fail_url', array('value' => '/'));
echo $this->Form->input('Form.notifiees', array('type' => 'text', 'label' => 'Email(s) to notify of submissions', 'placeholder' => 'Separate emails by commas'));
echo $this->Form->submit();
echo $this->Form->end();
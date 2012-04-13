<?php
$multiple = !empty($input['multiple']) ? array('multiple' => $input['multiple']) : array();
$optionValues = !empty($input['option_values']) ? preg_split('/[\n\r]+/', $input['option_values']) : array();
$optionNames = !empty($input['option_names']) ? preg_split('/[\n\r]+/', $input['option_names']) : array();
$options = !empty($optionValues) && !empty($optionNames) ? array_combine($optionValues, $optionNames) : array();
$model = !empty($input['model_override']) ? $input['model_override'] : $fieldset['model'];
$ckeSettings = $input['input_type'] == 'richtext' ? array('buttons' => array('Bold','Italic','Underline','FontSize','TextColor','BGColor','-','NumberedList','BulletedList','Blockquote','JustifyLeft','JustifyCenter','JustifyRight','-','Link','Unlink','-', 'Image')) : null;
$empty = !empty($input['empty_text']) ? array('empty' => $input['empty_text']) : array();
$separator = !empty($input['separator']) ? array('separator' => $input['separator']) : array();
$legend = !empty($input['legend']) ? array('legend' => $input['legend']) : array();
$timeFormat = !empty($input['time_format']) ? array('time_format' => $input['time_format']) : array();
$dateFormat = !empty($input['date_format']) ? array('date_format' => $input['date_format']) : array();
$divId = !empty($input['div_id']) ? array('id' => $input['div_id']) : array();
$divClass = !empty($input['div_class']) ? array('class' => $input['div_class']) : array();
$label = $input['show_label'] == 1 ? $input['name'] : false;
$before = !empty($preview) ? __('<span class="editFormSpan">%s</span> %s', $this->Html->link('Edit', array('plugin' => 'forms', 'controller' => 'forms', 'action' => 'edit', $input['form_id'], $input['id']), array('class' => 'editFormInput', 'title' => 'Edit')), $input['before']) : $input['before'];

if(!empty($divClass) && !empty($divId)) {
  $divOptions = array_merge($divId, $divClass);
} else {
  if(empty($divClass)) $divOptions = $divId;
  elseif(empty ($divId)) $divOptions = $divClass;
}
if(empty($divOptions)) $divOptions = null;
$isRequired = !empty($input['is_required']) ? 'required' : null;
$validationType = !empty($input['validation']) ? $input['validation'] : null;
# special field values
if (!empty($input['system_default_value'])) {
	if ($input['system_default_value'] == 'current user') {
		$defaultValue = $groups['user_id'];	
	} else if ($input['system_default_value'] == 'current page url') {
		if (!empty($_SERVER['HTTP_HOST']) && !empty($_SERVER['REQUEST_URI'])) {
			$defaultValue = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		} else {
			$defaultValue = Router::url($this->here, true);
		}
	} else {
		$defaultValue = $input['default_value'];
	}
} else {
	$defaultValue = $input['default_value'];
}
$options = array_merge(array(
	'type' => $input['input_type'],
	'label' => $label,
	'default' => $defaultValue,
	'selected' => $defaultValue,
	'options' => $options,
	'before' => $before,
	'between' => $input['between'],
	'after' => $input['after'],
	'minLength' => $input['min_length'],
	'maxLength' => $input['max_length'],
	'rows' => $input['rows'],
	'cols' => $input['columns'],
	'minYear' => $input['min_year'],
	'maxYear' => $input['max_year'],
	'interval' => $input['minute_interval'],
	//'div' =>  $divOptions,
	'ckeSettings' => $ckeSettings,
	'placeholder' => $input['placeholder'],
	'hiddenField' => false, // this was needed to make checkbox validation work because of name conflicts
	'class' => $isRequired.' '.$validationType,
	), $multiple, $separator, $legend, $empty, $timeFormat, $dateFormat);
if($divOptions !== null) $options['div'] = $divOptions;

echo $this->Form->input($model.'.'.$input['code'], $options); ?>
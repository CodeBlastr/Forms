<?php
/**
 * Forms Element
 *
 * Displays the form inputs or values for the add, edit, and view request versions. (please note the variable names are purposely not $form so as not to conflict with the form helper.
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
 * @subpackage    zuha.app.plugins.forms.views.elements
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 * @todo		  This might need to be broken up into 3 different views, so that adding new views of the element is easier.
 */
?>

<div id="formsForm">
<?php
#call the form display function using the form id, and the type in this format : x/type (ex. 4/view, or 17/add).
$groups = $this->requestAction('/forms/forms/display/'.$id);





#display the view type
if (strpos($id, 'view') > -1) {
	
	#get the model from the controller
	$model = Inflector::classify($this->params['controller']);
	
	foreach ($groups['FormFieldset'] as $fieldset) {  
		echo (!empty($fieldset['legend']) ? '<h4>'.$fieldset['legend'].'</h4>' : ''); ?>
      	<ul>
	    <?php
		foreach ($fieldset['FormInput'] as $input) {
			echo '<li><b>'.$input['name'].'</b> : '.$user[$model][$input['code']].'</li>';
		}
		?>
        </ul>
      <?php 
	}
	
	
	
	
	
	
	
	
#display the edit type
} else if (strpos($id, 'edit') > -1) {
	
		
# initialize the form open tag
echo $form->create('Form', array('url' => '/forms/forms/process', 'type' => $groups['Form']['method']));
/*echo $form->create($groups['Form']['model'], array(
				'url' => $groups['Form']['url'],
				'method' => $groups['Form']['method'],
				));*/
echo $form->input('Form.plugin', array('type' => 'hidden', 'value' => $groups['Form']['plugin'])); 
echo $form->input('Form.model', array('type' => 'hidden', 'value' => $groups['Form']['model'])); 
echo $form->input('Form.action', array('type' => 'hidden', 'value' => $groups['Form']['action'])); 
echo $form->input('Form.success_message', array('type' => 'hidden', 'value' => $groups['Form']['success_message'])); 
echo $form->input('Form.success_url', array('type' => 'hidden', 'value' => $groups['Form']['success_url'])); 
echo $form->input('Form.fail_message', array('type' => 'hidden', 'value' => $groups['Form']['fail_message'])); 
echo $form->input('Form.fail_url', array('type' => 'hidden', 'value' => $groups['Form']['fail_url'])); 

foreach ($groups['FormFieldset'] as $fieldset) {  
?>
	<fieldset>
  <?php echo (!empty($fieldset['legend']) ? '<legend>'.$fieldset['legend'].'</legend>' : ''); ?>
    <?php
	foreach ($fieldset['FormInput'] as $input) {
		$multiple = (!empty($input['multiple']) ? $input['multiple'] : null);
		$optionValues = (!empty($input['option_values']) ? preg_split('/[\n\r]+/', $input['option_values']) : null );
		$optionNames = (!empty($input['option_names']) ? preg_split('/[\n\r]+/', $input['option_names']) : null );
		$options = (!empty($optionValues) && !empty($optionNames) ? array_combine($optionValues, $optionNames) : null);
		$model = (!empty($input['model_override']) ? $input['model_override'] : $fieldset['model']);
		$ckeSettings = ($input['input_type'] == 'richtext' ? array('buttons' => array('Bold','Italic','Underline','FontSize','TextColor','BGColor','-','NumberedList','BulletedList','Blockquote','JustifyLeft','JustifyCenter','JustifyRight','-','Link','Unlink','-', 'Image')) : null);
		# special field values
		if (!empty($input['system_default_value'])) {
			if ($input['system_default_value'] == 'current user') {
				$defaultValue = $groups['user_id'];
			} else {
				$defaultValue = $input['default_value'];
			}
		} else {
			$defaultValue = $input['default_value'];
		}
		echo $form->input($model.'.'.$input['code'], array(
				'type' => $input['input_type'],
				'label' => $input['name'],
				'default' => $defaultValue,
				'selected' => $defaultValue,
				'options' => $options,
				'before' => $input['before'],
				'between' => $input['between'],
				'separator' => $input['separator'],
				'after' => $input['after'],
				'multiple' => $input['multiple'],
				'maxLength' => $input['max_length'],
				'legend' => $input['legend'],
				'rows' => $input['rows'],
				'cols' => $input['columns'],
				'empty' => $input['empty_text'],
				'time_format' => $input['time_format'],
				'date_format' => $input['date_format'],
				'minYear' => $input['min_year'],
				'maxYear' => $input['max_year'],
				'interval' => $input['minute_interval'],
				'div' => array('id' => $input['div_id'], 'div_class' => $input['div_class']),
				'ckeSettings' => $ckeSettings,
				)); 
	} 
?>
  </fieldset>
  <?php
}
#close the form and show the submit button
echo $form->end('Submit');
	
	
	
	
	
	
	
	
	
	
	
	
#display the add type by default
} else {

	# get rid of variables which should never be shown on redirect
	$this->Session->delete('User.password');
	$this->Session->delete('User.confirm_password');
	# set the form variables in the case of an error
	$form->data = $this->Session->read();
	$form->validationErrors = $this->Session->read('errors');
		
	# initialize the form open tag
	echo $form->create('Form', array('url' => '/forms/forms/process', 'type' => $groups['Form']['method'], 'id' => 'addForm'));
	echo $form->input('Form.plugin', array('type' => 'hidden', 'value' => $groups['Form']['plugin'])); 
	echo $form->input('Form.model', array('type' => 'hidden', 'value' => $groups['Form']['model'])); 
	echo $form->input('Form.action', array('type' => 'hidden', 'value' => $groups['Form']['action'])); 
	echo $form->input('Form.success_message', array('type' => 'hidden', 'value' => $groups['Form']['success_message'])); 
	echo $form->input('Form.success_url', array('type' => 'hidden', 'value' => $groups['Form']['success_url'])); 
	echo $form->input('Form.fail_message', array('type' => 'hidden', 'value' => $groups['Form']['fail_message'])); 
	echo $form->input('Form.fail_url', array('type' => 'hidden', 'value' => $groups['Form']['fail_url'])); 
	
	foreach ($groups['FormFieldset'] as $fieldset) {  
	?>

	<fieldset id="fieldset<?php echo $fieldset['id']; ?>">
	<?php echo (!empty($fieldset['legend']) ? '<legend>'.$fieldset['legend'].'</legend>' : ''); ?>
    <?php
		$i = 0;
		foreach ($fieldset['FormInput'] as $input) {
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
			//$divOptions = array_merge($divId, $divClass);
			$isRequired = !empty($input['is_required']) ? 'required' : null;
			$validationType = !empty($input['validation']) ? $input['validation'] : null;
			# special field values
			if (!empty($input['system_default_value'])) {
				if ($input['system_default_value'] == 'current user') {
					$defaultValue = $groups['user_id'];
				} else {
					$defaultValue = $input['default_value'];
				}
			} else {
				$defaultValue = $input['default_value'];
			}
			$options = array_merge(array(
				'type' => $input['input_type'],
				'label' => $input['name'],
				'default' => $defaultValue,
				'selected' => $defaultValue,
				'options' => $options,
				'before' => $input['before'],
				'between' => $input['between'],
				'after' => $input['after'],
				'minLength' => $input['min_length'],
				'maxLength' => $input['max_length'],
				'rows' => $input['rows'],
				'cols' => $input['columns'],
				'minYear' => $input['min_year'],
				'maxYear' => $input['max_year'],
				'interval' => $input['minute_interval'],
				//'div' => $divOptions,  // removed because nothing uses it yet, and it was causing an error I didn't have time to debug
				'ckeSettings' => $ckeSettings,
				'hiddenField' => false, // this was needed to make checkbox validation work because of name conflicts
				'class' => $isRequired.' '.$validationType,
				), $multiple, $separator, $legend, $empty, $timeFormat, $dateFormat);
			echo $form->input($model.'.'.$input['code'], $options); 
		} 
		?>
		</fieldset>
		<?php
	}
	#close the form and show the submit button
	echo $form->end('Submit');
} // end the else which display the add type of form
?>
</div>

<script src="/js/system/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">
$().ready(function() {	
		
	$("#addForm").validate();
});
</script>
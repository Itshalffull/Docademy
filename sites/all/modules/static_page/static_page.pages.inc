<?php

/**
 * @file
 * static_page config form.
 */
function static_page_config_form(){
  $options = array();
  $types = node_type_get_types();	
  foreach ($types as $key => $values) {
    $options[$key] = $values->name;
  }
  $default_types = variable_get('static_page_types', array());
  $form['static_page_types'] = array(
    '#title' => t('Content types used as static page'),
    '#type' =>'checkboxes',
    '#description' =>t('All nodes of these selected types will be used as static page.'),
    '#options' => $options,
	'#default_value' => $default_types,
  );
  //return system_settings_form($form, TRUE);
  
  $form['submit'] = array(
    '#type' =>'submit',
    '#value' => t('Submit'),
  );
  return $form;
  
}

function static_page_config_form_submit($form, &$form_state){
  $current_types = array_filter($form_state['values']['static_page_types']);
  variable_set('static_page_types', $current_types);
  drupal_set_message(t('Your setting has been saved.'));
}



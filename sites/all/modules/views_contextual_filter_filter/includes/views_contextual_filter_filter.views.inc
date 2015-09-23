<?php

/**
 * Implements hook_views_handlers().
 */
function views_contextual_filter_filter_views_handlers() {
  return array(
    'info' => array(
      'path' => drupal_get_path('module', 'views_contextual_filter_filter') . '/includes',
    ),
    'handlers' => array(
      // register our custom filter, with the class/file name and parent class
      'contextual_filter_filter' => array(
        'parent' => 'views_handler_filter',
      ),
      'contextual_filter_filter_boolean_operator' => array(
        'parent' => 'views_handler_filter_boolean_operator',
      ),
      'contextual_filter_filter_boolean_operator_string' => array(
        'parent' => 'views_handler_filter_boolean_operator_string',
      ),
      'contextual_filter_filter_combine' => array(
        'parent' => 'views_handler_filter_combine',
      ),
      'contextual_filter_filter_date' => array(
        'parent' => 'views_handler_filter_date',
      ),
      'contextual_filter_filter_entity_bundle' => array(
        'parent' => 'views_handler_filter_entity_bundle',
      ),
      'contextual_filter_filter_equality' => array(
        'parent' => 'views_handler_filter_equality',
      ),
      'contextual_filter_filter_fields_compare' => array(
        'parent' => 'views_handler_filter_fields_compare',
      ),
      'contextual_filter_filter_group_by_numeric' => array(
        'parent' => 'views_handler_filter_group_by_numeric',
      ),
      'contextual_filter_filter_in_operator' => array(
        'parent' => 'views_handler_filter_in_operator',
      ),
      'contextual_filter_filter_many_to_one' => array(
        'parent' => 'views_handler_filter_many_to_one',
      ),
      'contextual_filter_filter_numeric' => array(
        'parent' => 'views_handler_filter_numeric',
      ),
      'contextual_filter_filter_string' => array(
        'parent' => 'views_handler_filter_string',
      ),
    ),
  );
}

/**
 * Implements hook_views_data().
 */
function views_contextual_filter_filter_views_data() {
  return array(
    'node' => array(
      'views_contextual_filter_filter' => array(
        'group' => t('Views Contextual Filter Filter'),
        'title' => t('Contextual Filter Filter'),
        'help' => t('Filter the View by a contextual filter value'),
        'filter' => array(
          'handler' => 'contextual_filter_filter',
        ),
      ),
    ),
  );
}

/**
 * Implements hook_views_data_alter().
 *
 * Change the views_handler_filter_BLAH into contextual_filter_filter_BLAH.
 */
function views_contextual_filter_filter_views_data_alter(&$data) {
  dvm(__FUNCTION__);
  $filters = array(
    'views_handler_filter' => 'contextual_filter_filter',
    'views_handler_filter_boolean_operator' => 'contextual_filter_filter_boolean_operator',
    'views_handler_filter_boolean_operator_string' => 'contextual_filter_filter_boolean_operator_string',
    'views_handler_filter_date' => 'contextual_filter_filter_date',
    'views_handler_filter_equality' => 'contextual_filter_filter_equality',
    'views_handler_filter_numeric' => 'contextual_filter_filter_numeric',
    'views_handler_filter_string' => 'contextual_filter_filter_string',
  );

  // change node views filters
  foreach ($data['node'] as $item => $details) {
    if (isset($data['node'][$item]['filter']['handler'])) {
      if (isset($filters[$data['node'][$item]['filter']['handler']])) {
        $data['node'][$item]['filter']['handler'] = $filters[$data['node'][$item]['filter']['handler']];
      }
    }
  }

  $keys = array_keys($data);
  $field_keys = array_filter($keys, 'views_contextual_filter_filter_filter_field_keys');
  foreach ($field_keys as $field) {
    foreach ($data[$field] as $key => $value) {
      if (isset($value['filter']) && isset($filters[$data[$field][$key]['filter']['handler']])) {
        $data[$field][$key]['filter']['handler'] = $filters[$data[$field][$key]['filter']['handler']];
      }
    }
  }
}

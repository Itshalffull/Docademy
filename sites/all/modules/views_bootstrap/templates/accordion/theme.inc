<?php

/**
 * Implementation of template preprocess for the view.
 */
function template_preprocess_views_bootstrap_accordion_plugin_style(&$vars) {
  $view = &$vars['view'];
  $title_field = $vars['options']['title_field'];

  $vars['classes_array'][] = 'panel-group';

  // Get titles.
  if (isset($view->field[$title_field])) {
    foreach ($vars['view']->result as $key => $field) {
      $view->field[$title_field]->advanced_render($field);
      $vars['titles'][$key] = $view->field[$title_field]->last_render_text;
    }
  }
}

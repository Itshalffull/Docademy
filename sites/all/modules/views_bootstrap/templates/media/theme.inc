<?php

/**
 * Implementation of template preprocess for the view.
 */
function template_preprocess_views_bootstrap_media_plugin_style(&$vars) {
  $view = &$vars['view'];

  $image_field = $vars['options']['image_field'];
  $heading_field = $vars['options']['heading_field'];
  $body_field = $vars['options']['body_field'];

  $vars['items'] = array();

  foreach ($view->result as $field) {
    $vars['items'][] = array(
      'image_field' => isset($view->field[$image_field]) ? $view->field[$image_field]->advanced_render($field) : NULL,
      'heading_field' => isset($view->field[$heading_field]) ? $view->field[$heading_field]->advanced_render($field) : NULL,
      'body_field' => isset($view->field[$body_field]) ? $view->field[$body_field]->advanced_render($field) : NULL,
    );
  }
}

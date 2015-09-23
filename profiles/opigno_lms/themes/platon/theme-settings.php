<?php

/**
 * @file
 * Defines the theme settings logic.
 */

/**
 * Implements hook_form_FORM_ID_alter() for system_theme_settings().
 */
function platon_form_system_theme_settings_alter(&$form, $form_state) {
  // Deactivate irrelevant settings.
  foreach (array('toggle_name', 'toggle_slogan', 'toggle_favicon', 'toggle_secondary_menu') as $option) {
    $form['theme_settings'][$option]['#access'] = FALSE;
  }

  $form['platon_home_page_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t("Homepage settings"),
  );

  $form['platon_home_page_settings']['platon_use_home_page_markup'] = array(
    '#type' => 'checkbox',
    '#title' => t("Use a different homepage for anonymous users."),
    '#description' => t("Check here if you want the theme to use a custom page for users that are not logged in."),
    '#default_value' => theme_get_setting('platon_use_home_page_markup'),
  );

  $settings = theme_get_setting('platon_home_page_markup');
  $form['platon_home_page_settings']['platon_home_page_markup_wrapper'] = array(
    '#type' => 'fieldset',
    '#states' => array(
      'invisible' => array(
        'input[name="platon_use_home_page_markup"]' => array('checked' => FALSE),
      ),
    ),
    'platon_home_page_markup' => array(
      '#type' => 'text_format',
      '#base_type' => 'textarea',
      '#title' => t("Home page content"),
      '#description' => t("Set the content for the home page. This will be used for users that are not logged in."),
      '#format' => !empty($settings['format']) ? $settings['format'] : filter_default_format(),
      '#default_value' => !empty($settings['value']) ? $settings['value'] : '',
    ),
  );

  $form['platon_home_page_settings']['platon_use_home_page_background'] = array(
    '#type' => 'checkbox',
    '#title' => t("Use an image for the home page background"),
    '#description' => t("Check here if you want the theme to use a custom image for the homepage background."),
    '#default_value' => theme_get_setting('platon_use_home_page_background'),
  );

  $form['platon_home_page_settings']['platon_home_page_image_path'] = array(
    '#type' => 'textfield',
    '#title' => t("The path to the home page background image."),
    '#description' => t("The path to the image file you would like to use as your custom home page background (relative to sites/default/files)."),
    '#default_value' => theme_get_setting('platon_home_page_image_path'),
    '#states' => array(
      'invisible' => array(
       'input[name="platon_use_home_page_background"]' => array('checked' => FALSE),
      ),
    ),
  );

  $form['platon_home_page_settings']['platon_home_page_image_upload'] = array(
    '#type' => 'file',
    '#title' => t("Upload an image"),
    '#description' => t("If you don't have direct file access to the server, use this field to upload your background image icon."),
    '#states' => array(
      'invisible' => array(
       'input[name="platon_use_home_page_background"]' => array('checked' => FALSE),
      ),
    ),
  );

  $form['platon_menu_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t("Menu settings"),
  );

  $form['platon_menu_settings']['platon_menu_source'] = array(
    '#type' => 'select',
    '#title' => t("Main menu source"),
    '#options' => array(0 => t("None")) + menu_get_menus(),
    '#description' => t("The menu source to use for the tile navigation. If 'none', Platon will use a default list of tiles."),
    '#default_value' => theme_get_setting('platon_menu_source'),
  );

  $form['platon_menu_settings']['platon_menu_show_for_anonymous'] = array(
    '#type' => 'checkbox',
    '#title' => t("Show menu for anonymous users"),
    '#description' => t("Show the main menu for users that are not logged in. Only links that users have access to will show up."),
    '#default_value' => theme_get_setting('platon_menu_show_for_anonymous'),
  );

  $form['#validate'][] = 'platon_form_system_theme_settings_alter_validate';
  if (empty($form['#submit'])) {
    $form['#submit'] = array();
  }
  array_unshift($form['#submit'], 'platon_form_system_theme_settings_alter_submit');
}

/**
 * Validation callback for platon_form_system_theme_settings_alter().
 */
function platon_form_system_theme_settings_alter_validate($form, &$form_state) {
  if (!empty($_FILES['files']['name']['platon_home_page_image_upload'])) {
    $file = file_save_upload('platon_home_page_image_upload', array(
      'file_validate_is_image' => array(),
      'file_validate_extensions' => array('png gif jpg jpeg'),
    ), 'public://');

    if ($file) {
      $form_state['storage']['file'] = $file;
    }
    else {
      form_set_error('platon_home_page_image_upload', t("Couldn't upload file."));
    }
  }
}

/**
 * Submission callback for platon_form_system_theme_settings_alter().
 */
function platon_form_system_theme_settings_alter_submit($form, &$form_state) {
  if (isset($form_state['storage']['file'])) {
    $form_state['values']['platon_home_page_image_path'] = str_replace('public://', '', $form_state['storage']['file']->uri);
  }
}

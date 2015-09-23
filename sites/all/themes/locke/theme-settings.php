<?php
/**
 * @file
 * Theme settings.
 */

/**
 * Implementation of hook_form_system_theme_settings_alter() 
 */
 
function locke_form_system_theme_settings_alter(&$form, &$form_state) {
  // Ensure this include file is loaded when the form is rebuilt from the cache.
  $form_state['build_info']['files']['form'] = drupal_get_path('theme', 'locke') . '/theme-settings.php';

  // Add theme settings here.
  $form['locke_theme_settings'] = array(
    '#title' => t('Theme Settings'),
    '#type' => 'fieldset',
  );

  // Copyright.
  $copyright = theme_get_setting('copyright');
  $form['locke_theme_settings']['copyright'] = array(
    '#title' => t('Copyright'),
    '#type' => 'text_format',
    '#format' => $copyright['format'],
    '#default_value' => $copyright['value'] ? $copyright['value'] : t('Drupal is a registered trademark of Dries Buytaert.'),
  );
  
  // Update the "Toogle Display" to something clearer
  $form['theme_settings']['#title'] = t('Theme Display Settings');

  // Change Weighting of Default Setting Fields
  $form['theme_settings']['#weight'] = 20;
  $form['logo']['#weight'] = 30;
  $form['favicon']['#weight'] = 40;

  // Define the contact information fieldset
  $form['contact_information'] = array(
    '#type' => 'fieldset',
    '#title' => t('Contact Information in Footer'),
  );

  // Define the phone number
  $form['contact_information']['phone'] = array(
    '#type' => 'textfield',
    '#title' => t('Phone Number'),
    '#default_value' => theme_get_setting('phone'),
  );

  // Define your fax number
  $form['contact_information']['fax'] = array(
    '#type' => 'textfield',
    '#title' => t('Fax Number'),
    '#default_value' => theme_get_setting('fax'),
  );

  // Define your address
  $form['contact_information']['address'] = array(
    '#type' => 'textarea',
    '#title' => t('Address'),
    '#rows' => 3,
    '#default_value' => theme_get_setting('address'),
  );

  // Define the social media links
  $form['social_media'] = array(
    '#type' => 'fieldset',
    '#title' => t('Social Media Links in Footer'),
  );

  // Define the twitter link
  $form['social_media']['twitter_link'] = array(
    '#type' => 'textfield',
    '#title' => 'Twitter',
    '#default_value' => theme_get_setting('twitter_link'),
  );

  // Define the facebook link
  $form['social_media']['facebook_link'] = array(
    '#type' => 'textfield',
    '#title' => 'Facebook',
    '#default_value' => theme_get_setting('facebook_link'),
  );

  // Define the youtube link
  $form['social_media']['youtube_link'] = array(
    '#type' => 'textfield',
    '#title' => 'YouTube',
    '#default_value' => theme_get_setting('youtube_link'),
  );

  // Update the image settings to include seal
  $form['logo']['#title'] = 'Image settings';
  $default_seal = theme_get_setting('default_seal');
  $form['logo']['default_seal'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use the default seal'), 
    '#tree' => FALSE,
    '#description' => t('Check here if you want the theme to use the seal supplied with it'),
    '#default_value' => empty($default_seal) ? 0 : 1,
  );
  $seal_path = theme_get_setting('seal_path');
  // If $seal_path is a public:// URI, display the path relative to the files
  // directory; stream wrappers are not end-user friendly.
  if (file_uri_scheme($seal_path) == 'public') {
    $seal_path = file_uri_target($seal_path);
  }
  $form['logo']['seal_settings'] = array(
    '#type' => 'container',
    '#states' => array(
      'invisible' => array(
        'input[name="default_seal"]' => array(
          'checked' => TRUE,
        ),
      ),
    ),
    'seal_path' => array(
      '#type' => 'textfield',
      '#title' => 'Path to custom seal',
      '#description' => 'The path to the file you would like to use as your seal file instead of the default seal.',
      '#default_value' => $seal_path,
    ),
    'seal_upload' => array(
      '#type' => 'file',
      '#title' => 'Upload logo image',
      '#maxlength' => 40,
      '#description' => 'If you don\'t have direct file access to the server, use this field to upload your seal.',
    ),
  );

  $form['#submit'][] = 'locke_theme_settings_submit';
  $form['#validate'][] = 'locke_theme_settings_validate';
  
}

/**
 * Custom Validation Hook
 */
function locke_theme_settings_validate($form, &$form_state) {
  // Handle file uploads.
  $validators = array('file_validate_is_image' => array());

  // Check for a new uploaded logo.
  $file = file_save_upload('seal_upload', $validators);
  if (isset($file)) {
    // File upload was attempted.
    if ($file) {
      // Put the temporary file in form_values so we can save it on submit.
      $form_state['values']['seal_upload'] = $file;
    }
    else {
      // File upload failed.
      form_set_error('seal_upload', t('The seal could not be uploaded.'));
    }
  }

  // If the user provided a path for a seal file, make sure a file
  // exists at that path.
  if ($form_state['values']['seal_path']) {
    $path = _system_theme_settings_validate_path($form_state['values']['seal_path']);
    if (!$path) {
      form_set_error('seal_path', t('The custom seal path is invalid.'));
    }
  }
}

/**
 * Custom Submit Hook
 */
function locke_theme_settings_submit($form, &$form_state) {
  $values = $form_state['values'];

  // If the user uploaded a new seal, save it to a permanent location
  // and use it in place of the default theme-provided file.
  if ($file = $values['seal_upload']) {
    unset($values['seal_upload']);
    $filename = file_unmanaged_copy($file->uri);
    $values['default_seal'] = 0;
    $values['seal_path'] = $filename;
    $values['toggle_seal'] = 1;
  }

  // If the user entered a path relative to the system files directory for
  // a seal, store a public:// URI so the theme system can handle it.
  if (!empty($values['seal_path'])) {
    $values['seal_path'] = _system_theme_settings_validate_path($values['seal_path']);
  }

  // Save the values to $form_state
  if (!empty($values['seal_path'])) {
    $form_state['values']['seal_path'] = $values['seal_path'];
  }
  if (!empty($values['toggle_seal'])) {
    $form_state['values']['toggle_seal'] = $values['toggle_seal'];
  }
  $form_state['values']['default_seal'] = $values['default_seal'];
}

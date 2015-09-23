<?php
/**
 * @file
 * Theme functions
 */

require_once dirname(__FILE__) . '/includes/structure.inc';
require_once dirname(__FILE__) . '/includes/form.inc';
require_once dirname(__FILE__) . '/includes/menu.inc';
require_once dirname(__FILE__) . '/includes/comment.inc';
require_once dirname(__FILE__) . '/includes/node.inc';

/**
 * Implements hook_css_alter().
 */
function locke_css_alter(&$css) {
  $radix_path = drupal_get_path('theme', 'radix');

  // Radix now includes compiled stylesheets for demo purposes.
  // We remove these from our subtheme since they are already included 
  // in compass_radix.
  unset($css[$radix_path . '/assets/stylesheets/radix-style.css']);
  unset($css[$radix_path . '/assets/stylesheets/radix-print.css']);
}

/**
 * Implements template_preprocess_page().
 */
function locke_preprocess_page(&$variables, $hook) {
  // Add copyright to theme.
  if ($copyright = theme_get_setting('copyright')) {
    $variables['copyright'] = check_markup($copyright['value'], $copyright['format']);
  }

  // Setup the search box functionality
  $search_box_form = drupal_get_form('search_form');
  $search_box_form['basic']['keys']['#title'] = '';
  $search_box_form['basic']['keys']['#attributes'] = array('placeholder' => 'Search');
  $search_box_form['basic']['submit']['#value'] = t('Go');
  $search_box = drupal_render($search_box_form);
  $variables['search_box'] = (user_access('search content')) ? $search_box : NULL;

  // Setup the contact information
  $variables['contact_information']['site_name'] = variable_get('site_name');
  $variables['contact_information']['address'] = _filter_autop(theme_get_setting('address'));
  $variables['contact_information']['phone'] = theme_get_setting('phone');
  $variables['contact_information']['fax'] = theme_get_setting('fax');

  // Setup the social links
  $social_links = array();
  if (module_exists('openacademy_news')) {
    $social_links[] = l('Read', 'rss.xml', array('attributes' => array('class' => 'rss-link')));
  }
  if ($twitter_link = theme_get_setting('twitter_link')) {
    $social_links[] = l('Follow', $twitter_link, array('attributes' => array('class' => 'twitter-link')));
  }
  if ($youtube_link = theme_get_setting('youtube_link')) {
    $social_links[] = l('Watch', $youtube_link, array('attributes' => array('class' => 'youtube-link')));
  }
  if ($facebook_link = theme_get_setting('facebook_link')) {
    $social_links[] = l('Friend', $facebook_link, array('attributes' => array('class' => 'facebook-link')));
  }
  $variables['social_links'] = theme('item_list', array('items' => $social_links));

  // Define the university seal 
  if (theme_get_setting('seal_path') && !theme_get_setting('default_seal')) {
    $seal_path = theme_get_setting('seal_path');
    $variables['seal'] = file_create_url($seal_path);
  }
  else {
    $variables['seal'] = '/' . path_to_theme() . '/assets/images/seal.png';
  }
}
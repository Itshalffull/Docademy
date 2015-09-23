<?php

/**
 * @file
 * Template overrides as well as (pre-)process and alter hooks for the
 * Platon theme.
 */

/**
 * Implements hook_theme().
 */
function platon_theme() {
  return array(
    'platon__main_navigation__item' => array(
      'variables' => array('item' => NULL),
      'template' => 'templates/platon--main-navigation--item',
    ),
    'platon__main_navigation__row' => array(
      'variables' => array('items' => array()),
      'template' => 'templates/platon--main-navigation--row',
    ),
    'platon__message' => array(
      'variables' => array('type' => 'status', 'heading' => NULL, 'messages' => array()),
      'template' => 'templates/platon--message',
    ),
    'platon__app_list' => array(
      'variables' => array('items' => NULL),
      'template' => 'templates/platon--app-list',
    ),
  );
}

/**
 * Implements hook_theme_registry_alter().
 */
function platon_theme_registry_alter(&$registry) {
  $path = drupal_get_path('theme', 'platon');
  $registry['opigno_tool']['template'] = "$path/templates/opigno--tool";
  $registry['opigno_tools']['template'] = "$path/templates/opigno--tools";
  $registry['opigno_tool']['theme path'] = $registry['opigno_tools']['theme path'] = $path;
}

/**
 * Implements hook_preprocess_html().
 */
function platon_preprocess_html(&$vars) {
  if (theme_get_setting('platon_use_home_page_background') && $vars['is_front']) {
    $vars['classes_array'][] = 'platon-use-custom-background';
    $vars['attributes_array']['style'] = 'background-image: url("' . file_create_url('public://' . theme_get_setting('platon_home_page_image_path')) . '")';
  }
}

/**
 * Implements hook_preprocess_page().
 */
function platon_preprocess_page(&$vars) {
  drupal_add_library('system', 'jquery.cookie');

  // Set default value.
  $vars['is_og_node'] = FALSE;

  // Add the search form to the page.
  if (module_exists('search') && user_access('search content')) {
    $vars['search_form'] = drupal_get_form('search_form');
  }

  // Flag if we can show the "register" link.
  $register_setting = variable_get('user_register', USER_REGISTER_ADMINISTRATORS_ONLY);
  $vars['can_register'] = $register_setting !== USER_REGISTER_ADMINISTRATORS_ONLY;

  // Render the main navigation.
  $vars['main_navigation'] = _platon_get_main_navigation();

  // Create the OG context tabs.
  if (module_exists('og_context')) {
    $group = og_context('node');
    if (!empty($group['gid'])) {
      $group_title = platon_get_group_title($group['gid']);
      $tabs = array();

      foreach (array(
        "node/{$group['gid']}" => array(
          'title' => t("Home"),
          'class' => 'platon-og-context-view-tab platon-og-context-home-tab',
        ),
        "node/{$group['gid']}/edit" => array(
          'class' => 'platon-og-context-view-tab platon-og-context-settings-tab',
        ),
        "node/{$group['gid']}/group" => array(
          'class' => 'platon-og-context-view-tab platon-og-context-users-tab',
        ),
        "node/{$group['gid']}/tools" => array(
          'class' => 'platon-og-context-view-tab platon-og-context-tools-tab',
        ),
      ) as $path => $override) {
        $link = menu_get_item($path);
        if (!empty($link) && $link['access']) {
          if (!empty($override['title'])) {
            $link['title'] = $override['title'];
          }
          if (!empty($override['class'])) {
            $link['options']['attributes']['class'][] = $link['localized_options']['attributes']['class'][] = $override['class'];
          }
          $tabs[] = array(
            '#theme' => 'menu_local_task',
            '#link' => $link,
            '#active' => TRUE,
          );
        }
      }

      if (!empty($tabs)) {
        $vars['og_context_navigation'] = render($tabs);
      }

      $vars['og_context_parent_link'] = l($group_title, "node/{$group['gid']}");

      if (isset($vars['node']) && $vars['node']->nid == $group['gid']) {
        // $vars['hide_tabs'] = TRUE;
        $vars['is_og_node'] = TRUE;
      }
    }
  }

  // Show the number of unread messages.
  if (function_exists('privatemsg_unread_count')) {
    global $user;
    $unread = privatemsg_unread_count($user);
    drupal_add_js(array('platon' => array('unreadMessages' => $unread)), 'setting');
  }

  // Use a custom markup for the front page if anonymous ?
  if (empty($vars['user']->uid) && $vars['is_front'] && theme_get_setting('platon_use_home_page_markup')) {
    $setting = theme_get_setting('platon_home_page_markup');

    if (!empty($setting['value'])) {
      $value = check_markup($setting['value'], $setting['format']);
      if (!empty($value)) {
        drupal_set_title($vars['site_name']);
        $vars['page']['content'] = $value;
      }
    }
  }
}

/**
 * Implements theme_status_messages().
 * Use our own template, which contains a dismiss link. Users can dismiss the message if they want.
 */
function platon_status_messages($vars) {
  $display = $vars['display'];
  $output = '';
  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= theme('platon__message', array('type' => $type, 'heading' => !empty($status_heading[$type]) ? $status_heading[$type] : NULL, 'messages' => $messages));
  }
  return $output;
}

/**
 * Implements hook_form_FORM_ID_alter() for quiz_question_answering_form().
 * Group actions together for easier theming.
 */
function platon_form_quiz_question_answering_form_alter(&$form, $form_state) {
  $form['navigation']['#prefix'] = '<div class="quiz-question-navigation-wrapper">';
  $form['navigation']['#suffix'] = '</div>';
}

/**
 * Implements theme_apps_list().
 */
function platon_apps_list($vars) {
  $items = array();
  foreach($vars['apps'] as $id=> $app) {
    if (!preg_match("/^#/", $id)) {
      $items[] = drupal_render($app);
    }
  }
  return theme('platon__app_list', array('items' => $items));
}

/**
 * Helper function to create the main navigation.
 * Returns the rendered main navigation.
 * @see platon_theme().
 *
 * @return string
 */
function _platon_get_main_navigation() {
  $html = '';
  $items = _platon_get_main_navigation_items();
  $items_per_col = 2;

  // Separate the items in 2 columns.
  for ($i = 0, $rows = ceil(count($items) / $items_per_col); $i < $rows; $i++) {
    $j = $items_per_col;
    $row_html = '';
    // Render the items first.
    while ($j && ($item = array_shift($items))) {
      if (!empty($item['#href'])) {
        $row_html .= theme('platon__main_navigation__item', array('item' => $item));
        $j--;
      }
    }
    // If any items were rendered, wrap them in a row.
    if (!empty($row_html)) {
      $html .= theme('platon__main_navigation__row', array('items' => $row_html));
    }
  }

  return $html;
}

/**
 * Helper function to list the available items for the main navigation.
 *
 * @return array
 */
function _platon_get_main_navigation_items() {
  $source = @theme_get_setting('platon_menu_source');
  if (!empty($source)) {
    return menu_tree($source);
  }
  else {
    static $items = array();

    if (empty($items)) {
      $items = array(
        'home' => array(
          '#href' => '<front>',
          '#title' => t("Home"),
          'weight' => -50,
        ),
      );

      if (module_exists('opigno')) {
        $item = menu_get_item('my-courses');
        if ($item['access']) {
          $items['my-courses'] = array(
            '#href' => 'my-courses',
            '#title' => t("My courses"),
            'weight' => -40,
          );
        }

        $item = menu_get_item('course-catalogue');
        if ($item['access']) {
          $items['training-catalogue'] = array(
            '#href' => 'course-catalogue',
            '#title' => t("Training catalogue"),
            'weight' => -30,
          );
        }

        $item = menu_get_item('admin/opigno');
        if ($item['access']) {
          $items['administration'] = array(
            '#href' => 'admin/opigno',
            '#title' => t("Administration"),
            'weight' => 10,
          );
        }
      }

      if (module_exists('forum')) {
        $item = menu_get_item('forum');
        if ($item['access']) {
          $items['forum'] = array(
            '#href' => 'forum',
            '#title' => t("Forum"),
            'weight' => -20,
          );
        }
      }

      if (module_exists('opigno_calendar_app')) {
        $item = menu_get_item('opigno-calendar');
        if ($item['access']) {
          $items['calendar'] = array(
            '#href' => 'opigno-calendar',
            '#title' => t("Calendar"),
            'weight' => -15,
          );
        }
      }

      if (module_exists('opigno_quiz_app')) {
        $item = menu_get_item('my-achievements');
        if ($item['access']) {
          $items['my-achievements'] = array(
            '#href' => 'my-achievements',
            '#title' => t("My achievements"),
            'weight' => -10,
          );
        }
      }

      if (module_exists('opigno_messaging_app')) {
        $item = menu_get_item('messages');
        if ($item['access']) {
          $items['messages'] = array(
            '#href' => 'messages',
            '#title' => t("Messages"),
            'weight' => 0,
          );
        }
      }

      // Keep the keys.
      foreach ($items as $key => $item) {
        $items[$key]['#localized_options']['attributes']['id'] = 'main-navigation-item-' . $key;
      }

      usort($items, 'drupal_sort_weight');
    }

    return $items;
  }
}

/**
 * Helper function to get the group title.
 *
 * @param  int $nid
 *
 * @return string
 */
function platon_get_group_title($nid) {
  $query = db_select('node', 'n');
  $query->leftJoin('node_revision', 'v', 'n.vid = v.vid');
  return $query->fields('v', array('title'))
            ->condition('n.nid', $nid)
            ->execute()
            ->fetchField();
}

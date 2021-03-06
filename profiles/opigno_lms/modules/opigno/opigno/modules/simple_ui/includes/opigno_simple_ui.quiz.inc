<?php

/**
 * @file
 * Quiz specific enhancements.
 */

/**
 * Implements hook_theme_registry_alter().
 */
function opigno_simple_ui_quiz_theme_registry_alter(&$registry) {
  $registry['quiz_question_navigation_form']['function'] = 'theme_opigno_simple_ui_quiz_question_navigation_form';
}

/**
 * Implements hook_menu_local_tasks_alter().
 */
function opigno_simple_ui_quiz_menu_local_tasks_alter(&$data, $router_item, $root_path) {
  if ($root_path == 'node/%/quizzes') {
    if (!empty($data['actions']['output'])) {
      foreach ($data['actions']['output'] as $key => $theme_array) {
        if (!empty($theme_array['#link']['path'])) {
          if ($theme_array['#link']['path'] == 'node/add/quiz') {
            $data['actions']['output'][$key]['#link']['title'] = t("Add new lesson");
          }
          elseif ($theme_array['#link']['path'] == 'node/%/sort-quizzes') {
            $data['actions']['output'][$key]['#link']['title'] = t("Sort lessons");
          }
        }
      }
    }
  }
}

/**
 * Implements hook_form_quiz_directions_node_form_alter().
 */
function opigno_simple_ui_form_quiz_directions_node_form_alter(&$form, $form_state) {
  $form['add_directly']['#access'] = FALSE;
}

/**
 * Implements hook_form_quiz_report_form_alter().
 */
function opigno_simple_ui_form_quiz_report_form_alter(&$form, $form_state) {
  $no_report = TRUE;
  foreach ($form as $key => $value) {
    if (is_numeric($key)) {
      if (!$form[$key]['#no_report']) {
        $no_report = FALSE;
      }
    }
  }

  if ($no_report) {
    $form['#attributes']['class'][] = 'quiz-results-form-nothing-to-report';
    $form['success_message'] = array(
      '#markup' => '<h3>' . t("You successfully completed this lesson.") . '</h3>',
      '#attributes' => array(
        'class' => array('quiz-results-nothig-to-report')
      ),
    );
  }
}

/**
 * Implements hook_form_quiz_questions_form_alter().
 */
function opigno_simple_ui_form_quiz_questions_form_alter(&$form, $form_state) {
  $form['question_list']['browser']['#collapsible'] = TRUE;
  $form['question_list']['browser']['#collapsed'] = TRUE;

  // If this quiz is a 'theory' quiz, hide questions.
  $nid = arg(1);
  if ($nid && is_numeric($nid)) {
    $quiz = node_load($nid);
    if (!empty($quiz) && !empty($quiz->quiz_type[LANGUAGE_NONE][0]['value']) && $quiz->quiz_type[LANGUAGE_NONE][0]['value'] == 'theory' && isset($form['additional_questions']['quiz_directions'])) {
      $form['add_slide'] = array(
        '#type' => 'fieldset',
        '#title' => t("Add a slide to the lesson"),
        '#description' => t("A lesson of type 'theory' can only have slides as content."),
        '#weight' => -50,
      );
      $form['add_slide']['add_slide'] = $form['additional_questions']['quiz_directions'];

      $form['question_list']['#title'] = t("Slides inside this lesson");
      $form['question_list']['browser']['#access'] = FALSE;

      $form['additional_questions']['#attributes']['class'][] = 'element-hidden';
    }
  }
}

/**
 * Implements hook_form_quiz_node_form_alter().
 */
function opigno_simple_ui_form_quiz_node_form_alter(&$form, $form_state) {
  foreach ($form['resultoptions'] as $key => &$child) {
    if (!empty($child['#type']) && $child['#type'] == 'fieldset') {
      $child['#collapsed'] = TRUE;
    }
  }
  $form['#validate'][] = 'opigno_simple_ui_form_quiz_node_form_alter_validate';
}

/**
 * Validate callback for hook_form_quiz_node_form_alter().
 */
function opigno_simple_ui_form_quiz_node_form_alter_validate($form, &$form_state) {
  // If of type theory, deactivate Allow skipping questions".
  if (!empty($form_state['values']['quiz_type'][LANGUAGE_NONE][0]['value']) && $form_state['values']['quiz_type'][LANGUAGE_NONE][0]['value'] == 'theory') {
    $form_state['values']['allow_skipping'] = 0;
  }
}

/**
 * Theme Quiz navigation.
 */
function theme_opigno_simple_ui_quiz_question_navigation_form($vars) {
  $form = $vars['form'];
  if (!isset($form['#last'])) {
    return drupal_render_children($form);
  }
  else {
    $submit = drupal_render($form['submit']) . drupal_render($form['op']);
    return drupal_render_children($form) . $submit;
  }
}

/**
 * Update the quiz fields and settings and name them "Lesson".
 */
function opigno_simple_ui_update_quiz_labels() {
  variable_set('quiz_name', t("Lesson"));

  $instance = field_info_instance('node', 'quiz_weight', 'quiz');
  if (!empty($instance)) {
    $instance['label'] = "Lesson weight";
    field_update_instance($instance);
  }

  $instance = field_info_instance('node', 'quiz_type', 'quiz');
  if (!empty($instance)) {
    $instance['label'] = "Lesson type";
    field_update_instance($instance);
  }

  $instance = field_info_instance('node', 'course_required_quiz_ref', OPIGNO_COURSE_BUNDLE);
  if (!empty($instance)) {
    $instance['label'] = "Lesson required for the course validation";
    field_update_instance($instance);
  }
}

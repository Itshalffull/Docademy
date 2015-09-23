<?php

/**
 * @file
 * Rules integration for the class app module.
 *
 *
 *
 */

function opigno_class_app_rules_action_info() {
  $items = array();
  $items['class_map_role_to_course'] = array(
    'label' => t('Map class roles to course roles'),
    'group' => t('Class'),
    'parameter' => array(
      'class_role_id' => array(
        'type' => 'integer',
        'label' => t('Class role id'),
        'description' => t('The class role id'),
      ),
    ),
    'provides' => array(
      'course_role_id' => array(
        'type' => 'integer',
        'label' => t('Course role id'),
      ),
    ),
    'base' => 'opigno_class_app_class_to_course_role_map',
    'access callback' => 'og_rules_integration_access',
  );
  return $items;
}

function opigno_class_app_class_to_course_role_map($class_role_id) {
  $class_student = variable_get('class_app_class_studentid');
  $class_coach = variable_get('class_app_class_coachid');
  if (defined('OPIGNO_LMS_COURSE_STUDENT_ROLE'))
  {
    $course_student = opigno_lms_get_role_id(OPIGNO_LMS_COURSE_STUDENT_ROLE);
    $course_coach = opigno_lms_get_role_id(OPIGNO_LMS_COURSE_COACH_ROLE);
  }
  else
  {
    $course_student=0;
    $course_coach=0;
  }
  if ($class_role_id == $class_student) {
    return array('course_role_id' => $course_student);
  }
  if ($class_role_id == $class_coach) {
    return array('course_role_id' => $course_coach);
  }
  return 0;
}
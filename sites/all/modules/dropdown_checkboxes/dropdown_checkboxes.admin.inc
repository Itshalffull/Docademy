<?php

/**
 * @file
 * Configuration form for dropdown_checkboxes.
 */

/**
 * Settings form for dropdown checkboxes.
 */
function dropdown_checkboxes_settings_form($form, &$form_state) {
  $form = array();
  $form["dropdown_checkboxes_addjs_all_pages"] = array(
    "#type" => "checkbox",
    "#title" => t("Add IDs of select lists to be transformed into dropdown checkboxes."),
    "#description" => t("NOTE: This would load related js and css files on every page unless specific urls are not mentioned below."),
    "#default_value" => DROPDOWN_CHECKBOXES_ADDJS_ALL_PAGES,
    );
  $form["dropdown_checkboxes_collect_ids"] = array(
    "#type" => "textarea",
    "#rows" => 5,
    "#description" => t("Add one ID per line. <em>e.g. form-select-fruits</em>. Do not prefix #"),
    "#default_value" => DROPDOWN_CHECKBOXES_FORM_IDS,
    "#states" => array(
        "enabled" => array(
            ":input[name='dropdown_checkboxes_addjs_all_pages']" => array("checked" => TRUE),
          ),
      ),
    );
  $form["dropdown_checkboxes_addjs_specific_urls"] = array(
    "#type" => "checkbox",
    "#title" => t("Load related js and css files only on following urls."),
    "#description" => t("Checking this option would make !ddcl js and css files to load only on specified pages. This in turn helps in reducing the unusable code on every page load.", array("!ddcl" => l(t("ddcl"), "http://code.google.com/p/dropdown-check-list/"))),
    "#default_value" => DROPDOWN_CHECKBOXES_ADDJS_SPECIFIC_URLS,
    "#states" => array(
        "visible" => array(
            ":input[name='dropdown_checkboxes_addjs_all_pages']" => array("checked" => TRUE),
          ),
      ),
    );
  $form["dropdown_checkboxes_addjs_urls"] = array(
    "#type" => "textarea",
    "#rows" => 5,
    "#description" => t("Enter one relative url per line. Use * as wildcard.<em>e.g. admin/configure/mymodule, admin/configure/*</em>."),
    "#default_value" => DROPDOWN_CHECKBOXES_URLS,
    "#states" => array(
      "visible" => array(
            ":input[name='dropdown_checkboxes_addjs_all_pages']" => array("checked" => TRUE),
          ),
        "enabled" => array(
            ":input[name='dropdown_checkboxes_addjs_specific_urls']" => array("checked" => TRUE),
          ),
      ),
    );
  return system_settings_form($form);
}
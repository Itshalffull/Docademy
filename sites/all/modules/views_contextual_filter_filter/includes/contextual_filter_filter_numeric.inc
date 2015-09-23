<?php

/**
 * Override parts of views_handler_filter_numeric
 */
class contextual_filter_filter_numeric extends views_handler_filter_numeric {

  public function admin_summary() {
    if ($this->use_contextual_filter_value()) {
      return check_plain((string) $this->operator) . ' [contextual filter value]';
    }
    return parent::admin_summary();
  }

  /**
   * Should we use the contextual filter value?
   */
  public function use_contextual_filter_value() {
    return isset($this->options['contextual_filter']) && $this->options['contextual_filter'];
  }

  /**
   * Expose the contextual filter option for features export
   */
  public function option_definition() {
    $options = parent::option_definition();
    $options['contextual_filter'] = array('default' => '');

    return $options;
  }

  /**
   * Override the options_form().
   *
   * Adds options for using the contextual filter value to the filter form.
   */
  public function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    module_load_include('module', 'views_contextual_filter_filter');
    views_contextual_filter_filter_options_form($form, $form_state, $this);
  }

  /**
   * Extend the options_submit functionality to save the contextual filter data
   */
  public function options_submit(&$form, &$form_state) {
    parent::options_submit($form, $form_state);
    module_load_include('module', 'views_contextual_filter_filter');
    views_contextual_filter_filter_options_form_submit($form, $form_state, $this);
  }

  /**
   * Potentially override the handler's query.
   *
   * If we are using the contextual filter value, we'll changeup the query. If we are not,
   * we'll just pass this back to the views default handler.
   */
  public function query() {
    // do the default query
    if (!isset($this->options['contextual_filter']) || (isset($this->options['contextual_filter']) && !$this->options['contextual_filter'])) {
      parent::query();
    }
    // use our query
    else {
      $this->ensure_my_table();

      $argument = $this->view->argument[$this->options['contextual_filter']];
      $contextual_filter_value = '';
      if ($argument->get_value()) {
        $contextual_filter_value = $argument->get_value();
      }
      elseif ($argument->has_default_argument()) {
        $contextual_filter_value = $argument->get_default_argument();
      }
      $field = "$this->table_alias.$this->real_field";
      $this->value['value'] = $contextual_filter_value;
      $this->op_simple($field);
    }
  }
}

/**
 * @file
 * Module JS logic.
 */

;(function ($, Drupal) {

  Drupal.behaviors.opignoQuizApp = {

    attach: function (context, settings) {

      var $collapsibleTables = $('table.opigno-quiz-app-results-collapsible-table', context);
      if ($collapsibleTables.length) {
        $collapsibleTables.find('thead tr:eq(0)').each(function() {
          var $this = $(this);
          $this.click(function() {
            $this.parents('table:eq(0)').find('tbody').toggle();
          }).addClass('js-processed').click();
        });
      }
    }

  };

})(jQuery, Drupal);
(function ($) {
$(document).ready(function() {
   $(Drupal.settings.dropdown_checkboxes.dcid).each(function() {
    $("#" + this).dropdownchecklist({icon: {}});
   });
 });
 })(jQuery);

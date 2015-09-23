// Navigation menu 

// Create the dropdown base
$("<select />").appendTo(".navi");

// Create default option "Go to..."
$("<option />", {
   "selected": "selected",
   "value"   : "",
   "text"    : "Go to..."
}).appendTo(".navi select");

// Populate dropdown with menu items
$(".navi a").each(function() {
 var el = $(this);
 $("<option />", {
     "value"   : el.attr("href"),
     "text"    : el.text()
 }).appendTo(".navi select");
});

$(".navi select").change(function() {
  window.location = $(this).find("option:selected").val();
});

/* prettyPhoto Gallery */

jQuery(".prettyphoto").prettyPhoto({
   overlay_gallery: false, social_tools: false
});

/* Isotype */

// cache container
var $container = $('#portfolio');
// initialize isotope
$container.isotope({
  // options...
});

// filter items when filter link is clicked
$('#filters a').click(function(){
  var selector = $(this).attr('data-filter');
  $container.isotope({ filter: selector });
  return false;
});               

// Tooltip

$('.tip').tooltip();

// Popover

$('.pop').popover({
   'placement':'top'
});



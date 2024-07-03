

jQuery("#close-sidebar").click(function() {
  jQuery(".page-wrapper").removeClass("toggled");
});
jQuery("#show-sidebar").click(function() {
  jQuery(".page-wrapper").addClass("toggled");
});
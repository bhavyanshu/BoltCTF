$(function () {
  var path = window.location.pathname;
  path = path.replace(/\/$/, "");
  path = decodeURIComponent(path);
  $(".sidebar-menu a").each(function () {
    var href = $(this).attr('href');
    var tempath = path.substring(0, href.length);
    if (tempath === href) {
      if($(this).closest('.treeview').length) {
        $(this).closest('.treeview').addClass('active');
        $(this).closest('.treeview li').addClass('active');
      }
      else {
        $(this).closest('li').addClass('active');
      }
    }
  });
});

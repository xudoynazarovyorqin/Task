$(document).ready(function() {
  $("#pills-tabContent a.nav-link").on('click',function (e) {
    e.preventDefault();
    
    $("#pills-tabContent a.nav-link").removeClass('active--nav-tab-item')
    $(this).addClass('active--nav-tab-item')
  })
})
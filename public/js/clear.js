jQuery(document).ready(function($) {
  
  var tender = '<span><a class="entry-crumb" href="'+ urls.tender +'">Заявки</a></span>';
  var icon = '<i class="td-icon-right td-bread-sep td-bred-no-url-last"></i>';
  $('span.td-bred-no-url-last').before(tender + ' ' + icon + ' ');
  
});
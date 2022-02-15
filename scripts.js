jQuery(document).ready(function($) {

$('.popupDetection .close-popup').click(function(e){
  e.preventDefault();
  var selectedStore = $('body').attr('data-location'); // Set location
  Cookies.set('cookie-location', selectedStore, { expires: 30 });
  $('.popupDetection .mfp-close').trigger('click');
});

$('.setStore').click(function(e){
  var selectedStore = $(this).attr('data-closest'); // Set location
  Cookies.set('cookie-location', selectedStore, { expires: 30 });
});

});


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

 /*------------------------------------*\
        $COOKIE LOCATION
    \*------------------------------------*/

    // Magnific Popup - Standard     

    var user_agent = navigator.userAgent;
    // var is_bot = /bot|google|baidu|bing|msn|duckduckbot|teoma|slurp|yandex/i.test(navigator.userAgent);

    var is_bot = navigator.userAgent.indexOf("Googlebot") != -1;

    if (navigator.userAgent.indexOf("Googlebot") != -1) {        
        console.log(user_agent);
    } else {        
        console.log(user_agent);
        if (!Cookies.get('cookie-location')) {
            if ($('#popup-detected').length) {
            setTimeout(function() {
                
                //$('.header__location .js-magnific-location').trigger('click');
                $.magnificPopup.open({
                    items: {
                        src: '#popup-detected' 
                    },
                    type: 'inline'
        
                  // You may add options here, they're exactly the same as for $.fn.magnificPopup call
                  // Note that some settings that rely on click event (like disableOn or midClick) will not work here
                }, 0);
            }, 100);
        }
        }
    }


    $('.js-cookie-location').click(function() {
        var location = $(this).attr('data-location');
        if (!Cookies.get('cookie-location')) { // If the user hasn't set a location yet
            Cookies.set('cookie-location', location, { expires: 30 });
        } else if (Cookies.get('cookie-location')) {
            Cookies.remove('cookie-location');
            Cookies.set('cookie-location', location, { expires: 30 });
        }
    });

     if (Cookies.get('cookie-location')) { // Else if they have, redirect
         var cookieLocation = Cookies.get('cookie-location');
         var currentURL = window.location.href;
     }
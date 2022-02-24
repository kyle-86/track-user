jQuery(document).ready(function($) {



 /*------------------------------------*\
        $COOKIE LOCATION
    \*------------------------------------*/

    $.magnificPopup.close()

    // Magnific Popup - Standard     

    var user_agent = navigator.userAgent;
    // var is_bot = /bot|google|baidu|bing|msn|duckduckbot|teoma|slurp|yandex/i.test(navigator.userAgent);

    var is_bot = navigator.userAgent.indexOf("Googlebot") != -1;

    if (navigator.userAgent.indexOf("Googlebot") != -1) {        
        //console.log(user_agent);
    } else {        
        console.log(user_agent);
        if (!Cookies.get('cookie-location')) {
            if ($('#popup-detected').length) {
            setTimeout(function() {
                $.magnificPopup.open({
                    items: {
                        src: '#popup-detected' 
                    },
                    type: 'inline'
        
                  // You may add options here, they're exactly the same as for $.fn.magnificPopup call
                  // Note that some settings that rely on click event (like disableOn or midClick) will not work here
                }, 0);
            }, 2000);
        }
        }
    }


    $('.js-cookie-location').click(function() {
        var location = $(this).attr('data-location');
        console.log(location);
        if (!Cookies.get('cookie-location')) { // If the user hasn't set a location yet
            Cookies.set('cookie-location', location, { expires: 30 });
        } else if (Cookies.get('cookie-location')) {
            Cookies.remove('cookie-location');
            Cookies.set('cookie-location', location, { expires: 30 });
        }
    });
     if (Cookies.get('cookie-location')) { // Else if they have, redirect
        var cookieLocation = Cookies.get('cookie-location');
        cookieLocation = cookieLocation.toLowerCase();
        var currentURL = window.location.href;

        var windowPath = window.location.pathname;
        var start_pos = windowPath.indexOf('/') + 1;
        var end_pos = windowPath.indexOf('/',start_pos);
        var storeID = windowPath.substring(start_pos,end_pos);

        //console.log(storeID);

        if (storeID != 'au' && storeID != 'eu' && storeID != 'uk' ) {
            storeID = 'us';
        }

        //console.log(storeID);

        if ( cookieLocation == storeID ) {
            console.log('cookie matches store');
             var baseURL = window.location.origin;
            var sameLink = window.location.pathname;
            var newURL = sameLink.replace(storeID, cookieLocation);
            //console.log(baseURL + newURL);
        } else { 
            var baseURL = window.location.origin;
            var sameLink = window.location.pathname;

            var newURL = '';

            if (cookieLocation == 'us') {
                newURL = sameLink.replace(storeID, '');
                newURL = baseURL + newURL;
            } else if (  storeID = 'us' ) {
                newURL = baseURL + '/' + cookieLocation + '/' +  sameLink;
            } else {
                newURL = sameLink.replace(storeID, cookieLocation);
                newURL = baseURL + newURL;
            }

            window.location.href = newURL;
        }
     }

    });


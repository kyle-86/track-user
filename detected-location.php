<?php $cookie_detected_location = $_COOKIE['cookie-detected-country']; ?>
<?php $cookie_closest_store = $_COOKIE['cookie-closest-store']; ?>
<?php $storeLink = $_COOKIE['cookie-closest-store']; ?>

<?php 
	$urlURI = $_SERVER['REQUEST_URI'];
	$urlHost =  $_SERVER['HTTP_HOST'];

      $blog_id = get_current_blog_id();
      $location = 'US';
      if($blog_id == 2) {
        $location = 'AU';
      } elseif($blog_id == 3) {
        $location = 'EU';
      } elseif($blog_id == 4) {
        $location = 'UK';
      }

	if ($location != 'US') {
	  $urlURI = substr($urlURI, 3);
	}

	if ($storeLink == 'US') {
		$storeLink = '';
	}

	$sameURLdifferentStore = 'http://'.$urlHost.'/'.strtolower($storeLink).$urlURI;
?>

<div class="mfp-hide popupDetection" id="popup-detected">
</div>

<script>

$(document).ready(function() {

  console.log('here -> ');

	setTimeout(function() {

	function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

	let cookie_detected_location = getCookie('cookie-detected-country');
	let cookie_closest_store = getCookie('cookie-closest-store');
	let note = 'Please note, switching between sites later may empty your basket.';
	let php_url = "<?php echo $sameURLdifferentStore; ?>";
	let currentURL = $(location).attr('href');

  console.log(cookie_closest_store);

    $('\<div class="popup__body">\
		<h3 class="heading--beta heading--line heading--line--white heading--line--center" aria-label="Looks like you\'re shopping from "><span class="line1" aria-hidden="true">Looks like you\'re shopping from '+ cookie_detected_location +'</span></h3>\
		<blockquote><p>Would you like to swap to the '+ cookie_closest_store +' site?</p></blockquote>\
		<div class="countryButtons">\
			<a href="#" class="button button--outline close-popup">I\'m in the right place</a>\
			<a href="'+ php_url +'" class="button setStore" data-closest="'+ cookie_closest_store +'">Shop from the '+ cookie_closest_store +'</a>\
		</div>\
		'+ note + '\
	</div>').appendTo('#popup-detected');
  $('#popup-detected').addClass('popup');

  $('.popupDetection .close-popup').click(function(e){
    e.preventDefault();
    var selectedStore = $('body').attr('data-location'); // Set location
    Cookies.set('cookie-location', selectedStore, { expires: 30 });
    console.log('clicks');
    $('.popupDetection .mfp-close').trigger('click');
  });

$('.setStore').click(function(e){
  var selectedStore = $(this).attr('data-closest'); // Set location
  Cookies.set('cookie-location', selectedStore, { expires: 30 });
  console.log(selectedStore + 'current store');
});

}, 1500);

});

</script>

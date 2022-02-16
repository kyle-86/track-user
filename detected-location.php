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

<div class="popup mfp-hide popupDetection" id="popup-detected">
	<div class="popup__body">
		<h3 class="heading--beta heading--line heading--line--white heading--line--center" aria-label="Looks like you're shopping from "><span class="line1" aria-hidden="true">Looks like you're shopping from <?php echo $cookie_detected_location; ?></span></h3>
		<blockquote><p>Would you like to swap to the <?php echo $cookie_closest_store; ?> site?</p></blockquote>
		<div class="countryButtons">
			<a href="#" class="button button--outline close-popup">I'm in the right place</a>
			<a href="<?php echo $sameURLdifferentStore; ?>" class="button setStore" data-closest="<?php echo $cookie_closest_store; ?>">Shop from the <?php echo $cookie_closest_store; ?></a>
		</div>
		<?php if ( get_field('td_locator_note','options') ) : ?>
			<div> <?php echo get_field('td_locator_note','options'); ?> </div>
		<?php endif; ?>
	</div>
</div>
<?php 

/**
 * Plugin Name: Thirteen Redirect Users based on IP
 */
function isBot() {
  if ( isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider|klaviyo|mediapartners|Mb2345Browser|LieBaoFast|MicroMessenger|Kinza/i', $_SERVER['HTTP_USER_AGENT']) ) {
  }  else {
    // Is admin, but not doing ajaax
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
}
// Is doing AJAX 
else if ( is_admin() && ( defined( 'DOING_AJAX' ) || DOING_AJAX ) ) {
}
// Front-end functions
else { 

$cookie_location = $_COOKIE['cookie-location'];

if (!$cookie_location) {
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
  $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
  $ip = $_SERVER['REMOTE_ADDR'];
}

  
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.freegeoip.app/json/".$ip."?apikey=a12f5280-8ed3-11ec-bed5-ff90e409c622",
    //CURLOPT_URL => "https://freegeoip.app/json/210.8.50.30",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "accept: application/json",
      "content-type: application/json"
    ),
  ));

  // FOR localhost use ONLY!!!!!
  // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
  // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
  
  $data = curl_exec($curl);
  curl_close($curl);

$data = json_decode($data);

// if($_SERVER["REMOTE_ADDR"]=='124.171.210.30'){ 
// echo $ip;
// var_dump($data);
// echo '<h1>' .$data->country_code.'</h1>';
// }

$country_code = $data->country_code;
$longitudeFrom = $data->longitude;
$latitudeFrom = $data->latitude;
$country_name = $data->country_name;

setcookie("cookie-detected-country", $country_name, time()+2592000, '/');
setcookie("cookie-detected-country-code", $country_code, time()+2592000, '/');

$_COOKIE['cookie-detected-country'] = $country_name;
$_COOKIE['cookie-detected-country-code'] = $country_code;

}

function checkDistanceToEachStore( $latitudeFrom, $longitudeFrom, $earthRadius = 6371000)
{
  $eachStore = array (
    // Countrycode, LAT, LONG
    array("US",37.090240,-95.712891),
    array("AU",-25.274399,133.775131),
    array("UK",55.378052,-3.435973),
    array("EU",54.525963,15.255119)
  );

  $closestStoreDistance = '99999999999';
              $closestStoreCode = 'US';

  for ($totalStores = 0; $totalStores < count($eachStore); $totalStores++) {

    // $eachStore[$totalStores][0];

    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);

    $latTo = deg2rad($eachStore[$totalStores][1]);
    $lonTo = deg2rad($eachStore[$totalStores][2]);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

        //Calculate distance
    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    $distanceBetween = $angle * $earthRadius;

        //echo 'distance from IP to '.$eachStore[$totalStores][0]. ' = ' . $distanceBetween . '</br>';

    if ($distanceBetween < $closestStoreDistance) {
      $closestStoreDistance = $distanceBetween;
      $closestStoreCode = $eachStore[$totalStores][0];
    }

  }
  return $closestStoreCode;
}

$selectedCountiesUS =  get_site_option('td_force_country_us');
$selectedCountiesUK =  get_site_option('td_force_country_uk');
$selectedCountiesAU =  get_site_option('td_force_country_au');
$selectedCountiesEU =  get_site_option('td_force_country_eu');

if (!$cookie_location) {

  if (is_array($selectedCountiesUS)) {
    if (in_array($country_code, $selectedCountiesUS)) { 
      setcookie("cookie-closest-store", 'US', time()+2592000, '/');
      $_COOKIE['cookie-closest-store'] = 'US';
      $location_code = 'US';
    }
  }

  if (is_array($selectedCountiesUK)) {
    if (in_array($country_code, $selectedCountiesUK)) { 
      setcookie("cookie-closest-store", 'UK', time()+2592000, '/');
      $_COOKIE['cookie-closest-store'] = 'UK';
      $location_code = 'UK'; 
    }
  }

  if (is_array($selectedCountiesAU)) {
    if (in_array($country_code, $selectedCountiesAU)) {
      setcookie("cookie-closest-store", 'AU', time()+2592000, '/'); 
      $_COOKIE['cookie-closest-store'] = 'AU';
      $location_code = 'AU';
    }
  }

  if (is_array($selectedCountiesEU)) {
    if (in_array($country_code, $selectedCountiesEU)) { 
      setcookie("cookie-closest-store", 'EU', time()+2592000, '/');
      $_COOKIE['cookie-closest-store'] = 'EU';
      $location_code = 'EU'; 
    }
  }

}

if ( isset( $cookie_location ) ) {
  $location_code = $cookie_location;
}

if (!$location_code) {
  $location_code = checkDistanceToEachStore($latitudeFrom,$longitudeFrom);
  setcookie("cookie-closest-store", $location_code, time()+2592000, '/');
  $_COOKIE['cookie-closest-store'] = $location_code;
}

// Redirect to correct store

function insertLocatorChoice() {
    ?>
    
    
    <?php 
      $blog_id = get_current_blog_id();
      $location = 'US';
      if($blog_id == 2) {
        $location = 'AU';
      } elseif($blog_id == 3) {
        $location = 'EU';
      } elseif($blog_id == 4) {
        $location = 'UK';
      }

      if ($location != $_COOKIE['cookie-closest-store']) { ?>

    <?php include('detected-location.php'); ?>
    
    <?php }
}

add_action('check_location', 'insertLocatorChoice');

// if ($location_code == 'US') {
//   $url = network_home_url();
// } else {
//   $url = network_home_url().'/'.strtolower($location_code);
// }

// $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]";
// $actual_slug = $_SERVER[REQUEST_URI];
// $current_blogId = get_current_blog_id();

// if ($current_blogId != 1) {
//   $actual_slug = substr($actual_slug, 3);
// }

// $url = $url . $actual_slug;

// if ($url == $actual_link) {
//   if (strpos($actual_link, '/blog/') !== false) {
//     if ($current_blogId != 1) {
//     header('Location:'.network_home_url().$actual_slug);
//     exit;
//     }
//   }
// } else {
//   if (strpos($actual_link, '/blog/') !== false) {
//     if ($current_blogId != 1) {
//       header('Location:'.network_home_url().$actual_slug);
//       exit;
//       }
//   } else {
//     header('Location:'.$url);
//     exit;
//   }
// }

}
?>

<?php
add_action("network_admin_menu", "thirteen_track_users");
function thirteen_track_users() {

  add_menu_page(
    'Redirect Users', // Text in browser title bar
    'Redirect Users', // Text to be displayed in the menu.
    'manage_options', // Capability
    'thirteen-track-users', // Page slug, will be displayed in URL
    'thirteen_redirect_users', // Callback function which displays the page
    'dashicons-location-alt', // DASHICON
  );

}

function thirteen_redirect_users() { 
  $data = json_decode(file_get_contents(__DIR__ .'/countries.json'), true);
  ?>

<form method="post" action="edit.php?action=thirteenaction">
  <?php wp_nonce_field( 'thirteen-validate' ); ?>

  <?php $selectedCountiesUS = get_site_option('td_force_country_us'); ?>
  <?php $selectedCountiesUK = get_site_option('td_force_country_uk'); ?>
  <?php $selectedCountiesAU = get_site_option('td_force_country_au'); ?>
  <?php $selectedCountiesEU = get_site_option('td_force_country_eu'); ?>

  <table>
    <tr valign="top" align="left">
      <th scope="row"><label for="td_force_country_us">US STORE:
          <!-- <small> (<?php echo count($selectedCountiesUS); ?>) Selected </small> --> </label></th>
      <th scope="row"><label for="td_force_country_uk">UK STORE:
          <!-- <small> (<?php echo count($selectedCountiesUK); ?>) Selected </small> --> </label></th>
      <th scope="row"><label for="td_force_country_au">AU STORE:
          <!-- <small> (<?php echo count($selectedCountiesAU); ?>) Selected </small> --> </label></th>
      <th scope="row"><label for="td_force_country_eu">EU STORE:
          <!-- <small> (<?php echo count($selectedCountiesEU); ?>) Selected </small> --> </label></th>
    </tr>
    <tr valign="top">
      <td>
        <select class="selectiveJSselect" name="td_force_country_us[]" id="td_force_country_us" multiple
          style="height:400px;">

          <?php  foreach($data as $country)

            { ?>
          <option value="<?php echo $country['code']; ?>"
            <?php if (is_array($selectedCountiesUS)) { if (in_array($country['code'], $selectedCountiesUS)) { echo 'selected'; } } ?>>
            <?php echo $country['name']; ?></option>
          <?php }

            ?>
        </select>
      </td>
      <td>
        <select class="selectiveJSselect" name="td_force_country_uk[]" id="td_force_country_uk" multiple
          style="height:400px;">

          <?php  foreach($data as $country)

            { ?>
          <option value="<?php echo $country['code']; ?>"
            <?php if (is_array($selectedCountiesUK)) { if (in_array($country['code'], $selectedCountiesUK)) { echo 'selected'; } } ?>>
            <?php echo $country['name']; ?></option>
          <?php }

            ?>
        </select>
      </td>
      <td>

        <select class="selectiveJSselect" name="td_force_country_au[]" id="td_force_country_au" multiple
          style="height:400px;">

          <?php  foreach($data as $country)

            { ?>
          <option value="<?php echo $country['code']; ?>"
            <?php if (is_array($selectedCountiesAU)) { if (in_array($country['code'], $selectedCountiesAU)) { echo 'selected'; } } ?>>
            <?php echo $country['name']; ?></option>
          <?php }

            ?>
        </select>
      </td>
      <td>
        <select class="selectiveJSselect" name="td_force_country_eu[]" id="td_force_country_eu" multiple
          style="height:400px;">

          <?php  foreach($data as $country)
            { ?>
          <option value="<?php echo $country['code']; ?>"
            <?php if (is_array($selectedCountiesEU)) { if (in_array($country['code'], $selectedCountiesEU)) { echo 'selected'; } } ?>>
            <?php echo $country['name']; ?></option>
          <?php }

            ?>
        </select>
      </td>
    </tr>
  </table>
  <?php  submit_button(); ?>
</form>
</div>
<?php
}

add_action( 'network_admin_edit_thirteenaction', 'thirteen_save_settings' );

function thirteen_save_settings(){

  check_admin_referer( 'thirteen-validate' ); // Nonce security check

  update_site_option( 'td_force_country_us', $_POST['td_force_country_us'] );
  update_site_option( 'td_force_country_uk', $_POST['td_force_country_uk'] );
  update_site_option( 'td_force_country_au', $_POST['td_force_country_au'] );
  update_site_option( 'td_force_country_eu', $_POST['td_force_country_eu'] );

  wp_redirect( add_query_arg( array(
    'page' => 'thirteen-track-users',
    'updated' => true ), network_admin_url('themes.php')
));

  exit;

}


add_action( 'network_admin_notices', 'thirteen_custom_notices' );

function thirteen_custom_notices(){

  if( isset($_GET['page']) && $_GET['page'] == 'thirteen-track-users' && isset( $_GET['updated'] )  ) {
    echo '<div id="message" class="updated notice is-dismissible"><p>Settings updated.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
  }

}

function add_scrips()
{
     
    // loading css
    wp_register_style( 'selectize-css', plugin_dir_url( __FILE__ ) . '/vendor/selectize.css', false, '1.0.0' );
    wp_enqueue_style( 'selectize-css' );
     
    // loading js
    wp_register_script( 'selectize-js', plugin_dir_url( __FILE__ ) .'/vendor/selectize.js', array('jquery-core'), false, true );
    wp_enqueue_script( 'selectize-js' );
}
 
add_action( 'admin_enqueue_scripts', 'add_scrips' );

function add_front_scrips()
{
     
    // loading css
    wp_register_style( 'td-location-css', plugin_dir_url( __FILE__ ) . 'td-styles.css' );
    wp_enqueue_style( 'td-location-css' );
     
    // loading js
    wp_register_script('td-magnific-js', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js');
    wp_enqueue_script( 'td-magnific-js' );

    wp_register_script('td-location-js', plugin_dir_url( __FILE__ ) .'script.js');

    wp_enqueue_script( 'td-location-js' );
}
 
add_action( 'wp_enqueue_scripts', 'add_front_scrips', 20, 1 );

  }
}


add_action( "setup_theme", "isBot" );

?>
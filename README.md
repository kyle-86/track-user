# milton-track-user

<div id="top"></div>
<!--
*** Thanks for checking out the Best-README-Template. If you have a suggestion
*** that would make this better, please fork the repo and create a pull request
*** or simply open an issue with the tag "enhancement".
*** Don't forget to give the project a star!
*** Thanks again! Now go create something AMAZING! :D
-->



<!-- PROJECT SHIELDS -->
<!--
*** I'm using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->

<!-- PROJECT LOGO -->
<br />
<div align="center">
    <img src="https://s29938.pcdn.co/uk/wp-content/uploads/sites/4/2018/12/logo.svg" alt="Logo" width="80" height="80">
<h3 align="center">Milton and King Track Users</h3>
</div>

<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
    </li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

This project was built for the use on Milton & King website.

API used: https://freegeoip.app/

This requires an API key

Setting the api key:

1. Open change-theme-thirteen.php 
2. Find $apiKey = '#####-######-######-######'; ( Around line 20 )
3. Change to new api key

Add the following where you want the popup to go in your page. 
// <?php do_action('check_location'); ?>

Other required librarys used:

1. MagnificPopup ( loaded using CDN )
2. Selectize ( files are included )

This plug is activated via the networks plugin page and is updated from the networks admin panel left sidebar Redirect Users
https://miltonandking.com/wp-admin/network/admin.php?page=thirteen-track-users

Here you can change where located users from a country will be directed to shop from.

<p align="right">(<a href="#top">back to top</a>)</p>



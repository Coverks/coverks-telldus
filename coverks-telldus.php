<?php
/**
 * @package Coverks_Telldus
 * @version 0.1a
 */
/*
Plugin Name: Coverks Telldus
Description: Integration with Telldus Live API for WordPress
Author: Morten Hauan
Version: 0.1a
Author URI: http://hauan.me
*/

require_once('vendor/autoload.php');

/**
 * Use this if you're building an application just for you.
 *
 * On Telldus, you have the option to create a private token for your user, and don't have to go through the oauth-process
 * since Telldus generates a token and token_secret for you.
 *
 * Go to @see http://api.telldus.com/keys/index and click "Generate a private token for my user only"
 *
 * After that you can use the API as follows:
 */

$api = new Paxx\Telldus\Api(array(
	'identifier'      => 'FEHUVEW84RAFR5SP22RABURUPHAFRUNU',
	'secret'          => 'ZUXEVEGA9USTAZEWRETHAQUBUR69U6EF',
	'user_identifier' => '7714006d627959ff8afc1694d6d75d33059dced22',
	'user_secret'     => 'd6e38b68500972fe8295918c166350d6'
));

// Get all devices
$devices = $api->sensor(1310523)->info();

// Holder for the device we want
$deviceId = null;

echo "<pre>";
print_r($devices);
echo "</pre>";

die();

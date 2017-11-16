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

function coverks_telldus_api_light_on( $id ) {
	require_once( 'vendor/autoload.php' );


	$api = new Paxx\Telldus\Api( array(
		'identifier'      => 'FEHUVEW84RAFR5SP22RABURUPHAFRUNU',
		'secret'          => 'ZUXEVEGA9USTAZEWRETHAQUBUR69U6EF',
		'user_identifier' => '7714006d627959ff8afc1694d6d75d33059dced22',
		'user_secret'     => 'd6e38b68500972fe8295918c166350d6'
	) );

	return $api->device($id)->turnOn();

}

function coverks_telldus_api_light_off( $id ) {
	require_once( 'vendor/autoload.php' );


	$api = new Paxx\Telldus\Api( array(
		'identifier'      => 'FEHUVEW84RAFR5SP22RABURUPHAFRUNU',
		'secret'          => 'ZUXEVEGA9USTAZEWRETHAQUBUR69U6EF',
		'user_identifier' => '7714006d627959ff8afc1694d6d75d33059dced22',
		'user_secret'     => 'd6e38b68500972fe8295918c166350d6'
	) );

	return $api->device($id)->turnOff();

}
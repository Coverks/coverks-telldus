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

function coverks_telldus_get_outside_info() {
	require_once( 'vendor/autoload.php' );


	$api = new Paxx\Telldus\Api( array(
		'identifier'      => 'FEHUVEW84RAFR5SP22RABURUPHAFRUNU',
		'secret'          => 'ZUXEVEGA9USTAZEWRETHAQUBUR69U6EF',
		'user_identifier' => '7714006d627959ff8afc1694d6d75d33059dced22',
		'user_secret'     => 'd6e38b68500972fe8295918c166350d6'
	) );

	$sensor_info = $api->sensor(1310523)->info();

	$return_valie = [
		"temperature" => $sensor_info['data'][0]['value'],
		"humidity" => $sensor_info['data'][1]['value'],
		"updated" => $sensor_info['data'][1]['lastUpdated'],
	];
	return $return_valie;

}

function coverks_telldus_get_inside_info() {
	require_once( 'vendor/autoload.php' );


	$api = new Paxx\Telldus\Api( array(
		'identifier'      => 'FEHUVEW84RAFR5SP22RABURUPHAFRUNU',
		'secret'          => 'ZUXEVEGA9USTAZEWRETHAQUBUR69U6EF',
		'user_identifier' => '7714006d627959ff8afc1694d6d75d33059dced22',
		'user_secret'     => 'd6e38b68500972fe8295918c166350d6'
	) );

	$sensor_info = $api->sensor(1512099955)->info();

	$return_valie = [
		"temperature" => $sensor_info['data'][0]['value'],
		"humidity" => $sensor_info['data'][1]['value'],
		"updated" => $sensor_info['data'][1]['lastUpdated'],
	];
	return $return_valie;

}

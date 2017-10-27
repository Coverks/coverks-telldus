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

function coverks_update_sensors() {
	require_once( 'vendor/autoload.php' );


	$api = new Paxx\Telldus\Api( array(
		'identifier'      => 'FEHUVEW84RAFR5SP22RABURUPHAFRUNU',
		'secret'          => 'ZUXEVEGA9USTAZEWRETHAQUBUR69U6EF',
		'user_identifier' => '7714006d627959ff8afc1694d6d75d33059dced22',
		'user_secret'     => 'd6e38b68500972fe8295918c166350d6'
	) );

// Get all devices
	$sensors = $api->sensors();

	foreach ( $sensors as $sensor ) {
		$sensor_info = $api->sensor( $sensor['id'] )->info();

		//echo "<pre>";
		//print_r($sensor_info);
		//echo "</pre>";
		//die();

		// Check if post exist

		$args = array(
			'post_type'  => 'telldus-sensor',
			'meta_query' => array(
				array(
					'key'     => 'telldus_sensor_id',
					'value'   => $sensor_info['id'],
					'compare' => '=',
				),
			),
		);

		$old_post = get_posts( $args );

		// Create post object
		$my_post = array(
			'post_title'        => $sensor_info['name'],
			'post_status'       => 'publish',
			'post_author'       => 1,
			'post_type'         => 'telldus-sensor',
			'post_modified_gmt' => date( "Y-m-d H:i:s", $sensor_info['lastUpdated'] ),
			'post_content'      => json_encode( $sensor_info['data'] ),
		);

		if ( $old_post ) {

			if ( get_post_meta( $old_post[0]->ID, 'telldus_sensor_lastUpdated', true ) == $sensor_info['lastUpdated'] ) {
				continue;
			}
			$my_post["post_date_gmt"] = date( "Y-m-d H:i:s", $sensor_info['lastUpdated'] );
			$my_post["ID"]            = $old_post[0]->ID;
			$new_post_id              = wp_update_post( $my_post );
		} else {
			$new_post_id              = wp_insert_post( $my_post );
			$my_post["post_date_gmt"] = date( "Y-m-d H:i:s", $sensor_info['lastUpdated'] );
		}


		foreach ( $sensor_info as $key => $info ) {
			if ( 'data' == $key ) {
				foreach ( $info as $data ) {
					if ( ! add_post_meta( $new_post_id, "telldus_sensor_" . $data['name'], $data['value'], true ) ) {
						update_post_meta( $new_post_id, "telldus_sensor_" . $data['name'], $data['value'] );
					}
				}
				continue;
			}

			if ( ! add_post_meta( $new_post_id, "telldus_sensor_$key", $info, true ) ) {
				update_post_meta( $new_post_id, "telldus_sensor_$key", $info );
			}
		}

	}
}


//add_action( 'init', 'coverks_update_sensors' );
add_action( 'init', 'coverks_telldus_sensor_init' );
/**
 * Register a sensor post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function coverks_telldus_sensor_init() {
	$labels = array(
		'name'               => _x( 'Sensors', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Sensor', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Sensors', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Sensor', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'sensor', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Sensor', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Sensor', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Sensor', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Sensor', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Sensors', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Sensors', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Sensors:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No sensors found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No sensors found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => 'telldus',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'sensor' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'custom-fields', 'revisions', 'editor' )
	);

	register_post_type( 'telldus-sensor', $args );
}

add_action( 'init', 'coverks_telldus_device_init' );
/**
 * Register a device post type.
 *
 * @link http://coverks_telldus.wordpress.org/Function_Reference/register_post_type
 */
function coverks_telldus_device_init() {
	$labels = array(
		'name'               => _x( 'Devices', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Device', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Devices', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Device', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'device', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Device', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Device', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Device', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Device', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Devices', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Devices', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Devices:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No devices found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No devices found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => 'telldus',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'device' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'custom-fields', 'revisions', 'editor' )
	);

	register_post_type( 'telldus-device', $args );
}

function coverks_telldus_admin_menu() {
	add_menu_page(
		'Telldus',
		'Telldus',
		'read',
		'telldus',
		'',
		'dashicons-lightbulb',
		30
	);
}

add_action( 'admin_menu', 'coverks_telldus_admin_menu' );

add_action( 'manage_telldus-sensor_posts_custom_column' , 'custom_columns', 10, 2 );

function custom_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'temperature':
			$temperature = get_post_meta( $post_id, 'telldus_sensor_temp', true );
			if (  $temperature  ) {
				echo $temperature . "&deg;C";
			}
			break;

		case 'humidity':
			$humidity = get_post_meta( $post_id, 'telldus_sensor_humidity', true );
			if (  $humidity  ) {
				echo $humidity . "%";
			}
			break;
	}
}

add_filter( 'manage_edit-telldus-sensor_columns', 'my_edit_telldus_sensor_columns' ) ;

function my_edit_telldus_sensor_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Sensor' ),
		'temperature' => __( 'Temperature' ),
		'humidity' => __( 'Humidity' ),
		'date' => __( 'Date' )
	);

	return $columns;
}

/* CRON JOB */

add_filter('cron_schedules', 'new_interval');

// add once 10 minute interval to wp schedules
function new_interval($interval) {

	$interval['minutes_10'] = array('interval' => 10*60, 'display' => 'Once 10 minutes');

	return $interval;
}

register_activation_hook(__FILE__, 'my_activation');

function my_activation() {
	if (! wp_next_scheduled ( 'telldus_cron_job' )) {
		wp_schedule_event(time(), 'minutes_10', 'telldus_cron_job');
	}
}

add_action('telldus_cron_job', 'coverks_update_sensors');


register_deactivation_hook(__FILE__, 'my_deactivation');

function my_deactivation() {
	wp_clear_scheduled_hook('telldus_cron_job');
}
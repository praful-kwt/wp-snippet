<?php
/**
 * Security & Performance Enhancements
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Theme_Package
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Add security-related headers.
 *
 * @param array $headers Existing headers.
 * @return array Modified headers.
 */
function theme_prefix_additional_headers( $headers ) {
	if ( ! is_admin() ) {
		$headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains; preload';
		$headers['Referrer-Policy']           = 'no-referrer';
		$headers['X-Content-Type-Options']    = 'nosniff';
		$headers['X-XSS-Protection']          = '1; mode=block';
		$headers['X-Frame-Options']           = 'SAMEORIGIN';
		$headers['Permissions-Policy']        = 'accelerometer=(), camera=(), geolocation=(self), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()';
	}
	return $headers;
}
add_filter( 'wp_headers', 'theme_prefix_additional_headers' );

/**
 * Magic login link restricted by IP.
 */
function theme_prefix_magic_login() {
	$magic_slug  = 'magic-login';
	$allowed_ip  = '123.456.789.000'; // Replace with actual allowed IP.
	$serverdata  = wp_unslash( $_SERVER );
	$current_url = untrailingslashit( $serverdata['REQUEST_URI'] );
	$remote_ip   = $serverdata['REMOTE_ADDR'] ?? '';

	if ( '/' . $magic_slug === $current_url && $remote_ip === $allowed_ip ) {
		wp_set_auth_cookie( 1 ); // Replace with valid admin user ID.
		wp_safe_redirect( admin_url() );
		exit;
	}
}
add_action( 'init', 'theme_prefix_magic_login' );

/**
 * Restrict maximum image upload size.
 *
 * @param array $file Uploaded file data.
 * @return array
 */
function theme_prefix_limit_image_upload_size( $file ) {
	$max_size = 2 * 1024 * 1024; // 2MB.

	if ( isset( $file['size'] ) && $file['size'] > $max_size ) {
		$file['error'] = __( 'Image exceeds the maximum upload size of 2MB.', 'theme-textdomain' );
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'theme_prefix_limit_image_upload_size' );

/**
 * Disable the big image size threshold.
 */
add_filter( 'big_image_size_threshold', '__return_false' );

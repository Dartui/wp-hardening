<?php
/* Plugin Name: WordPress Hardening
 * Plugin URI: http://grabania.pl
 * Description: WordPress Hardening Plugin
 * Version: 1.0
 * Author: Krzysztof Grabania
 * Author URI: http://grabania.pl
 */

if (!class_exists('WP_Hardening')) {
	class WP_Hardening {
		public function __construct() {
			// remove emoji scripts
			remove_action('admin_print_scripts', 'print_emoji_detection_script');
			remove_action('admin_print_styles', 'print_emoji_styles');
			remove_action('embed_head', 'print_emoji_detection_script');
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('wp_print_styles', 'print_emoji_styles');
			
			// remove link to the WLW Manifest file
			remove_action('wp_head', 'wlwmanifest_link');
			
			// remove link to the Really Simple Discovery service endpoint
			remove_action('wp_head', 'rsd_link');
			
			// remove links to the Feed
			remove_action('wp_head', 'feed_links', 2);
			
			// disable XMLRPC
			add_filter('xmlrpc_enabled', '__return_false');
			
			// remove information about WordPress Version
			remove_action('wp_head', 'wp_generator');
			
			// remove information about WordPress Version in RSS
			add_filter('the_generator', '__return_empty_string');
			
			// remove information about Scripts and Styles Version
			add_filter('script_loader_src', array($this, 'remove_src_version'));
			add_filter('style_loader_src', array($this, 'remove_src_version'));
		}
		
		/**
		 * @see http://wpmagus.pl/pliki/prezentacje/wyczaruj-sobie-spokoj/#/25
		 */
		public function remove_src_version($src) {
			global $wp_version;

			$version_str = '?ver=' . $wp_version;
			$offset      = strlen($src) - strlen($version_str);

			if ($offset >= 0 && strpos($src, $version_str, $offset) !== FALSE) {
				return substr($src, 0, $offset);
			}

			return $src;
		}
	}

	new WP_Hardening();
}
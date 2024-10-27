<?php
/**
 * Plugin Name: Ads.txt Admin
 * Description: Ads.txt Admin is a simple tool that allows you to manage your ads.txt file from you WordPress dashboard.
 * Author: VideoYield.com
 * Author URI: https://www.videoyield.com/
 * License: GPLv2 or later
 * Text Domain: unveil-media-ads-txt
 * Version: 1.3
 */

if (!defined('ABSPATH')) {
	exit;
} // Exit if accessed directly


if (!defined('UNVEIL_MEDIA_ADS_TXT_VERSION')) {
	define('UNVEIL_MEDIA_ADS_TXT_VERSION', '1.3');
}

class Unveil_Media_Ads_Txt
{

	static $instance = null; // singleton pattern
	protected $enable_debug;
	protected $plugin_url;

	public static function get_file_path() {
		$upload_dir = wp_get_upload_dir();
		return $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'blog-' . get_current_blog_id() . '-ads.txt';
	}

	protected function __construct() {
		$this->enable_debug = !empty($_COOKIE['um-debug']);
		$this->plugin_url   = plugins_url('/', __FILE__);

		add_action('init', array($this, 'display_ads_txt'));
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
	}

	public function display_ads_txt() {
		$request = esc_url_raw($_SERVER['REQUEST_URI']);

		if ('/ads.txt' === $request) {
			$path = self::get_file_path();

			if (is_readable($path)) {
				header('Content-Type: text/plain');
				echo esc_html(file_get_contents($path));
				die();
			}
		}
	}

	public function plugin_options() {
		require "unveil-media-ads-txt.admin.inc.php";
	}

	public function enqueue_admin_scripts() {
		$url = plugin_dir_url(__FILE__) . 'js/main-admin-ads.js?v=' . UNVEIL_MEDIA_ADS_TXT_VERSION;
		wp_enqueue_script('unveil_media_ads_txt', $url, array('jquery'));

		wp_localize_script('unveil_media_ads_txt', 'unveil_media_ads_txt_data', array(
			'action_test_api_key' => 'test-api-key',
			'action_get_hostname' => 'get-hostname',
			'ajax_nonce' => wp_create_nonce('unveil_media_ads_txt-ajax-nonce'),
			'ajaxurl' => admin_url('admin-ajax.php')
		));
	}

	public function enqueue_admin_styles() {
		$url = plugin_dir_url(__FILE__) . 'css/main-admin.css?v=' . UNVEIL_MEDIA_ADS_TXT_VERSION;
		wp_enqueue_style('unveil_media_ads_txt', $url);
	}

	public function admin_menu() {
		add_menu_page(
			'Ads.txt Admin by VideoYield',
			'Ads.txt Admin by VideoYield',
			'manage_options',
			'unveil-media-ads-txt',
			array($this, 'plugin_options'),
			$this->plugin_url . 'img/um-ads-txt-avatar.png'
		);
	}

	public static function instance() {
		if (!Unveil_Media_Ads_Txt::$instance) {
			Unveil_Media_Ads_Txt::$instance = new self();
		}

		return Unveil_Media_Ads_Txt::$instance;
	}
}

$unveil_Media_Videowall = Unveil_Media_Ads_Txt::instance();

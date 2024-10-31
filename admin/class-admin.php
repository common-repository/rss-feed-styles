<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/rss-feed-styles/
 * @since      1.0.0
 *
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/admin
 * @author     lerougeliet
 */
class RSS_Feed_Styles_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in RSS_Feed_Styles_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The RSS_Feed_Styles_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/rss-feed-styles-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in RSS_Feed_Styles_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The RSS_Feed_Styles_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/rss-feed-styles-admin.js', array('jquery'), $this->version, false);
	}

	public function add_plugin_menu() {
		add_options_page('RSS Feed Styles Options', 'RSS Feed Styles', 'manage_options', 'rss-feed-styles', array(&$this, 'rss_feed_styles_options'));
	}

	public function rss_feed_styles_options() {
		if (!current_user_can('manage_options'))  {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/rss-feed-styles-admin-display.php';
	}

	function add_action_links($links) {
		$links[] = '<a href="' . admin_url('options-general.php?page=rss-feed-styles') . '">Options</a>';
		$links[] = '<a href="https://wordpress.org/support/plugin/rss-feed-styles/reviews/#new-post">Review</a>';
		return $links;
	}
}

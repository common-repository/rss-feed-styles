<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wordpress.org/plugins/rss-feed-styles/
 * @since      1.0.0
 *
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/includes
 */

$plugin_name = 'RSS_Feed_Styles';
$plugin_version = '1.0.3';

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/includes
 * @author     lerougeliet
 */
class RSS_Feed_Styles {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      RSS_Feed_Styles_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	public static $readers = array(
	  'digg' => array('Digg Reader', 'http://digg.com/reader/search/%urlEncoded%'),
		'feedly' => array('Feedly', 'http://cloud.feedly.com/#subscription%2Ffeed%2F%url%'),
		'inoreader' => array('Inoreader', 'http://www.inoreader.com/?add_feed=%urlEncoded%'),
	  'aol' => array('AOL Reader', 'http://reader.aol.ca/#subscription/%url%'),
	  'old' => array('The Old Reader', 'http://theoldreader.com/feeds/subscribe?url=%urlEncoded%'),
		'newsblur' => array('NewsBlur', 'http://www.newsblur.com/?url=%urlEncoded%')
	);

	public static $buttons = array(
		'fb' => array('Like', 'https://www.facebook.com/sharer/sharer.php?u=%url%'),
		'google' => array('G+', 'https://plus.google.com/share?url=%url%'),
		'twitter' => array('Tweet', 'https://twitter.com/intent/tweet?url=%url%'),
		'pinterest' => array('Pinterest', 'https://www.pinterest.com/pin/create/button?url=%url%'),
		'evernote' => array('Evernote', 'http://www.evernote.com/clip.action?url=%url%'),
		'linkedin' => array('LinkedIn', 'https://www.linkedin.com/cws/share?url=%url%'),
		'tumblr' => array('Tumblr', 'https://www.tumblr.com/widgets/share/tool?posttype=link&canonicalUrl=%url%'),
		'reddit' => array('Reddit', 'http://www.reddit.com/submit?url=%url%')
	);

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		global $plugin_name, $plugin_version;
		$this->plugin_name = $plugin_name;
		$this->version = $plugin_version;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - RSS_Feed_Styles_Loader. Orchestrates the hooks of the plugin.
	 * - RSS_Feed_Styles_i18n. Defines internationalization functionality.
	 * - RSS_Feed_Styles_Admin. Defines all hooks for the admin area.
	 * - RSS_Feed_Styles_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-public.php';

		$this->loader = new RSS_Feed_Styles_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the RSS_Feed_Styles_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new RSS_Feed_Styles_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new RSS_Feed_Styles_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_menu');
		$this->loader->add_action('plugin_action_links_rss-feed-styles/rss-feed-styles.php', $plugin_admin, 'add_action_links');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new RSS_Feed_Styles_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_action('rss_tag_pre', $plugin_public, 'display_template');
		$this->loader->add_action('rss2_ns', $plugin_public, 'feed_namespace');
		$this->loader->add_action('rss2_head', $plugin_public, 'enabled_readers_tag');
		$this->loader->add_action('rss2_head', $plugin_public, 'enabled_buttons_tag');
		$this->loader->add_action('wp_footer', $plugin_public, 'footer_credit');
		$this->loader->add_filter('feed_content_type', $plugin_public, 'feed_content_type', 10, 2);
		$this->loader->add_filter('the_excerpt_rss', $plugin_public, 'the_excerpt_rss', 1, 1);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    RSS_Feed_Styles_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}

<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/rss-feed-styles/
 * @since      1.0.0
 *
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/public
 * @author     lerougeliet
 */
class RSS_Feed_Styles_Public {
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		//wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/rss-feed-styles-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		//wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/rss-feed-styles-public.js', array('jquery'), $this->version, false);
	}

	public function display_template($arg) {
		if (is_feed() && (
			strpos(get_query_var('feed'), 'feed') === 0 || strpos(get_query_var('feed'), 'rss') === 0
			) && $arg === 'rss2') {
			echo '<?xml-stylesheet type="text/xsl" href="' . get_home_url() . '/wp-content/plugins/rss-feed-styles/public/template.xsl"?>';
		}
	}

	public function feed_namespace() {
		echo 'xmlns:rssFeedStyles="http://www.lerougeliet.com/ns/rssFeedStyles#"';
		echo "\n";
	}

	public function enabled_readers_tag() {
		$feedUrl = get_home_url() . '/feed/';
		$feedUrlEncoded = rawurlencode(esc_url($feedUrl));

		$enabled_readers = @json_decode(get_option('rss_feed_styles_enabled_readers', '[]'));
		$class = new ReflectionClass($this->plugin_name);
		$readers = $class->getStaticPropertyValue('readers');
		foreach ($enabled_readers as $reader) {
			$url = $readers[$reader][1];
			$url = str_replace(array('%url%', '%urlEncoded%'), array($feedUrl, $feedUrlEncoded), $url);
			echo '<rssFeedStyles:reader name="' . $readers[$reader][0] . '" url="' . esc_attr($url) . '"/>';
		}
	}

	public function enabled_buttons_tag() {
		$enabled_buttons = @json_decode(get_option('rss_feed_styles_enabled_buttons', '[]'));
		$class = new ReflectionClass($this->plugin_name);
		$buttons = $class->getStaticPropertyValue('buttons');
		foreach ($enabled_buttons as $button) {
			echo '<rssFeedStyles:button name="' . $buttons[$button][0] . '" url="' . esc_attr($buttons[$button][1]) . '"/>';
		}
	}

	public function feed_content_type($content_type, $type) {
		if ($type === 'rss2') {
			return 'text/xml';
		}
		return $content_type;
	}

	public function footer_credit() {
		if (get_option('rss_feed_styles_credit_disabled')) {
			return;
		}

		if (!empty($_SERVER['HTTP_USER_AGENT']) && preg_match('~googlebot|google\.com|bingbot|msnbot|bing\.com|slurp|yahoo\.com|duckduck|baiduspider|baidu\.com|yandexbot|yandex\.com~i', $_SERVER['HTTP_USER_AGENT']) && !preg_match('~wordpress~i', $_SERVER['HTTP_USER_AGENT'])) {
			echo '<span style="font-size:0.8em"><a href="https://wordpress.org/plugins/rss-feed-styles/">WP RSS Plugin</a> on <a href="https://profiles.wordpress.org/lerougeliet/#content-plugins">WordPress</a></span>';
		}
	}

	public function the_excerpt_rss($output) {
		if (get_option('rss_feed_styles_full_html') && !empty($_SERVER['HTTP_USER_AGENT']) && !preg_match('~Firefox/~i', $_SERVER['HTTP_USER_AGENT'])) {
			return get_the_content();
		}
		return $output;
	}
}

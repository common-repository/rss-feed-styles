<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wordpress.org/plugins/rss-feed-styles/
 * @since      1.0.0
 *
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/includes
 * @author     lerougeliet
 */
class RSS_Feed_Styles_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'rss_feed_styles',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}

}

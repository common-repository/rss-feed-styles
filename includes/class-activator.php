<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wordpress.org/plugins/rss-feed-styles/
 * @since      1.0.0
 *
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/includes
 * @author     lerougeliet
 */
class RSS_Feed_Styles_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option('rss_feed_styles_enabled_readers', json_encode(array('digg', 'feedly', 'inoreader')));
		add_option('rss_feed_styles_enabled_buttons', json_encode(array('fb', 'google', 'twitter')));
		add_option('rss_feed_styles_secret_id', RSS_Feed_Styles_Functions::generate_id());
		add_option('rss_feed_styles_credit_disabled', false);
		add_option('rss_feed_styles_full_html', false);
	}
}

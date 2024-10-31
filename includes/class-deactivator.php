<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://wordpress.org/plugins/rss-feed-styles/
 * @since      1.0.0
 *
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/includes
 * @author     lerougeliet
 */
class RSS_Feed_Styles_Deactivator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option('rss_feed_styles_secret_id');
		delete_option('rss_feed_styles_credit_disabled');
	}
}

<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wordpress.org/plugins/rss-feed-styles/
 * @since             1.0.0
 * @package           RSS_Feed_Styles
 *
 * @wordpress-plugin
 * Plugin Name:       RSS Feed Styles
 * Plugin URI:        https://wordpress.org/plugins/rss-feed-styles/
 * Description:       Styles your RSS feed automatically. Your RSS feed is at <a href="../?feed=rss2">http://myblog.com/feed/</a>
 * Version:           1.0.6
 * Author:            lerougeliet
 * Author URI:				https://profiles.wordpress.org/lerougeliet/#content-plugins
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rss-feed-styles
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * The class responsible for defining all helper functions used by the plugin.
 */
require_once plugin_dir_path(__FILE__) . 'includes/class-functions.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-activator.php
 */
function activate_rss_feed_styles() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-activator.php';
	RSS_Feed_Styles_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-deactivator.php
 */
function deactivate_rss_feed_styles() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-deactivator.php';
	RSS_Feed_Styles_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_rss_feed_styles');
register_deactivation_hook(__FILE__, 'deactivate_rss_feed_styles');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-main.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rss_feed_styles() {
	$plugin = new RSS_Feed_Styles();
	$plugin->run();
}
run_rss_feed_styles();

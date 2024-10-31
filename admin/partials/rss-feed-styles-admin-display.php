<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wordpress.org/plugins/rss-feed-styles/
 * @since      1.0.0
 *
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/admin/partials
 */

if (!empty($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'rss_feed_styles_update_options')) {
  if (!empty($_POST['rss_feed_styles_readers']) && is_array($_POST['rss_feed_styles_readers'])) {
    $enabled_readers = $_POST['rss_feed_styles_readers'];
    $class = new ReflectionClass($this->plugin_name);
		$readers = $class->getStaticPropertyValue('readers');
    $enabled_readers = array_intersect($enabled_readers, array_keys($readers));
  } else {
    $enabled_readers = array();
  }
  update_option('rss_feed_styles_enabled_readers', json_encode($enabled_readers));

  if (!empty($_POST['rss_feed_styles_buttons']) && is_array($_POST['rss_feed_styles_buttons'])) {
    $enabled_buttons = $_POST['rss_feed_styles_buttons'];
    $class = new ReflectionClass($this->plugin_name);
    $buttons = $class->getStaticPropertyValue('buttons');
    $enabled_buttons = array_intersect($enabled_buttons, array_keys($buttons));
  } else {
    $enabled_buttons = array();
  }
  update_option('rss_feed_styles_enabled_buttons', json_encode($enabled_buttons));

  if (isset($_POST['rss_feed_styles_credit_disabled'])) {
    update_option('rss_feed_styles_credit_disabled', !!$_POST['rss_feed_styles_credit_disabled']);
  }
  if (isset($_POST['rss_feed_styles_full_html'])) {
    update_option('rss_feed_styles_full_html', !!$_POST['rss_feed_styles_full_html']);
  }
} else {
  $enabled_readers = @json_decode(get_option('rss_feed_styles_enabled_readers', '[]'));
  $enabled_buttons = @json_decode(get_option('rss_feed_styles_enabled_buttons', '[]'));
}
?>

<h1>RSS Feed Styles Options</h1>

<form method="post" action="" novalidate="novalidate">
  <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce('rss_feed_styles_update_options'); ?>">
  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row">RSS Readers</th>
        <td>
          <fieldset>
            <?php
              $class = new ReflectionClass($this->plugin_name);
          		$readers = $class->getStaticPropertyValue('readers');
              foreach ($readers as $key => $reader) {
                ?>
                <label>
                  <input type="checkbox" name="rss_feed_styles_readers[]" value="<?php echo $key ?>"<?php echo in_array($key, $enabled_readers) ? ' checked' : '' ?>>
                  <?php echo $reader[0] ?>
                </label>
                <br>
                <?php
              }
            ?>
          </fieldset>
        </td>
      </tr>

      <tr>
        <th scope="row">Sharing buttons</th>
        <td>
          <fieldset>
            <?php
              $class = new ReflectionClass($this->plugin_name);
              $buttons = $class->getStaticPropertyValue('buttons');
              foreach ($buttons as $key => $button) {
                ?>
                <label>
                  <input type="checkbox" name="rss_feed_styles_buttons[]" value="<?php echo $key ?>"<?php echo in_array($key, $enabled_buttons) ? ' checked' : '' ?>>
                  <?php echo $button[0] ?>
                </label>
                <br>
                <?php
              }
            ?>
          </fieldset>
        </td>
      </tr>

      <tr>
        <th scope="row">Miscs</th>
        <td>
          <fieldset>
            <label>
              <input type="checkbox" name="rss_feed_styles_credit_disabled"<?php echo get_option('rss_feed_styles_credit_disabled') ? ' checked' : '' ?>>
              Disable credits
            </label>
            <p class="description">Removes link to the author's profile.</p>
            <br />
            <label>
              <input type="checkbox" name="rss_feed_styles_full_html"<?php echo get_option('rss_feed_styles_full_html') ? ' checked' : '' ?>>
              Show full HTML in feed
            </label>
            <p class="description">Disabled in Firefox. May not work in other browsers.</p>
          </fieldset>
        </td>
      </tr>
    </tbody>
  </table>

  <p class="submit">
    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
  </p>
</form>

<p>Note: to view changes, you may need to <a href="http://www.refreshyourcache.com/en/home/" rel="nofollow noreferrer" target="_blank">clear the browser cache</a>.</p>

<p><a href="https://wordpress.org/support/plugin/crypto-prices/reviews/#new-post">Leave a review</a></p>

<h3>My Other Plugins</h3>
<ul>
  <li><a href="https://wordpress.org/plugins/slideshow-reloaded/">Slideshow Reloaded</a></li>
  <li><a href="https://wordpress.org/plugins/auto-expire-posts/">Auto Expire Posts</a></li>
</ul>

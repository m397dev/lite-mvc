<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:36 AM
 */

namespace app\helpers;

/**
 * Network helper.
 */
class Network {

	/**
	 * This function is used to get user real ip address.
	 *
	 * @return mixed|null
	 */
	public static function getRealIP(): mixed {
		$ip = $_SERVER['REMOTE_ADDR'] ?? null;

		if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
			$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
		} else {
			if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		}

		return $ip;
	}

	/**
	 * This function is used to check an ip address is private or not.
	 *
	 * @param  string  $ip
	 *
	 * @return bool
	 */
	public static function isPrivateIP( string $ip ): bool {
		if ( ! empty( $ip ) ) {
			if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
				if ( $ip == '127.0.0.1' || $ip == '::1' ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * This function return user's OS information.
	 *
	 * @param  string  $user_agent
	 *
	 * @return string
	 */
	public static function getUserSystemInfo( string $user_agent ): string {
		$os_platform = "Unknown";
		$os_array    = [
			'/windows nt 10/i'      => 'Windows 10',
			'/windows nt 6.3/i'     => 'Windows 8.1',
			'/windows nt 6.2/i'     => 'Windows 8',
			'/windows nt 6.1/i'     => 'Windows 7',
			'/windows nt 6.0/i'     => 'Windows Vista',
			'/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
			'/windows nt 5.1/i'     => 'Windows XP',
			'/windows xp/i'         => 'Windows XP',
			'/windows nt 5.0/i'     => 'Windows 2000',
			'/windows me/i'         => 'Windows ME',
			'/win98/i'              => 'Windows 98',
			'/win95/i'              => 'Windows 95',
			'/win16/i'              => 'Windows 3.11',
			'/macintosh|mac os x/i' => 'Mac OS X',
			'/mac_powerpc/i'        => 'Mac OS 9',
			'/linux/i'              => 'Linux',
			'/ubuntu/i'             => 'Ubuntu',
			'/iphone/i'             => 'iPhone',
			'/ipod/i'               => 'iPod',
			'/ipad/i'               => 'iPad',
			'/android/i'            => 'Android',
			'/blackberry/i'         => 'BlackBerry',
			'/webos/i'              => 'Mobile',
		];

		foreach ( $os_array as $regex => $value ) {
			if ( preg_match( $regex, $user_agent ) ) {
				$os_platform = $value;
			}
		}

		return $os_platform;
	}

	/**
	 * This function return user's browser information.
	 *
	 * @param  string  $user_agent
	 *
	 * @return string
	 */
	public static function getUserBrowserInfo( string $user_agent ): string {
		$browser       = "Unknown";
		$browser_array = [
			'/msie/i'      => 'Internet Explorer',
			'/firefox/i'   => 'Firefox',
			'/safari/i'    => 'Safari',
			'/chrome/i'    => 'Chrome',
			'/edge/i'      => 'Edge',
			'/opera/i'     => 'Opera',
			'/netscape/i'  => 'Netscape',
			'/maxthon/i'   => 'Maxthon',
			'/konqueror/i' => 'Konqueror',
			'/mobile/i'    => 'Handheld Browser',
		];

		foreach ( $browser_array as $regex => $value ) {
			if ( preg_match( $regex, $user_agent ) ) {
				$browser = $value;
			}
		}

		return $browser;
	}

	/**
	 * This function return user's device information.
	 *
	 * @param  string  $user_agent
	 *
	 * @return string
	 */
	public static function getUserDeviceInfo( string $user_agent ): string {
		$tablet_browser = 0;
		$mobile_browser = 0;

		if ( preg_match(
			'/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i',
			strtolower( $_SERVER['HTTP_USER_AGENT'] )
		) ) {
			$tablet_browser ++;
		}

		if ( preg_match(
			'/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i',
			strtolower( $_SERVER['HTTP_USER_AGENT'] )
		) ) {
			$mobile_browser ++;
		}

		if ( ( strpos(
			       strtolower( $_SERVER['HTTP_ACCEPT'] ),
			       'application/vnd.wap.xhtml+xml'
		       ) > 0 ) or ( ( isset( $_SERVER['HTTP_X_WAP_PROFILE'] ) or isset( $_SERVER['HTTP_PROFILE'] ) ) ) ) {
			$mobile_browser ++;
		}

		$mobile_ua     = strtolower( substr( $user_agent, 0, 4 ) );
		$mobile_agents = [
			'w3c ',
			'acs-',
			'alav',
			'alca',
			'amoi',
			'audi',
			'avan',
			'benq',
			'bird',
			'blac',
			'blaz',
			'brew',
			'cell',
			'cldc',
			'cmd-',
			'dang',
			'doco',
			'eric',
			'hipt',
			'inno',
			'ipaq',
			'java',
			'jigs',
			'kddi',
			'keji',
			'leno',
			'lg-c',
			'lg-d',
			'lg-g',
			'lge-',
			'maui',
			'maxo',
			'midp',
			'mits',
			'mmef',
			'mobi',
			'mot-',
			'moto',
			'mwbp',
			'nec-',
			'newt',
			'noki',
			'palm',
			'pana',
			'pant',
			'phil',
			'play',
			'port',
			'prox',
			'qwap',
			'sage',
			'sams',
			'sany',
			'sch-',
			'sec-',
			'send',
			'seri',
			'sgh-',
			'shar',
			'sie-',
			'siem',
			'smal',
			'smar',
			'sony',
			'sph-',
			'symb',
			't-mo',
			'teli',
			'tim-',
			'tosh',
			'tsm-',
			'upg1',
			'upsi',
			'vk-v',
			'voda',
			'wap-',
			'wapa',
			'wapi',
			'wapp',
			'wapr',
			'webc',
			'winw',
			'winw',
			'xda ',
			'xda-',
		];

		if ( in_array( $mobile_ua, $mobile_agents ) ) {
			$mobile_browser ++;
		}

		if ( strpos( strtolower( $user_agent ), 'opera mini' ) > 0 ) {
			$mobile_browser ++;
			$stock_ua = strtolower( $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] ?? ( $_SERVER['HTTP_DEVICE_STOCK_UA'] ?? '' ) );

			if ( preg_match( '/(tablet|ipad|playbook)|(android(?!.*mobile))/i',
				$stock_ua ) ) {
				$tablet_browser ++;
			}
		}

		if ( $tablet_browser > 0 ) {
			return 'Tablet';
		} elseif ( $mobile_browser > 0 ) {
			return 'Mobile';
		} else {
			return 'Computer';
		}
	}

}
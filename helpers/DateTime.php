<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:36 AM
 */

namespace app\helpers;

use DateTime as BaseDateTime;

/**
 * DateTime helper.
 */
class DateTime {

	/**
	 * Check the given timestamp is weekend or not.
	 *
	 * @param  int|null  $timestamp
	 *
	 * @return bool
	 */
	public static function isWeekend( ?int $timestamp ): bool {
		if ( is_null( $timestamp ) ) {
			$timestamp = time();
		}

		return ( date( 'N', strtotime( $timestamp ) ) >= 6 );
	}

	/**
	 * Check if the given timestamp is today or not.
	 *
	 * @param $timestamp
	 *
	 * @return bool
	 */
	public static function isToday( $timestamp ): bool {
		$today     = new BaseDateTime( 'today' );
		$matchDate = BaseDateTime::createFromFormat( 'Y-m-d', $timestamp );
		$matchDate->setTime( 0, 0 );
		$diff     = $today->diff( $matchDate );
		$diffDays = (int) $diff->format( '%R%a' );

		if ( $diffDays === 0 ) {
			return true;
		}

		return false;
	}

}

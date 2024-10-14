<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        5:40 AM
 */

namespace app\system;

/**
 * Class Middleware
 *
 * This is the base middleware class.
 */
abstract class Middleware {

	/**
	 * @var array|mixed $actions List of actions will apply this middleware
	 */
	protected array $actions = [];

	/**
	 * Constructor.
	 *
	 * @param  array  $actions
	 */
	public function __construct( array $actions = [] ) {
		$this->actions = $actions;
	}

	/**
	 * Execute the middleware.
	 */
	abstract public function invoke();

}
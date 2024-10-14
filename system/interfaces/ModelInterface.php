<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        6:12 AM
 */

namespace app\system\interfaces;

/**
 * ModelInterface class.
 */
interface ModelInterface {

	/**
	 * Get table name this model represent.
	 *
	 * @return string
	 */
	public function represent(): string;

	/**
	 * Model validation.
	 *
	 * @return bool
	 */
	public function validate(): bool;

	/**
	 * Insert new record.
	 *
	 * @return bool
	 */
	public function insert(): bool;

	/**
	 * Update existing record.
	 *
	 * @return bool
	 */
	public function update(): bool;

	/**
	 * Delete existing record.
	 *
	 * @return bool
	 */
	public function delete(): bool;

}
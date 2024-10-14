<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:44 AM
 */

namespace app\system;

/**
 * Class Model.
 *
 * This is the base model.
 */
class Model {

	/**
	 * @var Database $db Database instance
	 */
	public Database $db;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->db = new Database();
	}

}

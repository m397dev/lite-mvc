<?php

namespace app\db\seeds;

use app\lib\DBFactory;	

/**
 * Class s_1728929032_add_default_users.
 */
class s_1728929032_add_default_users extends DBFactory
{
	
	/**
	 * This method contains the logic to be executed when apply this seed.
     *
     * @return false|int
     */
	public function up(): bool|int 
	{
		return $this->exec("");
	}
}
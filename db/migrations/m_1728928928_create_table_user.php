<?php

namespace app\db\migrations;

use app\lib\DBMigration;	

/**
 * Class m_1728928928_create_table_user.
 */
class m_1728928928_create_table_user extends DBMigration
{
	
	/**
	 * This method contains the logic to be executed when apply this migration.
     *
     * @return false|int
     */
	public function up(): bool|int 
	{
		return $this->exec("");
	}
	
	/**
	 * This method contains the logic to be executed when removing this
	 * migration.
     * 
     * @return bool
     */
	public function down(): bool 
	{
		echo "m_1728928928_create_table_user cannot be reverted.\n";
		
        return false;
	}
}
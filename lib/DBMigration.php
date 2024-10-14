<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:58 AM
 */

namespace app\lib;

use app\system\Database;
use PDO;

/**
 * Class DBMigration.
 *
 * This class handles the database migration system.
 */
class DBMigration extends Database {

	/**
	 * @var string $migrationNamespace Namespace of migration class
	 */
	private string $migrationNamespace = 'app\db\migrations\\';

	/**
	 * Create a new migration.
	 *
	 * @param  string  $migrationName
	 *
	 * @return void
	 */
	public function createMigration( string $migrationName ): void {
		$migration = $this->getMigrationName( $migrationName );
		$file      = fopen( $this->getMigrationPath() . '/' . $migration . '.php',
			'w' );
		$template  = "<?php

namespace app\db\migrations;

use app\lib\DBMigration;	

/**
 * Class $migration.
 */
class $migration extends DBMigration
{
	
	/**
	 * This method contains the logic to be executed when apply this migration.
     *
     * @return false|int
     */
	public function up(): bool|int 
	{
		return \$this->exec(\"\");
	}
	
	/**
	 * This method contains the logic to be executed when removing this
	 * migration.
     * 
     * @return bool
     */
	public function down(): bool 
	{
		echo \"$migration cannot be reverted.\\n\";
		
        return false;
	}
}";
		fwrite( $file, $template );
		fclose( $file );
		$this->log( 'Created migration ' . $migration );
	}

	/**
	 * Apply migrations.
	 *
	 * @return void
	 */
	public function applyMigrations(): void {
		$this->createMigrationsTable();
		$appliedMigrations = $this->getAppliedMigrations();
		$newMigrations     = [];
		$files             = scandir( $this->getMigrationPath() );
		$toApplyMigrations = array_diff( $files, $appliedMigrations );

		foreach ( $toApplyMigrations as $migration ) {
			if ( $migration === '.' || $migration === '..' || $migration === '.gitkeep' ) {
				continue;
			}

			require_once $this->getMigrationPath() . '/' . $migration;

			$className = $this->getMigrationClassName( $migration );
			$instance  = new $className();
			$this->log( 'Applying migration ' . $migration );
			$instance->up();
			$this->log( 'Applied migration ' . $migration );
			$newMigrations[] = $migration;
		}

		if ( ! empty( $newMigrations ) ) {
			$this->saveMigrations( $newMigrations );
		} else {
			$this->log( 'There are no migrations to apply' );
		}
	}

	/**
	 * Down an existing migration.
	 *
	 * @param  string  $migration
	 *
	 * @return void
	 */
	public function downMigrations( string $migration ): void {
		$file = $this->getMigrationPath() . $migration . '.php';

		if ( file_exists( $file ) ) {
			require $file;

			$className = $this->getMigrationClassName( $migration );
			$instance  = new $className();
			$this->log( 'Downing migration ' . $migration );
			$instance->down();
			$this->log( 'Downed migration ' . $migration );
		}
	}

	/**
	 * Create the "migrations" table.
	 *
	 * @return void
	 */
	private function createMigrationsTable(): void {
		$this->exec(
			"CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;"
		);
	}

	/**
	 * Get migrations has been applied .
	 *
	 * @return array|false
	 */
	private function getAppliedMigrations(): bool|array {
		$statement = $this->pdo->prepare( "SELECT migration FROM migrations" );
		$statement->execute();

		return $statement->fetchAll( PDO::FETCH_COLUMN );
	}

	/**
	 * Generate migration name.
	 *
	 * @param  string  $migrationName
	 *
	 * @return string
	 */
	private function getMigrationName( string $migrationName ): string {
		return 'm_' . strtotime( date( 'Y-m-d H:i:s',
				time() ) ) . '_' . $migrationName;
	}

	/**
	 * Get migration class name.
	 *
	 * @param  string  $migration
	 *
	 * @return string
	 */
	private function getMigrationClassName( string $migration ): string {
		return $this->migrationNamespace . pathinfo( $migration,
				PATHINFO_FILENAME );
	}

	/**
	 * Save new migrations.
	 *
	 * @param  array  $newMigrations
	 *
	 * @return void
	 */
	private function saveMigrations( array $newMigrations ): void {
		$str       = implode( ',',
			array_map( fn( $m ) => "('$m')", $newMigrations ) );
		$statement = $this->pdo->prepare( "INSERT INTO migrations (migration) VALUES $str" );
		$statement->execute();
	}

	/**
	 * Returns migration path.
	 *
	 * @return string|void
	 */
	private function getMigrationPath() {
		$path = realpath( dirname( __FILE__, 2 ) . '/db/migrations/' );

		if ( $path === false ) {
			die( 'Migration path not found.' );
		}

		return $path;
	}

}
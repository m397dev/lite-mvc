<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        1:02 AM
 */

namespace app\lib;

use app\system\Database;
use PDO;

/**
 * Class DBFactory.
 *
 * This class handles the database seed system.
 */
class DBFactory extends Database {

	/**
	 * @var string $seedNamespace Namespace of seed class
	 */
	private string $seedNamespace = 'app\db\seeds\\';

	/**
	 * Create a new seed.
	 *
	 * @param  string  $seedName
	 *
	 * @return void
	 */
	public function createSeed( string $seedName ): void {
		$seed     = $this->getSeedName( $seedName );
		$file     = fopen( $this->getFactoryPath() . '/' . $seed . '.php',
			'w' );
		$template = "<?php

namespace app\db\seeds;

use app\lib\DBFactory;	

/**
 * Class $seed.
 */
class $seed extends DBFactory
{
	
	/**
	 * This method contains the logic to be executed when apply this seed.
     *
     * @return false|int
     */
	public function up(): bool|int 
	{
		return \$this->exec(\"\");
	}
}";
		fwrite( $file, $template );
		fclose( $file );
		$this->log( 'Created seed ' . $seed );
	}

	/**
	 * Apply seeds.
	 *
	 * @return void
	 */
	public function applySeeds(): void {
		$this->createSeedsTable();
		$appliedSeeds = $this->getAppliedSeeds();
		$newSeeds     = [];
		$files        = scandir( $this->getFactoryPath() );
		$toApplySeeds = array_diff( $files, $appliedSeeds );

		foreach ( $toApplySeeds as $seed ) {
			if ( $seed === '.' || $seed === '..' || $seed === '.gitkeep' ) {
				continue;
			}

			require_once $this->getFactoryPath() . '/' . $seed;

			$className = $this->getSeedClassName( $seed );
			$instance  = new $className();
			$this->log( 'Applying seed ' . $seed );
			$instance->up();
			$this->log( 'Applied seed ' . $seed );
			$newSeeds[] = $seed;
		}

		if ( ! empty( $newSeeds ) ) {
			$this->saveSeeds( $newSeeds );
		} else {
			$this->log( 'There are no seeds to apply' );
		}
	}

	/**
	 * Create the "seeds" table.
	 *
	 * @return void
	 */
	private function createSeedsTable(): void {
		$this->exec(
			"CREATE TABLE IF NOT EXISTS seeds (
            id INT AUTO_INCREMENT PRIMARY KEY,
            seed VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;"
		);
	}

	/**
	 * Get seeds has been applied .
	 *
	 * @return array|false
	 */
	private function getAppliedSeeds(): bool|array {
		$statement = $this->pdo->prepare( "SELECT seed FROM seeds" );
		$statement->execute();

		return $statement->fetchAll( PDO::FETCH_COLUMN );
	}

	/**
	 * Generate seed name.
	 *
	 * @param  string  $seedName
	 *
	 * @return string
	 */
	private function getSeedName( string $seedName ): string {
		return 's_' . strtotime( date( 'Y-m-d H:i:s',
				time() ) ) . '_' . $seedName;
	}

	/**
	 * Get seed class name.
	 *
	 * @param  string  $seed
	 *
	 * @return string
	 */
	private function getSeedClassName( string $seed ): string {
		return $this->seedNamespace . pathinfo( $seed, PATHINFO_FILENAME );
	}

	/**
	 * Save new seeds.
	 *
	 * @param  array  $newSeeds
	 *
	 * @return void
	 */
	private function saveSeeds( array $newSeeds ): void {
		$str       = implode( ',',
			array_map( fn( $m ) => "('$m')", $newSeeds ) );
		$statement = $this->pdo->prepare( "INSERT INTO seeds (seed) VALUES $str" );
		$statement->execute();
	}

	/**
	 * Returns factory path.
	 *
	 * @return string|void
	 */
	private function getFactoryPath() {
		$path = realpath( dirname( __FILE__, 2 ) . '/db/seeds/' );

		if ( $path === false ) {
			die( 'Factory path not found.' );
		}

		return $path;
	}

}
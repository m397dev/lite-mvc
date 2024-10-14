<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:43 AM
 */

namespace app\system;

use PDO;
use PDOException;
use NilPortugues\Sql\QueryBuilder\Builder\MySqlBuilder;

/**
 * Database Connection Factory
 *
 * Creates and returns an instance of the appropriate Database Connection.
 */
class Database {

	/**
	 * @var PDO|null $pdo PDO instance
	 */
	protected ?PDO $pdo = null;
	/**
	 * @var MySqlBuilder|null $builder MySqlBuilder instance
	 */
	protected ?MySqlBuilder $builder = null;
	/**
	 * @var array $config array of configuration
	 */
	protected array $config = [];
	/**
	 * @var mixed Query variables "query"
	 */
	protected mixed $query = null;
	/**
	 * @var mixed Query variables "error"
	 */
	protected mixed $error = null;
	/**
	 * @var bool Database debug mode
	 */
	protected bool $debug;
	/**
	 * @var int $transactionCount DB transaction counter
	 */
	private int $transactionCount = 0;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->config = $this->getConfig();
		$this->debug  = $this->getConfigParam( $this->config, 'debug', true );

		if ( is_null( $this->pdo ) ) {
			$this->connect();
		}

		if ( is_null( $this->builder ) ) {
			$this->builder = new MySqlBuilder();
		}
	}

	/**
	 * Execute an SQL statement and return the number of affected rows.
	 *
	 * @param  string  $sql
	 *
	 * @return false|int|mixed
	 */
	protected function exec( string $sql ): mixed {
		$this->query = $this->pdo->exec( $sql );

		if ( $this->query === false ) {
			$this->error = $this->pdo->errorInfo()[2];
			$this->error();
		}

		return $this->query;
	}

	/**
	 * Create PDO transaction.
	 *
	 * @return bool
	 */
	protected function beginTransaction(): bool {
		if ( ! $this->transactionCount ++ ) {
			return $this->pdo->beginTransaction();
		}

		$this->exec( 'SAVEPOINT trans' . $this->transactionCount );

		return $this->transactionCount >= 0;
	}

	/**
	 * Commits a transaction.
	 *
	 * @return bool
	 */
	protected function commit(): bool {
		if ( ! -- $this->transactionCount ) {
			return $this->pdo->commit();
		}

		return $this->transactionCount >= 0;
	}

	/**
	 * Rolls back a transaction.
	 *
	 * @return bool
	 */
	protected function rollBack(): bool {
		if ( -- $this->transactionCount ) {
			$this->exec( 'ROLLBACK TO trans' . ( $this->transactionCount + 1 ) );

			return true;
		}

		return $this->pdo->rollBack();
	}

	/**
	 * Display DB log.
	 *
	 * @param  string  $msg
	 */
	protected function log( string $msg ): void {
		echo '[' . date( 'Y-m-d H:i:s' ) . '] - ' . $msg . PHP_EOL;
	}

	/**
	 * Throw the PDO exception.
	 *
	 * @param  string|null  $msg
	 */
	protected function error( string $msg = null ): void {
		if ( $this->debug === true ) {
			if ( php_sapi_name() === 'cli' ) {
				die( "Query: " . $this->query . PHP_EOL . "Error: " . $this->error . PHP_EOL );
			}

			if ( is_null( $msg ) ) {
				$msg = '<h1>Database Error</h1>';
				$msg .= '<h4>Query: <em style="font-weight:normal;">"' . $this->query . '"</em></h4>';
				$msg .= '<h4>Error: <em style="font-weight:normal;">' . $this->error . '</em></h4>';
			}

			die( $msg );
		}

		throw new PDOException( $this->error . '. (' . $this->query . ')' );
	}

	/**
	 * Create DB connection.
	 *
	 * @return void
	 */
	private function connect(): void {
		$params = $this->getConnectParams();

		try {
			$this->pdo = new PDO(
				$params['dsn'],
				$this->getConfigParam( $this->config, 'username', 'root' ),
				$this->getConfigParam( $this->config, 'password', '' ),
				$this->getConfigParam( $this->config, 'options' )
			);
			$this->exec( "SET NAMES '" . $params['charset'] . "' COLLATE '" . $params['collation'] . "'" );
			$this->exec( "SET CHARACTER SET '" . $params['charset'] . "'" );
			$this->pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE,
				PDO::FETCH_OBJ );
		} catch ( PDOException $e ) {
			die( 'Cannot the connect to Database with PDO. ' . $e->getMessage() );
		}
	}

	/**
	 * Returns DB connection params.
	 *
	 * @return array
	 */
	private function getConnectParams(): array {
		$database  = $this->getConfigParam( $this->config, 'database' );
		$driver    = $this->getConfigParam( $this->config,
			'driver',
			'mysql' );
		$host      = $this->getConfigParam( $this->config,
			'host',
			'localhost' );
		$charset   = $this->getConfigParam( $this->config,
			'charset',
			'utf8' );
		$collation = $this->getConfigParam( $this->config,
			'collation',
			'utf8_unicode_ci' );
		$port      = $this->getConfigParam( $this->config, 'port', 3306 );
		$dsn       = $this->getDsn( $database, $driver, $host, $port );

		return [
			'database'  => $database,
			'driver'    => $driver,
			'host'      => $host,
			'charset'   => $charset,
			'collation' => $collation,
			'port'      => $port,
			'dsn'       => $dsn,
		];
	}

	/**
	 * Get database DSN.
	 *
	 * @param $database
	 * @param $driver
	 * @param $host
	 * @param $port
	 *
	 * @return string
	 */
	private function getDsn( $database, $driver, $host, $port ): string {
		return match ( $driver ) {
			'', 'mysql', 'pgsql' => $driver . ':host=' . str_replace(
					':' . $port,
					'',
					$host
				) . ';' . ( $port !== '' ? 'port=' . $port . ';' : '' ) . 'dbname=' . $database,
			'sqlite' => 'sqlite:' . $database,
			default => '',
		};
	}

	/**
	 * Get configuration.
	 *
	 * @return mixed|void
	 */
	private function getConfig() {
		$dbConfig = realpath( dirname( __FILE__, 2 ) . '/config/db.php' );

		if ( $dbConfig === false ) {
			die( 'DB config not found!' );
		}

		return require $dbConfig;
	}

	/**
	 * Get configuration param.
	 *
	 * @param  array  $config
	 * @param  string  $param
	 * @param  mixed|null  $default
	 *
	 * @return mixed
	 */
	private function getConfigParam(
		array $config,
		string $param,
		mixed $default = null
	): mixed {
		return ! empty( $config[ $param ] ) ? $config[ $param ] : $default;
	}

}
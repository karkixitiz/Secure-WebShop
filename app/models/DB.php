<?php

/**
 * A wrapper around PDO to ensure that only one
 * connection to the DB is made
 * From: https://phpdelusions.net/pdo/pdo_wrapper#dependency_injection
 */
class DB
{
    protected static $instance;
    protected $pdo;

    protected function __construct() {
        require_once __DIR__ . '/../config/database.php';

        $host = $db_config['DB_SERVER'];
        $db   = $db_config['DB_NAME'];
        $user = $db_config['DB_USERNAME'];
        $pass = $db_config['DB_PASSWORD'];
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->db = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    // a classical static method to make it universally available
    public static function instance()
    {
        if (self::$instance === null)
        {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // a proxy to native PDO methods
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->pdo, $method), $args);
    }

    // a helper function to run prepared statements smoothly
    public function run($sql, $args = [])
    {
        if (!$args)
        {
             return $this->query($sql);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}

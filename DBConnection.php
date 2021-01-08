<?php

class Connection {

    private static ?Connection $dbconn = null;
    private static ?\PDO $PDO = null;

    /**
     * Connect to the database and return an instance of \PDO object
     * @return \PDO
     * @throws \Exception
     */
    public function connect() {

        // read parameters in the ini configuration file
        $params = parse_ini_file('postgres.ini');
        if ($params === false) {
            throw new \Exception("Error reading database configuration file");
        }
        // connect to the postgresql database
        $conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s", 
                $params['host'], 
                $params['port'], 
                $params['database'], 
                $params['user'], 
                $params['password']);

        $pdo = new \PDO($conStr);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    /**
     * return an instance of the Connection object
     * @return type
     */
    public static function get() {
        if (null === static::$dbconn) {
            static::$dbconn = new static();
            static::$PDO = static::$dbconn -> connect();
        }

        return static::$dbconn;
    }

    public static function getPDO() {
        if (static::$PDO === null) {
            static::get();
        }
        return static::$PDO;
    }

    protected function __construct() {
        
    }

    private function __clone() {
        
    }

    private function __wakeup() {
        
    }

}
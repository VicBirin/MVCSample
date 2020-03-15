<?php

namespace Database;


use PDO;

class DatabaseAdapter {
    
    /**
     * Database Connection
     * @var
     */
    private $dbConnection;

    /**
     * Database constructor. Set connection driver [pdo only in this demo]
     * @param $driver
     * @param $hostname
     * @param $username
     * @param $password
     * @param $database
     * @param $port
     */
    public function __construct($driver, $hostname, $username, $password, $database, $port) {
        $class = '\Database\Drivers\\' . $driver;

        if (class_exists($class)) {
            $this->dbConnection = new $class($hostname, $username, $password, $database, $port);
        } else {
            exit(sprintf("Error: Could not load database driver %s!", $driver));
        }
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function query($sql) {
        return $this->dbConnection->query($sql);
    }

    /**
     * @param $sql
     * @return bool|mixed
     */
    public function exec($sql) {
        return $this->dbConnection->exec($sql);
    }

    /**
     * @param $tableName
     * @param $page
     * @param $perPage
     * @return mixed
     */
    public function queryPage($tableName, &$page, $perPage) {
        $sql = "select count(*) as numRows from $tableName";
        $totalRows = $this->dbConnection->query($sql);
        $totalPages = intval(ceil($totalRows->row['numRows'] / $perPage));

        $page = intval($page);

        if($totalPages < 1){
            $totalPages = 1;
        }

        if($page > $totalPages) {
            $page = $totalPages;
        }
        if($page == 0) {
            $page = 1;
        }

        $offset = ($page - 1) * $perPage;

        return array("offset" => $offset, "perPage" => $perPage, "totalPages" => $totalPages);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function escape($value) {
        return $this->dbConnection->escape($value);
    }

    /**
     * Returns last inserted id
     * @return integer
     */
    public function getLastId() {
        return $this->dbConnection->getLastId();
    }
}

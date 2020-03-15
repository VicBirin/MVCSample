<?php

namespace Database\Drivers;

use PDOException;
use stdClass;

/**
 *  Global Class PDO
 */
final class PDO {

    /**
     * @var
     */
    private $pdo = null;

    /**
     * @var
     */
    private $statement = null;

    /**
     *  Construct, create object of PDO class
     * @param $hostname
     * @param $username
     * @param $password
     * @param $database
     * @param $port
     */
    public function __construct($hostname, $username, $password, $database, $port) {
        try {
            $this->pdo = new \PDO("mysql:host=" . $hostname . ";port=" . $port . ";dbname=" . $database, $username, $password,
                array(\PDO::ATTR_PERSISTENT => true, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
            $this->initializeDatabase();
        } catch(PDOException $e) {
            trigger_error('Error: Could not make a database link ( ' . $e->getMessage() . '). Error Code : ' . $e->getCode() . ' <br />');
            exit();
        }

        // set default setting database
        $this->pdo->exec("SET NAMES 'utf8'");
        $this->pdo->exec("SET CHARACTER SET utf8");
        $this->pdo->exec("SET CHARACTER_SET_CONNECTION=utf8");
        $this->pdo->exec("SET SQL_MODE = ''");
    }

    /**
     * Execute query statement
     * @param $sql
     * @return bool|stdClass
     */
    public function query($sql) {
        $this->statement = $this->pdo->prepare($sql);
        $result = false;

        try {
            if ($this->statement && $this->statement->execute()) {
                $data = array();

                while ($row = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }

                // create std class
                $result = new stdClass();
                $result->row = (isset($data[0]) ? $data[0] : array());
                $result->rows = $data;
                $result->num_rows = $this->statement->rowCount();
            }
        } catch (PDOException $e) {
            return $e;
        }

        if ($result) {
            return $result;
        } else {
            $result = new stdClass();
            $result->row = array();
            $result->rows = array();
            $result->num_rows = 0;
            return $result;
        }
    }

    /**
     * Exec non-query statement
     * @param $sql
     * @return bool|error
     */
    public function exec($sql) {
        $this->statement = $this->pdo->prepare($sql);
        try {
            if ($this->statement && $this->statement->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            return $e;
        }
        return false;
    }

    /**
     *  Clean inserted data
     * @param $value
     * @return string|string[]
     */
    public function escape($value) {
        $search = array("\\", "\0", "\n", "\r", "\x1a", "'", '"');
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"');
        return str_replace($search, $replace, $value);
    }

    /**
     *  Return last inserted id
     */
    public function getLastId() {
        return $this->pdo->lastInsertId();
    }

    public function __destruct() {
        $this->pdo = null;
    }

    /**
     * Create tables if not exists (for demo DB only)
     */
    public function initializeDatabase(){
        if($this->pdo == null) return;

        // Create table tasks
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS `tasks` (
                                      `id` INT NOT NULL AUTO_INCREMENT,
                                      `userName` VARCHAR(50) NOT NULL,
                                      `email` VARCHAR(255) NOT NULL,
                                      `body` TEXT NOT NULL,
                                      `completed` INT DEFAULT 0,
                                      PRIMARY KEY (`id`));
                                    ");

        // Create table users
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
                                      `id` INT NOT NULL AUTO_INCREMENT,
                                      `userName` VARCHAR(50) NOT NULL,
                                      `email` VARCHAR(255) NOT NULL,
                                      `password` VARCHAR(255),
                                      `isAdmin` INT NOT NULL DEFAULT 0,
                                      PRIMARY KEY (`id`),
                                      UNIQUE INDEX `email_UNIQUE` (`email` ASC));");
    }
}
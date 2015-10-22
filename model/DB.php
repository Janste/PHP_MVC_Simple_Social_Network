<?php

namespace model;

/**
 * This class is responsible for connection to the DB.
 * It is designed to be a singleton. It is possible to create only one instance of this class.
 */
class DB {

    // This object is an instance of this DB class
    private static $_instance = null;

    // This is the data which is used to connect to the DB
    private $conn;
    private $database = 'localhost';
    private $dbName = 'simple_social_network';
    private $dbUserName = 'root';
    private $dbPassword = 'root';
    private $dbPort = 8889;


    /**
     * Private constructor. It connect to the DB and
     * crates an instance of this class.
     */
    private function __construct() {

        // Connect to database
        $this->conn = new \mysqli($this->database, $this->dbUserName, $this->dbPassword, $this->dbName, $this->dbPort);

        // If couldn't connect to the DB, throw an exception
        if ($this->conn->connect_error) {
            throw new \Exception();
        }
    }

    /**
     * A static method which returns the instance of this DB class.
     * @return object of DB class
     */
    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    /**
     * Method which returns an array containing rows from the DB
     * @return array containing rows from the DB
     * @throws \Exception, if problems appeared with the database
     */
    public function getAllUsers() {

            $sql = "SELECT * FROM users"; // Select all entries
            $result = $this->conn->query($sql); // Execute and prepare for analysis

            if ($result == false) { // Error with the DB. Throw an exception.
                throw new \Exception();
            }
            // If no error, continue

            $array = array();
            while ($row = $result->fetch_assoc()) { // Fetch row after row
                $array[] = $row;
            }
            return $array;
    }

    /**
     * Adds a new user to the DB
     * @param $username
     * @param $password
     * @throws \Exception, if an error occurs with the DB
     */
    public function addToDB($username, $password) {
        // Prepare query
        $sql = "INSERT INTO users " .
                "(`username`,`password`) " .
                "VALUES ('$username', '$password')";

        // Execute query
        $result = $this->conn->query($sql);

        // Check for errors
        if ($result == false) {
            // Error with the DB. Throw an exception.
            throw new \Exception();
        }
    }

    public function updateRecord($table, $rowName, $userName, $newValue) {

        $sql = 'UPDATE '. $table . ' SET '. $rowName . '="'. $newValue . '" WHERE username="'. $userName . '"';

        // Execute query
        $result = $this->conn->query($sql);

        // Check for errors
        if ($result == false) {
            // Error with the DB. Throw an exception.
            throw new \Exception();
        }

    }
}
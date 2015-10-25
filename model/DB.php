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
     * Returns an array which contains queries from the users table inside the DB
     * @return array
     * @throws \Exception
     */
    public function getUsersList() {
        return $this->getAllQueries("users");
    }

    /**
     * Returns an array which contains queries from the status table inside the DB
     * @return array
     * @throws \Exception
     */
    public function getStatusList() {
        return $this->getAllQueries("status");
    }

    /**
     * Returns an array which contains querires from the followers table
     * @return array
     * @throws \Exception
     */
    public function getFolloweesList() {
        return $this->getAllQueries("followers");
    }

    /**
     * Adds a new user to the DB
     * @param $username
     * @param $password
     * @throws \Exception, if an error occurs with the DB
     */
    public function addUserToDB($username, $password) {
        $this->insertTwoStringQueries("users", "username", "password", $username, $password);
    }

    /**
     * Adds a user as a follower to another user who is the followee (a person who is being followed)
     * @param $follower
     * @param $followee
     * @throws \Exception
     */
    public function addFollowerToDB($follower, $followee) {
        $this->insertTwoStringQueries("followers", "username_follower", "username_followee", $follower, $followee);
    }

    /**
     * Adds a new status to the DB.
     * @param $username
     * @param $content
     * @throws \Exception
     */
    public function addStatusToDB($username, $content) {

        $date = date('Y-m-d H:i:s');

        $sql = 'INSERT INTO status (`username`,`date_and_time`,`content`) VALUES (? , ? , ? )';

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param('sss', $username, $date, $content);

        $stmt->execute();

        // Check for errors
        if ($stmt == false) {
            // Error with the DB. Throw an exception.
            throw new \Exception();
        }
    }

    /**
     * Updates a user inside the users table. User is identified by the username
     * @param $columnName
     * @param $userName
     * @param $newValue
     * @throws \Exception
     */
    public function updateUser($columnName, $userName, $newValue) {
        $this->updateRecord("users", "username" , $columnName, $userName, $newValue);
    }

    /**
     * Deletes a followee for a given user, identifies as follower
     * @param $follower
     * @param $followee
     * @throws \Exception
     */
    public function deleteFollowee($follower, $followee) {
        $this->deleteRecord("followers", "username_follower", "username_followee", $follower, $followee);
    }

    /**
     * Returns all queries from the specified table
     * @param $table
     * @return array
     * @throws \Exception
     */
    public function getAllQueries($table) {

        $sql = "SELECT * FROM " . $table; // Select all queries

        $result = $this->conn->query($sql); // Execute and prepare for analysis

        if ($result == false) { // Error with the DB. Throw an exception.
            throw new \Exception();
        }

        $array = array();
        while ($row = $result->fetch_assoc()) { // Fetch row after row
            $array[] = $row;
        }
        return $array;
    }

    /**
     * Inserts a query into the DB
     * @param $table
     * @param $column1
     * @param $column2
     * @param $argument1
     * @param $argument2
     * @throws \Exception
     */
    public function insertTwoStringQueries($table, $column1, $column2, $argument1, $argument2) {

        $sql = 'INSERT INTO ' . $table . ' (`' . $column1 . '`,`' . $column2 . '`) VALUES (? , ?)';

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param('ss', $argument1, $argument2);

        $stmt->execute();

        // Check for errors
        if ($stmt == false) {
            // Error with the DB. Throw an exception.
            throw new \Exception();
        }
    }

    /**
     * Updates a record
     * @param $table, the name of the table
     * @param $identifier, eg. username
     * @param $columnName
     * @param $userName
     * @param $newValue
     * @throws \Exception
     */
    public function updateRecord($table, $identifier, $columnName, $userName, $newValue) {

        $sql = 'UPDATE ' . $table . ' SET ' . $columnName . '=? WHERE ' . $identifier . '= "' . $userName . '"';

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param('s', $newValue);

        $stmt->execute();

        // Check for errors
        if ($stmt == false) {
            // Error with the DB. Throw an exception.
            throw new \Exception();
        }
    }

    /**
     * Deletes a record from DB
     * @param $table
     * @param $identifier1
     * @param $identifier2
     * @param $follower
     * @param $followee
     * @throws \Exception
     */
    public function deleteRecord($table, $identifier1, $identifier2, $follower, $followee) {
        $sql = 'DELETE FROM ' . $table .
            ' WHERE ' . $identifier1 . ' = "' . $follower . '"
            AND ' . $identifier2 . ' = "' . $followee . '"';

        // Execute query
        $result = $this->conn->query($sql);

        // Check for errors
        if ($result == false) {
            // Error with the DB. Throw an exception.
            throw new \Exception();
        }
    }
}
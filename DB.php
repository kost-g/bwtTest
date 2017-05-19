<?php

require('DBConnectionInterface.php');

class DB implements DBConnectionInterface
{
    //array of instances of DB class
    private static $dbInstances=[];

    //current instance of DB class
    private static $currDbInstance;

    //instance of connection
    public $pdoInstance;

    //name of database to connect
    public $dsn;

    //name of user to connect
    public $username;

    //password of user to connect
    public $password;

    //array of current PDO attributes
    public $pdoAttribute = array();

    private function __construct($dsn, $username, $password){
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->pdoInstance = $this->getPdoInstance();
        $this->pdoAttribute = null;
    }

    /**
     * Creates new instance representing a connection to a database
     * @param string $dsn The Data Source Name, or DSN, contains the information required to connect to the database.
     *
     * @param string $username The user name for the DSN string.
     * @param string $password The password for the DSN string.
     * @see http://www.php.net/manual/en/function.PDO-construct.php
     * @throws  PDOException if the attempt to connect to the requested database fails.
     *
     * @return $this DB
     */
    public static function connect($dsn, $username = '', $password = ''){
        //if first query of connect
        if (is_null(self::$currDbInstance)){
            self::$currDbInstance = new self($dsn, $username, $password);
            self::$dbInstances[] = self::$currDbInstance;
        } else {
        //if instances of class DB exist
            foreach (self::$dbInstances as $instance){
                if (($instance->$dsn === $dsn) && ($instance->$username === $username)){
                    self::$currDbInstance = $instance;
                    //if instance with necessary parameters exist
                }else{
                    //if instance with necessary parameters doesn't exist
                    self::$currDbInstance = new self($dsn, $username, $password);
                    self::$dbInstances[] = self::$currDbInstance;
                }
            }
        }
        return self::$currDbInstance;
    }

    /**
     * Completes the current session connection, and creates a new.
     *
     * @return void
     */
    public function reconnect(){
        $this->pdoInstance = null;
        $this->pdoInstance = $this->getPdoInstance();
        foreach ($this->pdoAttribute as $key => $val){
            $this->pdoInstance->setAttribute($key, $val);
        }
    }

    /**
     * Returns the PDO instance.
     *
     * @return PDO the PDO instance, null if the connection is not established yet
     */
    public function getPdoInstance(){
        $newPdoInstance = new PDO($this->dsn, $this->username, $this->password);
        if (is_null($newPdoInstance)){
            return null;
        }else{
            return $newPdoInstance;
        }
    }

    /**
     * Returns the ID of the last inserted row or sequence value.
     *
     * @param string $sequenceName name of the sequence object (required by some DBMS)
     *
     * @return string the row ID of the last row inserted, or the last value retrieved from the sequence object
     * @see http://www.php.net/manual/en/function.PDO-lastInsertId.php
     */
    public function getLastInsertID($sequenceName = null){
        return $this->pdoInstance->lastInsertId($sequenceName);
    }

    /**
     * Closes the currently active DB connection.
     * It does nothing if the connection is already closed.
     *
     * @return void
     */
    public function close(){
        $this->dsn = null;
        $this->username = null;
        $this->password = null;
        $this->pdoInstance = null;
        $this->pdoAttribute= null;
    }

    /**
     * Sets an attribute on the database handle.
     * Some of the available generic attributes are listed below;
     * some drivers may make use of additional driver specific attributes.
     *
     * @param int $attribute
     * @param mixed $value
     *
     * @return bool
     * @see http://php.net/manual/en/pdo.setattribute.php
     */
    public function setAttribute($attribute, $value){
        if (is_null($this->pdoAttribute)) {
            $this->pdoAttribute [$attribute] = $value;
        } else {
            foreach ($this->pdoAttribute as $key){
                if ($key === $attribute) {
                    $this->pdoAttribute[$key] = $value;
                } else {
                    $this->pdoAttribute[$attribute] = $value;
                }
            }
        }
        if (!is_null($this->pdoInstance)){
            $this->pdoInstance->setAttribute($attribute, $value);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the value of a database connection attribute.
     *
     * @param int $attribute
     *
     * @return mixed
     * @see http://php.net/manual/en/pdo.setattribute.php
     */
    public function getAttribute($attribute){
        return $this->pdoInstance->getAttribute($attribute);
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    private function __sleep() {
    }

}
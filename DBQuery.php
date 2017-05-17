<?php

require('DBQueryInterface.php');

class DBQuery implements DBQueryInterface
{
    public $connectInstance;

    /**
     * Create new instance DBQuery.
     *
     * @param DBConnectionInterface $DBConnection
     */
    public function __construct(DBConnectionInterface $DBConnection){
        $this->connectInstance = $DBConnection->pdoInstance;
    }

    /**
     * Returns the DBConnection instance.
     *
     * @return DBConnectionInterface
     */
    public function getDBConnection(){
        return clone $this->connectInstance;
    }

    /**
     * Change DBConnection.
     *
     * @param DBConnectionInterface $DBConnection
     *
     * @return void
     */
    public function setDBConnection(DBConnectionInterface $DBConnection){

    }

    /**
     * Executes the SQL statement and returns query result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed if successful, returns a PDOStatement on error false
     */
    public function query($query, $params = null){
        return $this->connectInstance->query($query);
    }

    /**
     * Executes the SQL statement and returns all rows of a result set as an associative array
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryAll($query, array $params = null){
        return $this->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Executes the SQL statement returns the first row of the query result
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryRow($query, array $params = null){
        return $this->query($query)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Executes the SQL statement and returns the first column of the query result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryColumn($query, array $params = null){
        return $this->query($query)->fetchAll(PDO::FETCH_NUM);
    }

    /**
     * Executes the SQL statement and returns the first field of the first row of the result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed  column value
     */
    public function queryScalar($query, array $params = null){
        return $this->query($query)->fetchColumn();
    }

    /**
     * Executes the SQL statement.
     * This method is meant only for executing non-query SQL statement.
     * No result set will be returned.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return integer number of rows affected by the execution.
     */
    public function execute($query, array $params = null){
        $email = $params['email'];
        $password = $params['password'];

        $exec = $this->connectInstance->prepare($query);

        $exec->bindValue(':email', "%{$email}%");
        $exec->bindValue(':password', "%{$password}%");

        return $exec->execute();
    }

    /**
     * Returns the last query execution time in seconds
     *
     * @return float query time in seconds
     */
    public function getLastQueryTime(){

    }
}
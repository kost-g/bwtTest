<?php

require('DBQueryInterface.php');

class DBQuery implements DBQueryInterface
{
//    public $connectInstance;
    public $DBpdoInstance;

    public $startTime;
    public $endTime;
    /**
     * Create new instance DBQuery.
     *
     * @param DBConnectionInterface $DBConnection
     */
    public function __construct(DBConnectionInterface $DBConnection){
        $this->setDBConnection($DBConnection);
        return $this->getDBConnection();
    }

    /**
     * Returns the DBConnection instance.
     *
     * @return DBConnectionInterface
     */
    public function getDBConnection(){
        return $this->DBpdoInstance;
    }

    /**
     * Change DBConnection.
     *
     * @param DBConnectionInterface $DBConnection
     *
     * @return void
     */
    public function setDBConnection(DBConnectionInterface $DBConnection){
//        $this->connectInstance = $DBConnection;
        $this->DBpdoInstance = $DBConnection->pdoInstance;
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
//        return $this->DBpdoInstance->query($query, $params);
        isset($params)? $exec = $this->DBpdoInstance->query($query, $params) : $exec = $this->DBpdoInstance->query($query);
        $this->endTime = microtime(true);
        return $exec;
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
        $this->startTime = microtime(true);
        isset($params)? $exec = $this->DBpdoInstance->prepare($query)->execute($params)->fetchAll(PDO::FETCH_ASSOC) : $exec = $this->query($query, PDO::FETCH_ASSOC)->fetchAll();
        $this->endTime = microtime(true);
        return $exec;
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
        $this->startTime = microtime(true);
        if (!is_null($params)){
            $exec = $this->DBpdoInstance->prepare($query);
            foreach($params as $key => $val) {
                $exec->execute([':' . $key => $val]);
            }
            $this->endTime = microtime(true);
            return $exec->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $this->endTime = microtime(true);
            return $this->query($query, PDO::FETCH_ASSOC)->fetch();
        }
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
        $this->startTime = microtime(true);
        isset($params)? $exec = $this->DBpdoInstance->prepare($query)->execute($params)->fetchAll(PDO::FETCH_COLUMN) : $exec = $this->query($query)->fetchAll(PDO::FETCH_COLUMN);
        $this->endTime = microtime(true);
        return $exec;
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
        $this->startTime = microtime(true);
        isset($params)? $exec = $this->DBpdoInstance->prepare($query)->execute($params)->fetchColumn() : $exec = $this->query($query)->fetchColumn();
        $this->endTime = microtime(true);
        return $exec;
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
        $this->startTime = microtime(true);
        isset($params)? $exec = $this->DBpdoInstance->prepare($query)->execute($params) : $exec = $this->query($query)->execute();
        $this->endTime = microtime(true);
        return $exec;
    }

    /**
     * Returns the last query execution time in seconds
     *
     * @return float query time in seconds
     */
    public function getLastQueryTime(){
        return (round(($this->endTime) - ($this->startTime), 5));
    }
}
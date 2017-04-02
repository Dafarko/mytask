<?php
require_once "config.php";

class Model{

    private $host = _DB_HOST;
    private $user = _DB_USER;
    private $password = _DB_PASSWORD;
    private $db = _DB;
    private $connection;
    public $query;
    public $debug = 1 ;
    public $errors = array();

    function __construct(){
        $this->connect();
    }
    // Make connection with Data Base
    public function connect(){
        if(!$this->connection = mysqli_connect($this->host, $this->user, $this->password, $this->db))
            die(mysqli_error());
    }

    public function lastQuery(){
        return $this->query;
    }
    //Function for insert in DB
    public function insert($table, $values){
        $query = "INSERT INTO " . $table
            . " (`" . implode("`, `", array_keys($values)) ."`) "
            . " VALUES (" . implode(', ', $values) . ") ";

        if($this->debug)
            $this->query = $query;

        if(!mysqli_query($this->connection, $query)){
            $this->errors = mysqli_error($this->connection);
            return false;
        }

        return mysqli_insert_id($this->connection);
    }
    //Universal query
    public function universalQuery($sql){
        $query = $sql;
        $row = mysqli_query($this->connection,$query);
        if($this->debug)
            $this->query = $query;

        if(!mysqli_query($this->connection, $query)){
            $this->errors = mysqli_error($this->connection);
            return false;
        }
    }
    //Insert one by one
    public function insertButch($table, $arrayValues){
        $query = "INSERT INTO " . $table
            . " (`" . implode("`, `", array_keys($arrayValues[0])) ."`) " ;

        foreach( $arrayValues as $one)
            $oneValues[] = "(". implode(", ",$one) .")";

        $query .= " VALUES " . implode(',', $oneValues);

        if($this->debug)
            $this->query = $query;

        if(!mysqli_query($this->connection, $query)){
            $this->errors = mysqli_error($this->connection);
            return false;
        }
        return mysqli_affected_rows($this->connection);
    }

    /**
     *
     * @param string $table
     * @param associative array $conditions
     *
     * @return bool
     */
    public function delete($table, $conditions = array(), $logOpen = 'AND'){
        $query = "DELETE FROM " . $table . " ";

        if(!empty($conditions)){
            $query .= " WHERE ";

            foreach($conditions as $column => $condition)
                $oneCondition[] = "`" . $column . "` " . $condition;

            $query .= implode(" " . $logOpen ." ", $oneCondition);
        }

        if($this->debug)
            $this->query = $query;

        if(!mysqli_query($this->connection, $query)){
            $this->errors = mysqli_error($this->connection);
            return false;
        }

        return mysqli_affected_rows($this->connection);

    }

    /*
     *
     * @param string $table
     * @param array $values
     * @param associative array $conditions
     * @return bool
     */
    public function update($table, $values, $conditions = array(), $ignore = false){
        if($ignore)
            $ignore = " IGNORE ";

        $query = "UPDATE " . $ignore . $table
            . " SET ";

        foreach($values as $column => $newValue)
            $oneSet[] = "`" . $column . "` = " . $newValue;

        $query .= implode(", ", $oneSet) ;

        if(!empty($conditions)){
            $query .= " WHERE ";

            foreach($conditions as $column => $condition)
                $oneCondition[] = $column . " " . $condition;

            $query .= implode(" AND ", $oneCondition);
        }

        if($this->debug)
            $this->query = $query;

        if(!mysqli_query($this->connection, $query)){
            $this->errors = mysqli_error($this->connection);
            return false;
        }

        return mysqli_affected_rows($this->connection);

    }

    public function selectOne($table,  $conditions = array(), $columns = '*'){
        $query = "SELECT " . $columns
            . " FROM " . $table ;

        if(!empty($conditions)){
            $query .= " WHERE ";

            foreach($conditions as $column => $condition)
                $oneCondition[] = $column . " " . $condition;

            $query .= implode(" AND ", $oneCondition);
        }

        $query .= " LIMIT 1";

        if($this->debug)
            $this->query = $query;

        $row = mysqli_query($this->connection, $query);

        if(!$row){
            $this->errors = mysqli_error($this->connection);
            return false;
        }

        if(!mysqli_num_rows($row))
            return false;

        return mysqli_fetch_assoc($row);
    }

    public function selectMany($table, $conditions = array(), $order = false, $limit = false, $columns = '*'){

        $query = "SELECT " . $columns
            . " FROM " . $table ;

        if(!empty($conditions)){
            $query .= " WHERE ";

            foreach($conditions as $column => $condition)
                $oneCondition[] = $column . " " . $condition;

            $query .= implode(" AND ", $oneCondition);
        }

        if($order)
            $query .= " ORDER BY " . $order;

        if($limit)
            $query .= " LIMIT " . $limit;

        if($this->debug)
            $this->query = $query;

        $row = mysqli_query($this->connection, $query);

        if(!$row){
            $this->errors = mysqli_error($this->connection);
            return false;
        }

        $rows = array();
        if(mysqli_num_rows($row)){
            while($one = mysqli_fetch_assoc($row))
                $rows[] = $one;
        }

        return $rows;
    }
}
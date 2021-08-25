<?php

class SQLWrapper
{
    public $num_rows = 0;

    private $myServer = "CETJNSQL02, 1433";
    private $myUser = "sa_dev";
    private $myPass = "Slayer6zed";
    private $myDB = "CETNEWSMS";

    private $data = array();
    private $query = "";
    private $conn;
    private $stmt;


    public function __construct()
    {

        //$this->data = $queryresult;
    }

    public function __destruct()
    {
        $this->close();
    }

    public function connect()
    {
        //echo $this->myDB;
        $connectionInfo = array( "Database"=>$this->myDB, "UID"=>$this->myUser , "PWD"=>$this->myPass);
        $this->conn = sqlsrv_connect( $this->myServer , $connectionInfo) or die(print_r( sqlsrv_errors(), true));

        if (false == $this->conn)
        {

            die("Connection failed: " . print_r( sqlsrv_errors(), true));
        }
    }

    public function close()
    {
        sqlsrv_free_stmt( $this->stmt);
    }

    public function query($query)
    {
        $this->stmt = sqlsrv_query($this->conn, $query);

        $stmt = sqlsrv_query($this->conn, $query,array(), array ('Scrollable' => SQLSRV_CURSOR_STATIC)); // For count

        if( $this->stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }

        $this->num_rows = sqlsrv_num_rows( $stmt );
        //$this->num_rows = sqlsrv_num_rows( $this->stmt );

        return $this;
        //$this->query = $query;
    }

    public function insert($query)
    {
        if ( false == strpos($query,"SCOPE_IDENTITY"))
        {
            $query .= "; SELECT SCOPE_IDENTITY(); ";
        }

        $this->stmt = sqlsrv_query($this->conn, $query);

        sqlsrv_next_result($this->stmt);
        sqlsrv_fetch($this->stmt);

        return sqlsrv_get_field($this->stmt, 0);
    }

    public function fetch_array()
    {
        return sqlsrv_fetch_array( $this->stmt, SQLSRV_FETCH_BOTH);
    }

    public function fetch_assoc()
    {
        return $this->fetch_array();
    }
}

?>
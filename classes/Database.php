<?php
Class Database{

    protected   $db_name = "";
    protected   $db_user = "";
    protected   $db_pass = "";
    public      $is_connected = false;
    public      $db_host = "";
    private     $connection = "";

    //Connection settings
    public function open_connection($host, $username, $password, $database)
    {
        $this->db_host = $host;
        $this->db_user = $username;
        $this->db_pass = $password;
        $this->db_name = $database;

        try
        {
            $this->connection = @mysql_connect($this->db_host, $this->db_user, $this->db_pass);

            if(!$this->connection){
                throw new Exception("Can't connect to database: " . mysql_error());
                $this->$is_connected = false;
            }else{
                $this->query("SET NAMES utf8");
                $this->query("SET collation_connection = 'utf8_general_ci'");
                $this->is_connected = true;
            }

        }catch (Exception $e)
        {
            return $e->getMessage();
        }

        $this->select_db();
        return $this->is_connected;
    }

    private function select_db(){
        mysql_select_db($this->db_name) or die("Cant select databse!");
    }

    public function close_connection()
    {
        if($this->is_connected == false)
        {
            return true;
        }

        mysql_close($this->connection);

    }

    //CRUD settings
    public function query($sql)
    {
        $result = mysql_query($sql) or die("Query error: " . mysql_error() );
        return $result;
    }

    public function query_num_rows($sql)
    {
        $result = mysql_query($sql);
        if($result){
            return mysql_num_rows($result);
        }else{
            return false;
        }

    }

    public function get_result_array($sql)
    {
        $result_array = array();

        $result = mysql_query($sql) or die("Query error: " . mysql_error() );

        if( mysql_num_rows($result) > 0)
        {
            while($row = mysql_fetch_array($result, MYSQL_ASSOC))
            {
                $result_array[] = $row;
            }
        }else{
            return false;
        }

        return $result_array;
    }

    public function get_result($sql)
    {
        $result_array = array();

        $result = mysql_query($sql) or die("Query error: " . mysql_error() );
//        echo mysql_num_rows($result);

        if( mysql_num_rows($result) > 0)
        {
            while($row = mysql_fetch_object($result))
            {
                $result_array[] = $row;
            }
        }else{
            return false;
        }

        return $result_array;
    }

    public function insert($table, $data)
    {
        !is_array($data) ? die("Database::insert function error: \"data\" argument must be array object!") : null;

        $sql_fields  = "";
        $sql_data   = "";

        //generisi values
        foreach($data as $key => $value)
        {
            $sql_fields .= "`" . $key . "`, ";

            //ako je int ne treba staviti navodnike u string
            if( is_int( $value ) )
            {
                $sql_data .= $value . ", ";
            }else{
                $sql_data .= "'" . $value . "', ";
            }

        }

        //izbrisi ", " iz generisanih stringova
        $sql_fields_length = strlen($sql_fields) - 2;
        $sql_data_length   = strlen($sql_data) -2 ;

        $sql_fields = substr( $sql_fields, 0, $sql_fields_length);
        $sql_data   = substr( $sql_data, 0, $sql_data_length);

        //Generisani SQL query
        $generated_sql_query = "INSERT INTO " . $table . " (" . $sql_fields . ") VALUES (" . $sql_data . ")";

        //Unesi
        $this->query($generated_sql_query);

        return mysql_insert_id();
    }

    public function insert_single($query)
    {
        //Unesi
        $this->query($query);

        return mysql_insert_id();
    }



}
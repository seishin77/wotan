<?php
require_once('conf/db.conf');

class dbm{
  private static $_db = null;

  private function __construct(){}

  public static function getConnexion(){
    if(is_null(self::$_db)){
      self::$_db = new db;
    }
    return self::$_db;
  }
}

class db{

  var $hostname;
  var $db;
  var $user; 
  var $pass; 
  var $conn;
  var $result;
  var $debug;
  var $stmt;
  static $_statements = array();

  function __construct($host=HOST, $data=DB, $username=USER, $passwd=PASS, $debug=false){
    $this->hostname = $host;
    $this->db = $data;
    $this->user = $username;
    $this->pass = $passwd;
    $this->connect();
    $this->result="";
    $this->stmt = null;
    $this->debug = $debug;

  }

  function q($sql, $types='', $data=array(), $fog=array(), $file= __FILE__, $line= __LINE__){
    // var_dump(func_get_args());
    if(!isset(self::$_statements[$sql])){
      self::$_statements[$sql] = mysqli_prepare($this->conn, $sql);
      if(self::$_statements[$sql] === false){
        unset(self::$_statements[$sql]);
        if(count($fog)>0){
          $sql = str_replace($fog, '********', $sql);
        }
        $this->error($file . " " . $line . " Couldn't prepare query '" . $sql . "'!!<BR><BR>\n" .
               mysqli_errno($this->conn) . " : " . mysqli_error($this->conn) . "<BR>\n" . $sql."<BR>");
        exit();
      }
    }
    $this->stmt = self::$_statements[$sql];
    if($types != '' && count($data) > 0){
      $refarg = array($this->stmt, $types);
      foreach ($data as $key => $value)
        $refarg[] =& $data[$key];

      call_user_func_array('mysqli_stmt_bind_param', $refarg);
    }
    mysqli_execute($this->stmt);
    $this->result = mysqli_stmt_get_result($this->stmt);
  }

  function query($sql, $types='', $data=array(), $fog=array(), $file= __FILE__, $line= __LINE__){
    $this->q($sql, $types, $data, $fog, $file, $line);
  }

  function error($err, $type=E_USER_ERROR){
    trigger_error($err, $type);
  }

  function getDB(){
    return $this->db;
  }

  function connect(){
    $this->conn = @mysqli_connect($this->hostname, $this->user, $this->pass, $this->db) or trigger_error(mysqli_connect_error(),E_USER_ERROR); 
  }

  function fetch_array($result_type = MYSQL_ASSOC){
    $row = mysqli_fetch_array($this->result, $result_type);
    if($row){
      $keys = array_keys($row);
      global $masked;
      if(isset($masked))
        $old_masked = $masked;
      $masked = false;
      array_walk($keys, 'mask_field_name');
      if($masked){
      	array_walk($row, 'mask_field');
      	return array_filter(array_combine($keys, array_values($row)));
      }
      if(isset($old_masked))
        $masked = $old_masked;
    }
    return $row;
  }

  function fetch_field(){
    return mysqli_fetch_field($this->result);
  }

  function num_fields(){
    return mysqli_num_fields($this->result);
  }

  function num_rows(){
    return mysqli_num_rows($this->result);
  }

  function seek($i){
    return mysqli_data_seek($this->result, $i);
  }

  function begin(){
    return mysqli_data_seek($this->result, 0);
  }

  function insert_id(){
    return mysqli_insert_id($this->conn);
  }

  function end(){
    return mysqli_data_seek($this->result, mysqli_num_rows($this->result) - 1);
  }

  function affected_rows(){
    return mysqli_affected_rows($this->conn);
  }

  function close(){
    mysqli_close($this->conn);
  }

  function table_exists($table){
    $this->query('SHOW TABLES FROM ' . $this->db . ' LIKE \'' . $table . '\'');
    return $this->num_rows() > 0;
  }

  function print_table(){
    if($this->num_rows() > 0){
      $row = $this->fetch_array();
      $keys = array_keys($row);
      echo '<table class="table table-bordered table-striped table-condensed"><tr><th>', implode('</th><th>', $keys), '</th></tr>';
      echo '<tr><td>', implode('</td><td>', $row), '</td></tr>';
      
      while($row = $this->fetch_array()){
        echo '<tr><td>', implode('</td><td>', $row), '</td></tr>';
      }
      
      echo '</table>';
    }
    else
      echo "No record !";
  }
}

function mask_field_name(&$a, $key){
  global $masked;
  if(substr($a,0,3) === '___'){
    $a='';
    $masked = true;
  }
  else{
    if(substr($a,0,2) === '__'){
      $a=substr($a,2);
      $masked = true;
    }
  }
}

function mask_field(&$item, $key){
  if(substr($key,0,3) === '___')
    $item = '';
  else
    if(substr($key,0,2) === '__')
      $item = '**********';
}

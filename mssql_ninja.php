<?php

/**
 * Description of mssql_ninja
 * 
 * @author Woody
 */
class mssql_ninja {
  
  // Connection specific information
  public $database = ""; 
  public $server = 'localhost';
  public $db_user = "sa";
  public $db_pass = "password"; 
  public $port = 1433;
  
  // Data specific information
  public $source_table = 'dbo.table';
  public $source_key_column = 'dbo.table.pk';
  public $source_value_column = 'dbo.table.value';
  
  // Object state properties
  public $db_connect;
  public $db_selected;
  
  /**
   * Constructor
   * Setup connection to the external server or fail object creation.
   * @param string $database - Catalog Name
   * @param string $server - Host Name, DNS Name of DB server
   * @param string $db_user - DB Username (sql server accounts Only)
   * @param string $db_pass - DB User (account password)
   * @param string $port  - DB Port (windows Default is 1433)
   */
  function __construct($database,$server='',$db_user='',$db_pass='',$port='') {
    
    $this->database = $database;
    if (!$this->database)
      die("Database is required");
    if ($server)
      $this->server = $server;
    if ($db_user)
      $this->db_user = $db_user;
    if ($db_pass)
      $this->db_pass = $db_pass;
    if ($port)
      $this->port = $port;
    //connection to the database
    $this->db_connect = mssql_connect($this->server, $this->db_user, $this->db_pass);
    if (!$this->db_connect)
      die("Couldn't connect to SQL Server on $this->server");

    //select a database to work with
    $this->db_selected = mssql_select_db($this->database, $this->db_connect);
    if (!$this->db_selected)
      die("Couldn't open database $this->database");
  }
  

  /**
   * Alter the target table and information. This can be called many times to 
   * slowly import information from multiple tables using once connection.
   * @param string $source_table
   * @param string $source_key_column
   * @param string $source_value_column 
   */
  function set_target($source_table,$source_key_column,$source_value_column) {

    $this->source_table = $source_table;
    if (!$this->source_table)
      die("source_table is required");
    $this->source_key_column = $source_key_column;
    if (!$this->source_key_column)
      die("source_key_column is required");
    $this->source_value_column = $source_value_column;
    if (!$this->source_value_column)
      die("$source_value_column is required");
  }
  
  /**
   * The actual act of creating a query and getting that piece of 
   * atomic data you want
   * @param int $primary_key
   * @return string 
   */
  function retrieve_target_info($primary_key) {
    $value = '';
    
    $sql = "
    SELECT 
      $this->source_value_column 
    FROM 
      $this->source_table 
    WHERE 
      $this->source_key_column = $primary_key";

    $result = mssql_query($sql);

    $row = mssql_fetch_row($result); 
    
    $value = (string)$row[0];
    
    return $value;
  }
  
  function __destruct() {
    mssql_close($this->db_connect);
  }
}

?>

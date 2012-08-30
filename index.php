<?php
include_once 'mssql_ninja.php';

  
  // Connection Information
  $database = "mdanderson_interim"; 
  $server = 'FESTUS';
  $db_user = "andrew_martinez";
  $db_pass = "am47m!d9"; 
  $port = 1433;

  // Source Table
  $source_table = 'dbo.display';
  // Primary Key of Source Table
  $source_key_column = 'dbo.display.display_id';
  // Column we want the value from 
  $source_value_column = 'dbo.display.image_src';

  // Let SQL Ninja set up its connection
  $sqlNinja = new mssql_ninja($database, $server, $db_user, $db_pass, $port);
  
  // Tell the SQL Ninja what table we are attacking
  $sqlNinja->set_target($source_table, $source_key_column, $source_value_column);
  
  // Have the ninja strike out for the information.
  $result = $sqlNinja->retrieve_target_info(5);
  
  echo $result;
// echo phpinfo();
  /*
   *
  
////declare the SQL statement that will query the database
$query = "SELECT content from dbo.content ";
$query = "SELECT * from dbo.display WHERE dbo.display.display_id = 5 ";
   */
?>


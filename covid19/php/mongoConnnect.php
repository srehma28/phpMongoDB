<?php
// This function will return a object of mongoBD connection string. 
  function createConnection(){ 

 $host="localhost:27017";
     $userdb="test";
     $database=$userdb.".testing"; 

  // Using the MongoDB\Driver\Manager of new PHP-mongoBD driver, a connection object is created. 
    $m = new MongoDB\Driver\Manager("mongodb://{$host}/{$userdb}");
	// Return of the object. 
	return $m;
  }
  
  function getDb(){
	  return "test";
  }
  
  function getCollection(){
	  return ".testing";
  }
  
  function getCollCat(){
	  return "FIPS";
  }
?>
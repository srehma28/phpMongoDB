<?php
/*
This php script is responsible for receiving the user's selection from front-end, process it, send appropriate queries to
mongoDB and send back data to front-end in html format. 
*/

  // The mongoConnnect.php file is included which contain the connecting string to the mongoDB. It is more efficient way
  // to use a common file for connections. 
  include 'mongoConnnect.php';
  // The PHP function "createConnection" is called which will initiate a connection with mongoDB.
  $m = createConnection();
  $db = getDb();
  $col = getCollection();
  $d="<select class='form-control' multiple data-live-search='true'>";
  $query = null;
  // Here we are using the \MongoDB\Driver\Command funtion of new PHP-mongoDB driver. In the earlier version of drivers 
	// it used to be called simple as Command(). This will create an object of Command which will be later sent as an 
	// argument to executeCommand function.
	$cmd = new \MongoDB\Driver\Command([
	// We are fetching the distinct values from "BBC" collection.
	'distinct' => $col, 
	// We are fetching the distinct values in "masterbrand datafield. 
	'key' => 'masterbrand',
	// We have to option to put additional filters or options also, but here we will just pass null to query. 
	'query' => $query]);
	// Invoke the executeCommand function, which takes the database name "WestCrime" and the command object as argument.
	// The result is saved in a array. 
	$rows = $m->executeCommand($db, $cmd);
	foreach ($rows as $key => $document) {	
	// this is an important step to remove any kind of unnecessary encoding.  	
	$document = json_decode(json_encode($document),true);
	// Sorting in ascending order is done for the values in the array. 
	sort($document["values"]);
	
	// Using the foreach to traverse through the individual records. 
		foreach ($document["values"] as $doc) {	
		if($doc != null){
		// Fetched data is appended to a variable and html formatting is also done.
		$d.=  "<option value='".$doc."'>".$doc."</option>";
		}
		}
	}
	// Sent back the results containing distinct values of Crime Type in Crime collection. 
    echo $d."</select>";
?>
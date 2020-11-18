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
  $col = getCollProg();
  // The data from post request is fetched from the arrays with the help of identifiers and saved in a variable. 
  // isset is a funtion which checks of data is set or not. 
  // isset(**) ? ** : ** --> This is a method of using inline if..then..else statement. 
  
  $programme 	 	 = isset($_POST['programme']) ? $_POST['programme'] : "" ;
  $categories 	 = isset($_POST['categories'])?$_POST['categories']: "";
  $channel 	 = isset($_POST['channel'])?$_POST['channel']: "";
  $limit 	 	 = isset($_POST['limit'])?$_POST['limit']: "";
  $sortfield 	 = isset($_POST['sortfield'])?$_POST['sortfield']: "";
  $sorttype 	 = isset($_POST['sorttype'])?$_POST['sorttype']: "";
  $filter= [];
  $options = [];
  // An HTML table is being formatted. 
  $tbl = "<table id='usertabledata' class='table table-responsive' style='width: 100%'>";
	$tbl .= "<thead class='thead-dark'>";
			$tbl .= "<tr>";
			$tbl .= "<th scope='col'>Pid</th>";
			  $tbl .= "<th scope='col'>Epoch_start</th>";
			  $tbl .= "<th scope='col'>Epoch_end</th>";		
			$tbl .= "<th scope='col'>Masterbrand</th>";			  
			  $tbl .= "<th scope='col' style='width:100px'>Complete_title</th>";
			  
			$tbl .= "</tr>";
		  $tbl .= "</thead>";

// In the below code, first it is checked that the received value is not empty. 
// Then it is stored in an array with definative identifier. 
// This will be used a filter in executeQuery funtion. 



if(empty($_POST['programme']) && empty($_POST['categories']) && empty($_POST['channel']) && empty($_POST['limit']) && empty($_POST['sortfield']) && empty($_POST['sorttype']))
{
	
	// Here we are using the \MongoDB\Driver\Query funtion of new PHP-mongoDB driver, its takes filters and options as arguments.
  // A new object is created and saved in a variable. 
	$query = new \MongoDB\Driver\Query($filter, $options);
	// The function executeQuery is invoked with the Collection name and the query object from previous step as argument. 
	// The data is converted to an array and saved to a variable.	  
	$rows   = $m->executeQuery($db.".".$col, $query)->toArray();
	echo "<h4 class='form-control mb-4'>Total Records Found: ".count($rows)."</h4>";
	//echo count($rows);
	if (count($rows)>0){
	foreach ($rows as $document) {		
	$document = json_decode(json_encode($document),true);
	// Preparing the data. 	
	$tbl .= "<tbody class='text-dark'>
								<tr>
								 <th scope='row'>".$document['pid']."</th>
								 <td>".$document['epoch_start']."</td>
								 <td>".$document['epoch_end']."</td>
								 <td>".$document['masterbrand']."</td>
								 <td style='width:100px'>".$document['complete_title']."</td>
								 </tr>";
	}$tbl .= "</tbody></table>";
	  echo $tbl;
	}
}

else {

// Preparing the regex object. 
	$regexProg = new MongoDB\BSON\Regex ($programme);
	
	
	
// Inserting the value in filter. 
	$filter["complete_title"]=$regexProg;
	
	
	
	
	// Here we are using the \MongoDB\Driver\Query funtion of new PHP-mongoDB driver, its takes filters and options as arguments.
  // A new object is created and saved in a variable. 

				
		
	
	
	if(!empty($categories) && !is_null($categories) && isset($categories))
			{
				// Traversing by the data sent via POST. 
				foreach($categories as $val1)
				{
					// Preparing the regex object. 
					$regexCat = new MongoDB\BSON\Regex ($categories);
				// Inserting the value in filter. 
					$filter["categories"]=$regexCat;	
			}
			}
	if(!empty($channel) && !is_null($channel) && isset($channel))
			{
				// Traversing by the data sent via POST. 
				foreach($channel as $val1)
				{
					
				// Inserting the value in filter. 
					$filter["masterbrand"]=$val1;	
			}
			}
// Declaring some values and initiating them   
	
	
// Checking the sorting preference of the user and preparing the options. 
	if(!empty($sortType) && !empty($sortfield))
		{ if($sortType == "asc")
			{$options['sort'][$sortfield]=1;}
		  else
		  { $options['sort'][$sortfield]=-1;}
		}

// Checking the limiting preference of the user and preparing the options.
	if(!empty($limit) && !empty($sortfield))
		{
			$options['limit']=$limit;
		}

// Here we are using the \MongoDB\Driver\Query funtion of new PHP-mongoDB driver, its takes filters and options as arguments.
				// A new object is created and saved in a variable. 
				$query = new \MongoDB\Driver\Query($filter, $options);
				// The funtion executeQuery is invocked with the Collection name and the query object from previous step as argument. 
				// The data is converted to an array and saved to a variable.
				$rows   = $m->executeQuery($db.".".$col, $query)->toArray();	
	echo "<h4 class='form-control mb-4'>Total Records Found: ".count($rows)."</h4>";
// Checking the availability of data. 
	if (count($rows)>0){
	foreach ($rows as $document) {		
	$document = json_decode(json_encode($document),true);
	// Preparing the data. 	
	$tbl .= "<tbody class='text-dark'>
								<tr>
								 <th scope='row'>".$document['pid']."</th>
								 <td>".$document['epoch_start']."</td>
								 <td>".$document['epoch_end']."</td><td>".$document['masterbrand']."</td>
								 <td style='width:100px'>".$document['complete_title']."</td>
								 </tr>";
	}$tbl .= "</tbody></table>";
	  echo $tbl;
	}
	else echo "<h4 class='form-control mb-4'>No data matches the filter criteria, Please try another search parameter</h4>";
		
}
	

?>	
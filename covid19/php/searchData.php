<?php

  include 'mongoConnnect.php';
  // The PHP function "createConnection" is called which will initiate a connection with mongoDB.
  $m = createConnection();
  $db = getDb();
  $col = getCollection();
  // The data from post request is fetched from the arrays with the help of identifiers and saved in a variable. 
  // isset is a funtion which checks of data is set or not. 
  // isset(**) ? ** : ** --> This is a method of using inline if..then..else statement. 
  
  $covid 	 	 = isset($_POST['covid']) ? $_POST['covid'] : "" ;
  $Fips 	 = isset($_POST['Fips'])?$_POST['Fips']: "";
  $channel 	 = isset($_POST['channel'])?$_POST['channel']: "";
  $limit 	 	 = isset($_POST['limit'])?$_POST['limit']: "";
  $sortfield 	 = isset($_POST['sortfield'])?$_POST['sortfield']: "";
  $sorttype 	 = isset($_POST['sorttype'])?$_POST['sorttype']: "";
  $filter= [];
  $options = [];
  $sort= isset($_POST['sort'])?$_POST['sort']: "";

  $tbl = "<table id='usertabledata' class='table table-responsive' style='width: 100%'>";
	$tbl .= "<thead class='thead-dark'>";
			$tbl .= "<tr>";
			$tbl .= "<th scope='col'>FIPS</th>";
			  $tbl .= "<th scope='col'>Admin2</th>";
			  $tbl .= "<th scope='col'>Province State</th>";
			  $tbl .= "<th scope='col'>Lat</th>";	
			  $tbl .= "<th scope='col'>Long_</th>";
			  $tbl .= "<th scope='col'>Confirmed</th>";	
			  $tbl .= "<th scope='col'>Deaths</th>";
			  $tbl .= "<th scope='col'>Recovered</th>";	
			  $tbl .= "<th scope='col'>Active</th>";		
			  $tbl .= "<th scope='col'>Incidence Rate</th>";	
			  $tbl .= "<th scope='col'>Case-Fatality Ratio</th>";		  

			  
			$tbl .= "</tr>";
		  $tbl .= "</thead>";

// In the below code, first it is checked that the received value is not empty. 
// Then it is stored in an array with definative identifier. 
// This will be used a filter in executeQuery funtion. 



if(empty($_POST['covid']) && empty($_POST['Fips']) && empty($_POST['sort'])  && empty($_POST['channel']) && empty($_POST['limit']) && empty($_POST['sortfield']) && empty($_POST['sorttype']))
{
	
	// Here we are using the \MongoDB\Driver\Query funtion of new PHP-mongoDB driver, its takes filters and options as arguments.
  // A new object is created and saved in a variable. 
	$query = new \MongoDB\Driver\Query($filter);
	// The function executeQuery is invoked with the Collection name and the query object from previous step as argument. 
	// The data is converted to an array and saved to a variable.	  
	$rows   = $m->executeQuery($db.$col, $query)->toArray();
	echo "<h4 class='form-control mb-4'>Total Records Found: ".count($rows)."</h4>";
	//echo count($rows);
	if (count($rows)>0){
	foreach ($rows as $document) {		
	$document = json_decode(json_encode($document),true);
	// Preparing the data. 	
	$tbl .= "<tbody class='text-dark'>
								<tr>
								 <th scope='row'>".$document['FIPS']."</th>
								 <td>".$document['Admin2']."</td>
								 <td>".$document['Province State']."</td>
								 <td>".$document['Lat']."</td>
								 <td>".$document['Long_']."</td>
								 <td>".$document['Confirmed']."</td>
								 <td>".$document['Deaths']."</td>
								 <td>".$document['Recovered']."</td>
								 <td>".$document['Active']."</td>
								 <td>".$document['Incidence Rate']."</td>
								 <td>".$document['Case-Fatality Ratio']."</td>
								
								 </tr>";
	}$tbl .= "</tbody></table>";
	  echo $tbl;
	}
}

else {

	

// Preparing the regex object. 
	$regexProg = new MongoDB\BSON\Regex ($Fips);
	
	
	
// Inserting the value in filter. 
	$filter['Admin2']=$regexProg;

 //Checking the limiting preference of the user and preparing the options.

	if(!empty($limit) )
		{
			$options = ['Admin2' => -1];
			// ,              //sets limit
			//  'sort' => ['Admin2' => $sort],             //sorts alphatic in admin column
			//  'projection' =>['_id'=> 0];

		}

// Here we are using the \MongoDB\Driver\Query funtion of new PHP-mongoDB driver, its takes filters and options as arguments.
				// A new object is created and saved in a variable. 
				$query = new \MongoDB\Driver\Query($filter, $options);
				// The funtion executeQuery is invocked with the Collection name and the query object from previous step as argument. 
				// The data is converted to an array and saved to a variable.
				$rows   = $m->executeQuery($db.$col, $query)->toArray();	
		
	echo "<h4 class='form-control mb-4'>Total Records Found: ".count($rows)."</h4>";
// Checking the availability of data. 
		
	if (count($rows)>0){
	foreach ($rows as $document) {		
	$document = json_decode(json_encode($document),true);
	// Preparing the data. 	
	$tbl .= "<tbody class='text-dark'>
								<tr>
								<th scope='row'>".$document['FIPS']."</th>
								<td>".$document['Admin2']."</td>
								<td>".$document['Province State']."</td>
								<td>".$document['Lat']."</td>
								<td>".$document['Long_']."</td>
								<td>".$document['Confirmed']."</td>
								<td>".$document['Deaths']."</td>
								<td>".$document['Recovered']."</td>
								<td>".$document['Active']."</td>
								<td>".$document['Incidence Rate']."</td>
								<td>".$document['Case-Fatality Ratio']."</td>
								 </tr>";
	}$tbl .= "</tbody></table>";
	  echo $tbl;
	}
	else echo "<h4 class='form-control mb-4'>No data matches the filter criteria, Please try another search parameter</h4>";
		
}

	

?>	
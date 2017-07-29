<?php
$settings = parse_ini_file("settings.ini");

$servername = $settings["server"];
$username = $settings["user"];
$password = $settings["pass"];
$dbname = $settings["db"];

// areasplit is calculated from ( max - min ) / 3
// value is the actual value per entry in the db
function setColoring($value, $areasplit){

	// echo "value:" .$value . "<br>";
	// echo "split:" .$areasplit . "<br>";

	// Defining colors for the different categories
	// Format #RRGGBB
	// 66 - 100% -> red
	$highest = '#FF0000';
	// 33 - 65% -> yellow
	$midrange = '#f4ee42';
	// 0 - 33% -> green
	$lowest = '#00FF00';
	// no data available -> black
	$nonexisting = '#000000';

	$color = '#000000';

	// expecting it to be wether 0.xx 1.xx oder 2.xx
	// with floor -> 0, 1 or 2, or nonexisting if no value is present
	$category = floor($value / $areasplit);


	if(!empty($value) || !is_null($value)){
		// Determining the right category
		switch ($category) {
		    case 2:
				$color = $highest;
		        break;
		    case 1:
		        $color = $midrange;
		        break;
		    case 0:
		        $color = $lowest;
		        break;
		    default:
		        $color = $nonexisting;
		}

	}
	else{
		$color = $nonexisting;
	}
	
	// echo $value . " " . floor($areasplit) . " ". $color . "<br>";
	// echo $value . "<br>";
	return $color;
}


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Setting query string for min/max values to determine categories per entry
$minMax = "SELECT   MIN(crimes) as mincrimes, MAX(crimes) as maxcrimes,
        			MIN(rent) as minrent, MAX(rent) as maxrent
					FROM mesh";
// Executing query
$minMaxResult = $conn->query($minMax);

$mincrimes = 0;
$maxcrimes = 0;

$minrent = 0;
$maxrent = 0;

if ($minMaxResult->num_rows > 0) {
	while($row = $minMaxResult->fetch_assoc()) {
		$mincrimes = $row["mincrimes"];
		$maxcrimes = $row["maxcrimes"];
		// echo "mincrimes".$mincrimes . "<br>";
		// echo "maxcrimes".$maxcrimes . "<br>";

		$minrent = $row['minrent'];
		$maxrent = $row['maxrent'];
		// echo "minrent".$minrent . "<br>";
		// echo "maxrent".$maxrent . "<br>";

    }

    // Calculating the areas of available values
    $crimearea = $maxcrimes - $mincrimes;
    // 0 - 33 - 66 - 100
    $crimeareasplit = $crimearea / 3;
    // echo "crimeareasplit".$crimeareasplit . "<br>";


    $rentarea = $maxrent - $minrent;
    $rentareasplit = $rentarea / 3;
    // echo "rentareasplit".$rentareasplit . "<br>";

}


// Setting query string for geospatial data
$sql = "SELECT  ST_AsGeoJSON(SHAPE) as shape,
				crimes,
				rent
		FROM mesh";

// Executing query
$result = $conn->query($sql);


if ($result->num_rows > 0) {

	$output = "";

	// appending the wrapping JSON structure
    $output .= "{\"type\": \"FeatureCollection\",\"features\":[";

    // appending each row of the result set
    while($row = $result->fetch_assoc()) {
    	// Adding leading JSON structure
        $output .= "{\"type\": \"Feature\",";

        // Determining the right category
        $crimecolor = setColoring($row["crimes"], $crimeareasplit);
        // echo $crimecolor . "<br>";

        // printing extra line to get a nicer overview
        // echo "<br>";

        $rentcolor = setColoring($row["rent"], $rentareasplit);
        // echo $rentcolor . "<br>";

        // printing two extra lines to get a nicer overview
        // echo "<br>";
        // echo "<br>";

        // appending properties
        $output .= "\"properties\":{";

		// Set the coloring due to the GET parameter
		if(isset($_GET["coloring"])) {
			switch($_GET["coloring"]){
				case "rent":
					$output .= "\"color\":\"".$rentcolor."\",";
					break;
				case "crimes":
					$output .= "\"color\":\"".$crimecolor."\",";
					break;
				default:
					// default black if param value is not matching
					$output .= "\"color\":\"rgb(0,0,0)\",";
			}
		}

		// Adding additional information
        $output .= "\"crimes\":\"".$row["crimes"]."\",\"rent\":\"".$row["rent"]."\"},";

        // appending geometry (coordinates)
        $output .= "\"geometry\": ".$row["shape"]. "},";

    }

    // removing last, unnecessary comma from list
    $output = rtrim($output,",");

    // appending the wrapping JSON structure
    $output .= " ]}";

    echo $output;
} else {
    echo "0 results";
}



$conn->close();
?>
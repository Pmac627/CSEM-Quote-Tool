<?php /* options.php */
    require_once('dbc.php');

	// Set return output type
	header('content-type: text/plain; charset=utf-8');

	// Allows the data to be returned between pages!
	header('Access-Control-Allow-Origin: *');

	// Get r from Query String
	if(htmlspecialchars($_GET['q'])) {
		$r = htmlspecialchars($_GET['q']);
	} else {
		die("Invalid request.");
	}

	// Get c from Query String
	if(htmlspecialchars($_GET['c'])) {
		$c = htmlspecialchars($_GET['c']);
	}

	// Connect to DB
	$con = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
	if(!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	mysqli_select_db($con, DATABASE);

	// Select OptionID and OptionName from DB based on OptionCategoryID
	$sql = "SELECT OptionID, OptionName FROM Options WHERE OptionCategoryID = '".$r."'";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)) {
        if($c != NULL) {
            if($c == $row['OptionID']) {
                echo "<option value='" . $row['OptionID'] . "' selected='selected'>" . $row['OptionName'] . "</option>";
            } else {
                echo "<option value='" . $row['OptionID'] . "'>" . $row['OptionName'] . "</option>";
            }
        } else {
            echo "<option value='" . $row['OptionID'] . "'>" . $row['OptionName'] . "</option>";
        }
	}

	// Never leave DB connection open!
	mysqli_close($con);
?>
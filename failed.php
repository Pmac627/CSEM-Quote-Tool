<?php /* failed.php */
    require_once('src/sessions.class.php');
	$session = new Session();

	// Communication between pages requires Sessions
    //session_start();

	// Save ErrArray, OrigArray and SiteID
    $ErrArray = $_SESSION['ErrArray'];
    $OrigArray = $_SESSION['OrigArray'];
	$SiteID = $_SESSION['siteID'];

	// Kill the remaining Session
	//session_unset();

	// Initialize SITE variables with DEFAULT values
	$name = SITENAME;

	// Connect to DB
	$con = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
	if(!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	mysqli_select_db($con, DATABASE);

	// Select SITE information from DB
	$sql = "SELECT SiteName FROM Site WHERE SiteID = '" . $SiteID . "'";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)) {
		$name = $row['SiteName'];
	}

	// Never leave DB connection open!
	mysqli_close($con);
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <style type="text/css">
        body { background-color: white; }
    </style>
</head>
<body>
    <h1><?php echo $name; ?> Quote Generator</h1>
    <p>FAILED!</p>
    <p><?php echo var_dump($ErrArray); ?></p>
    <p>ORIGINAL!</p>
    <p><?php echo var_dump($OrigArray); ?></p>
</body>
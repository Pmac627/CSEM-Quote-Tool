<?php /* registered.php */
    require_once('src/sessions.class.php');
	$session = new Session();

	$sessID = $_GET['PHPSESSID'];

	// Communication between pages requires Sessions
    //session_start();

	//if($sessID != "") {
	//	session_id($sessID);
	//}

	// Save Email, QuoteID and SiteID
	$Email = $_SESSION['email'];
	$RegID = $_SESSION['registrationID'];
	$SiteID = $_SESSION['siteID'];

	// Kill the remaining Session
	$session->delete();
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
    <p>
		Your registration has been delivered to <b><?php echo $Email; ?></b>.
	</p>
    <p>
		Thank you for registering with <?php echo $name; ?> Inc.
	</p>
    <p>
		Please see email for further instructions for authorizing your registration.
	</p>
    <p>
		Thank you!
	</p>
</body>
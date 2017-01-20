<?php /* adm/logout.php */
    require_once('../src/sessions.class.php');
	$session = new Session();

	// Initialize SITE variables with DEFAULT values
	$url = URL;

	// Connect to DB
	$con = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
	if(!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	mysqli_select_db($con, DATABASE);

	// Select SITE information from DB
	$sql = "SELECT SiteURL FROM Site WHERE SiteID = '1'";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)) {
		$url = $row['SiteURL'];
	}

	// Never leave DB connection open!
	mysqli_close($con);

	$session->delete();
	//session_unset();
	header("location: " . $url . "adm/index.php");
?>
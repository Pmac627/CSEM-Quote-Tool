<?php /* adm/login.php */
    require_once('../src/sessions.class.php');
	$session = new Session();

	function santize_input($data) {
		return htmlspecialchars(stripslashes(trim($data)));
	}

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

    function validLogin($usr, $pwd) {
        $sql = "SELECT UserID FROM Admin WHERE Username = '" . $usr . "' AND Password = '" . hash('sha256', $pwd) . "'";
        $result = mysqli_query($con, $sql);
		$userID = "";
		while($row = mysqli_fetch_array($result)) {
			$userID = $row['UserID'];
		}

		return $userID;
    }

	$UserID = validLogin(santize_input($_POST['usr']), santize_input($_POST['pwd']));

	// Never leave DB connection open!
	mysqli_close($con);

    if(!is_numeric($UserID)) {
        header("location: " . $url . "adm/index.php");
    } else {
		$_SESSION['UserID'] = $UserID;
		$_SESSION['Username'] = santize_input($_POST['usr']);
        header("location: " . $url . "adm/dash.php");
    }
?>
<?php /* adm/index.php */
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title></title>
	<style type="text/css">
		html { background-color: #ddd; }
		.adm-login {
			background-color: #fff;
			position: relative;
			margin: 200px auto;
			padding: 10px;
			width: 300px;
			box-shadow: 2px 2px 2px #eee;
		}
		.adm-login input {
			width: 75%;
			margin: 3px 0;
		}
		.adm-logo {
			text-align: center;
		}
	</style>
</head>
<body>
	<div class="adm-login">
		<div class="adm-logo"><img src="<?php echo $url; ?>img/CSEMLogo.jpg" alt="CSEM" width="173" /></div>
		<form name="login" action="<?php echo $url; ?>adm/login.php" method="post">
			<div>Username: <input type="text" name="usr" /></div>
			<div>Password: <input type="password" name="pwd" /></div>
			<div class="adm-logo"><button type="submit" name="submit">Login</button></div>
		</form>
	</div>
</body>
</html>
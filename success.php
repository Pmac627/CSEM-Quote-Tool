<?php /* success.php */
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
	$QuoteID = $_SESSION['quoteID'];
	$SiteID = $_SESSION['siteID'];

	// Kill the remaining Session
	//session_unset();

	// Initialize SITE variables with DEFAULT values
	$name = SITENAME;
	$url = URL;
	$phone = SITEPHONE;

	// Connect to DB
	$con = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
	if(!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	mysqli_select_db($con, DATABASE);

	// Select SITE information from DB
	$sql = "SELECT SiteName, SitePhone FROM Site WHERE SiteID = '" . $SiteID . "'";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)) {
		$name = $row['SiteName'];
		$phone = $row['SitePhone'];
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
		.register { 
			border: 1px solid #ccc;
			padding: 10px;
			background-color: #3399FF;
			color: #efefef;
			text-decoration: none;
			font-weight: bold;
			border-radius: 10px; 
		}
    </style>
</head>
<body>
    <p>
		Your quote has been delivered to <b><?php echo $Email; ?></b>.
	</p>
    <p>
		Thank you for obtaining a quote using <?php echo $name; ?>'s quote generator. We have delivered a copy of your quote to your email address. 
		This quote has been generated as a reference only and may differ from the final quote.
	</p>
    <p>
		You can register for this class now if you wish. To do so, click the link below:
	</p>
    <p style="text-align:center;">
		<a href="<?php echo $url; ?>register.php?PHPSESSID=<?php echo $sessID; ?>&q=<?php echo $QuoteID; ?>&s=<?php echo $SiteID; ?>" class="register">Register for this class</a>
	</p>
    <p>
		You can also register from the email that has been sent to you. Just click on the register link.
	</p>
    <p>
		<b>NOTICE:</b> Price is valid for day of quote only. Confirmation must be received on the same day that the quote is generated. 
		Quoted cost shall include all travel, labor, course materials and certificates of completion. Additional students will be 
		charged at $135/student/day. When your authorized company representative affixes their signature to this proposal, the 
		proposal becomes a binding contract between <?php echo $name; ?>, Inc. and your firm for schedule and delivery of service. If you have any 
		questions, please feel free to contact <?php echo $name; ?> at <?php echo $phone; ?>.
	</p>
</body>
<?php /* form.php */
    require_once('src/dbc.php');

	// Communication between pages requires Sessions
    session_start();

	// Get RegID from Query String or Session
	if(is_numeric(htmlspecialchars($_GET['r']))) {
		$RegID = htmlspecialchars($_GET['r']);
	} elseif(isset($_SESSION['regID'])) {
		$RegID = $_SESSION['regID'];
	} else {
		die("You never completed a registration.");
	}

	// Initialize variables
	$course_title = $location = $price = $attendees = $quoteID = $company_name = $company_contact = $phone = $fax = $email = $address1 = $address2 = $city = $state = $zip = $notes = "";

	// Initialize SITE variables with DEFAULT values
	$name = SITENAME;
	$logo = SITELOGO;
	$site_phone = SITEPHONE;
	$url = URL;

	// Connect to DB
	$con = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
	if(!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	mysqli_select_db($con, DATABASE);

	// Select Registration info from DB based on RegID
	$sql = "SELECT * FROM Registrations WHERE RegID = '" . $RegID . "'";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)) {
		$SiteID = $row['SiteID'];
		$course_title = $row['RegCourse'];
		$location = $row['RegLocation'];
		$price = $row['RegPrice'];
		$attendees = $row['RegAttendees'];
		$quoteID = $row['QuoteID'];
		$company_name = $row['RegCompanyName'];
		$company_contact = $row['RegCompanyContact'];
		$phone = $row['RegPhone'];
		$fax = $row['RegFax'];
		$email = $row['RegEmail'];
		$address1 = $row['RegAddress1'];
		$address2 = $row['RegAddress2'];
		$city = $row['RegCity'];
		$state = $row['RegState'];
		$zip = $row['RegZip'];
		$notes = $row['RegNotes'];
	}

	// Select SITE information from DB
	$sql = "SELECT SiteName, SiteURL, SiteLogo, SitePhone FROM Site WHERE SiteID = '" . $SiteID . "'";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)) {
		$name = $row['SiteName'];
		$url = $row['SiteURL'];
		$logo = $row['SiteLogo'];
		$site_phone = $row['SitePhone'];
	}

	// Never leave DB connection open!
	mysqli_close($con);

	// Kill the remaining Session
	session_unset();
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title>Your Registration Form</title>
    <link rel="stylesheet" href="<?php echo $url; ?>css/tool.css" />
</head>
<body>
	<div id="reg">
		<div class="reg-logo"><img src="<?php echo $url; ?>img/<?php echo $logo; ?>" alt="<?php echo $name; ?>" width="173" /></div>
		<table class="reg-table">
			<thead>
				<tr>
					<th class="reg-header" colspan="2">
						Course Registration Form
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td class="reg-gap" colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td class="reg-text reg-disclaimer" colspan="2">
						<p>
							<b>Price is valid for day of quote only.</b> Quoted cost shall include all travel, labor, course 
							materials and certificates of completion. Additional students charged at $135/student/day. The 
							Invoice will be modified if additional students are in attendance. Modifications will be based 
							on additional students only. It is the Clients obligation to commit to a minimum number of 
							students in the course.
						</p>
						<br />
						<p>
							Please signify your acceptance of this proposal by providing a signature of an authorized company 
							representative. When your authorized company representative affixes their signature to this 
							proposal, the proposal becomes a binding contract between <?php echo $name; ?>, Inc. and your firm for schedule 
							and delivery of service. If you have any questions, please feel free to contact <?php echo $name; ?>, Inc. at 
							<?php echo $site_phone; ?>.
						</p>
					</td>
				</tr>
				<tr>
					<td class="reg-gap" colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="reg-signature">Authorized Company Representative</div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td class="reg-left">&nbsp;</td>
					<td class="reg-right">&nbsp;</td>
				</tr>
				<tr>
					<td class="reg-left">Course Title:</td>
					<td class="reg-right"><b><?php echo $course_title; ?></b></td>
				</tr>
				<tr>
					<td class="reg-left">Location:</td>
					<td class="reg-right"><?php echo $location; ?></td>
				</tr>
				<tr>
					<td class="reg-left">Price:</td>
					<td class="reg-right">$<?php echo $price; ?></td>
				</tr>
				<tr>
					<td class="reg-left"></td>
					<td class="reg-right reg-text">
						<p>
							Course fees may be paid in advance or to the instructor at the door; otherwise, 
							you will be billed via US Mail. Your certificate will not be issued until 
							payment in full is received.
						</p>
						<p>
							If you are attending a refresher course and were not trained by <?php echo $name; ?>, Inc., 
							please provide proof of current certification in advance of the training day.
						</p>
					</td>
				</tr>
				<tr>
					<td class="reg-left">Attendees:</td>
					<td class="reg-right"><?php echo $attendees; ?></td>
				</tr>
				<tr>
					<td class="reg-left">Quote #:</td>
					<td class="reg-right"><?php echo $quoteID; ?></td>
				</tr>
				<tr>
					<td class="reg-gap" colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td class="reg-title" colspan="2">Company Information</td>
				</tr>
				<tr>
					<td class="reg-left">Company Name:</td>
					<td class="reg-right"><?php echo $company_name; ?></td>
				</tr>
				<tr>
					<td class="reg-left">Company Contact:</td>
					<td class="reg-right"><?php echo $company_contact; ?></td>
				</tr>
				<tr>
					<td class="reg-left">Phone Number:</td>
					<td class="reg-right"><?php echo $phone; ?></td>
				</tr>
				<tr>
					<td class="reg-left">Fax Number:</td>
					<td class="reg-right"><?php echo $fax; ?></td>
				</tr>
				<tr>
					<td class="reg-left">Email:</td>
					<td class="reg-right"><?php echo $email; ?></td>
				</tr>
				<tr>
					<td class="reg-left">Address:</td>
					<td class="reg-right">
					<?php if($address2 != "") {
						echo $address1 . "<br />" . $address2;
					} else {
						echo $address1;
					} ?>
					</td>
				</tr>
				<tr>
					<td class="reg-left">City:</td>
					<td class="reg-right"><?php echo $city; ?></td>
				</tr>
				<tr>
					<td class="reg-left">State:</td>
					<td class="reg-right"><?php echo $state; ?></td>
				</tr>
				<tr>
					<td class="reg-left">Zip:</td>
					<td class="reg-right"><?php echo $zip; ?></td>
				</tr>
				<tr>
					<td class="reg-left">Notes:</td>
					<td class="reg-right"><?php echo $notes; ?></td>
				</tr>
			</tbody>
		</table>
		<div class="reg-copyright">Copyright &copy; <?php echo date('Y'); ?> <?php echo $name; ?>, Inc. All rights reserved</div>
	</div>
</body>
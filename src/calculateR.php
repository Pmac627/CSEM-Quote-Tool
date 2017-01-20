<?php /* calculateR.php */
    require('gump.class.php');
    require_once('sessions.class.php');
	$session = new Session();

	$sessID = $_POST['PHPSESSID'];

	// Communication between pages requires Sessions
    //session_start();

	//if($sessID != "") {
	//	session_id($sessID);
	//}

	// Get SiteID from Query String or Session
	if(is_numeric(htmlspecialchars($_GET['s']))) {
		$SiteID = htmlspecialchars($_GET['s']);
		$_SESSION['siteID'] = $SiteID;
	} elseif(isset($_SESSION['siteID'])) {
		$SiteID = $_SESSION['siteID'];
	} else {
		$SiteID = SITEID;
		$_SESSION['siteID'] = $SiteID;
	}

	// Initialize SITE variables with DEFAULT values
	$name = SITENAME;
	$url = URL;
	$email = SITEEMAIL;
	$emailRecord = SITEEMAILRECORD;
	$emailRecord2 = SITEEMAILRECORD2;
	$phone = SITEPHONE;
	$fax = SITEFAX;
	$logo = SITELOGO;
	$logoW = SITELOGOWIDTH;
	$logoH = SITELOGOHEIGHT;

	// Connect to DB
	$con = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
	if(!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	mysqli_select_db($con, DATABASE);

	// Select SITE information from DB
	$sql = "SELECT SiteName, SiteURL, SiteEmail, SiteEmailRecord, SiteEmailRecord2, SitePhone, SiteFax, SiteLogo, SiteLogoWidth, SiteLogoHeight FROM Site WHERE SiteID = '" . $SiteID . "'";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)) {
		$name = $row['SiteName'];
		$url = $row['SiteURL'];
		$email = $row['SiteEmail'];
		$emailRecord = $row['SiteEmailRecord'];
		$emailRecord2 = $row['SiteEmailRecord2'];
		$phone = $row['SitePhone'];
		$fax = $row['SiteFax'];
		$logo = $row['SiteLogo'];
		$logoW = $row['SiteLogoWidth'];
		$logoH = $row['SiteLogoHeight'];
	}

	// Never leave DB connection open!
	mysqli_close($con);

	// Format the address for display
	function createAddress($addy1, $addy2, $city, $state, $zip) {
		if($addy2 != "") {
			$addy = $addy1 . "<br>" . $addy2;
		} else {
			$addy = $addy1;
		}
		return $addy . "<br>" . $city . ", " . $state . " " . $zip;
	}

	// Validate captcha
    function validate_captcha($checker, $choice) {
        switch ($checker) {
            case '1_securityimage.jpg':
                $checker = 'Safety_Glasses';
                break;
            case '2_securityimage.jpg':
                $checker = 'Ear_Muffs';
                break;
            case '3_securityimage.jpg':
                $checker = 'Hard_Hat';
                break;
            case '4_securityimage.jpg':
                $checker = 'Respiratory_Masks';
                break;
            default:
                $checker = 'FALSE';
                break;
        }
        return ($checker === $choice) ? TRUE : FALSE;
	}

	// Create email body for potential CLIENT
    function emailHTML($RegID, $name, $logo, $phone, $fax, $email, $url) {
        $body = "<html lang='en'>
<head>
	<meta charset='utf-8' />
	<title></title>
</head>
<body>
	<table style='width:477px;'>
		<thead>
			<tr>
				<td colspan='2' style='text-align:center;'>
					<img src='" . $url . "img/" . $logo . "' width='173' height='177' alt='" . $name . " - Safety Training | Certification Classes for OSHA, MSHA, DOT, EPA & Marcellus Shale' />
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					Thank you for registering with the Center for Safety and Environmental Management.<br><br>
					Your registration form is available to view using the following button:<br><br>
					<center><a href='" . $url . "form.php?r=" . $RegID . "' target='_blank' style='border: 1px solid #ccc;padding: 10px;background-color: #3399FF;color: #efefef;text-decoration: none;font-weight: bold;border-radius: 10px;'>Registration Form</a></center><br><br>
					An authorizing signature is required before registration is official. Please return the signed registration form to us in one of the following ways:
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan='2'>
					If both of these methods are unavailable to you, feel free to contact us at " . $phone . "<br><br>
					Thank you.<br><br>
					The Center for Safety and Environmental Management
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td style='background-color:#ccc;padding:5px;width:50%;'>
					FAX:
				</td>
				<td style='background-color:#ccc;padding:5px;'>
					SCAN:
				</td>
			</tr>
			<tr>
				<td>
					Open up the registration form and print it out.<br><br>
					Sign the form on the line provided.<br><br>
					Fax this completed form to:<br>
					<b>" . $fax . "</b>
				</td>
				<td>
					Open up the registration form and print it out.<br><br>
					Sign the form on the line provided.<br><br>
					Scan the form back into your computer and send it as an attachment via email to: <a href='mailto:" . $email . "'>" . $email . "</a>
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>";

        return $body;
    }

	// Create email body for RECORD
    function emailHTMLRecord($fields, $course, $location, $price, $address, $name) {
        $body = "<html lang='en'>
<head>
	<meta charset='utf-8' />
	<title></title>
</head>
<body>
	<table>
		<tr>
			<td colspan='2'>
				<h1>A " . $name . " Registration has been completed!</h1>
			</td>
		</tr>
		<tr>
			<td>
				<b>Course:</b>
			</td>
			<td>
				" . $course . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Location:</b>
			</td>
			<td>
				" . $location . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Price:</b>
			</td>
			<td>
				" . $price . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Attendees:</b>
			</td>
			<td>
				" . $fields['attendees'] . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Company Name:</b>
			</td>
			<td>
				" . $fields['company_name'] . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Company Contact:</b>
			</td>
			<td>
				" . $fields['company_contact'] . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Phone:</b>
			</td>
			<td>
				" . $fields['phone'] . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Fax:</b>
			</td>
			<td>
				" . $fields['fax'] . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Email:</b>
			</td>
			<td>
				" . $fields['email'] . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Address:</b>
			</td>
			<td>
				" . $address . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Notes:</b>
			</td>
			<td>
				" . $fields['notes'] . "
			</td>
		</tr>
	</table>
</body>
</html>";

        return $body;
    }

	// Initialize the Validator class GUMP
    $gump = new GUMP();

	// Always sanitize input!
    $fields = $gump->sanitize($_POST);

	// Validator rules
    $validation_rules = array(
        'attendees' => 'required|max_len,4000',
        'company_name' => 'max_len,150',
        'company_contact' => 'required|max_len,60',
        'phone' => 'required|max_len,14',
        'fax' => 'max_len,14',
        'email' => 'required|valid_email|max_len,75',
        'address1' => 'required|max_len,150',
        'address2' => 'max_len,150',
        'city' => 'required|max_len,60',
        'state' => 'required|alpha|max_len,2',
        'zip' => 'required|min_len,5|max_len,10',
        'notes' => 'max_len,2000'
    );

	// Validate input
    $validated_data = $gump->validate($fields, $validation_rules);

	// If valid, check Captcha
	if($validated_data === TRUE) {
		if(!validate_captcha($fields['checker'], $fields['radiobutton'])) {
			$validated_data = array(array(
			   'field' => 'checker',
			   'value' => 'FALSE',
			   'rule'	=> 'validate_captcha',
			   'param' => ''
		   ));
		}
	}

	// If not valid, return to index.php with error messages.
    if($validated_data !== TRUE) {
        $_SESSION['ErrArray'] = $validated_data;
        $_SESSION['OrigArray'] = $fields;
        header("location: " . $url . "register.php?PHPSESSID=" . $sessID . "&q=" . $_SESSION['quoteID'] . "&s=" . $_SESSION['siteID']);
    } else {
		// Clear session arrays
        $_SESSION['ErrArray'] = $_SESSION['OrigArray'] = '';

		// Setup email TO LINE
        $to = $fields['email'];
        $to_record = $emailRecord;
        $to_record2 = $emailRecord2;

		// Setup email SUBJECT LINE
        $subject = $name . " Quote Tool";

		// Set variables for emails
		$course = $_SESSION['course'];
		$location = $_SESSION['location'];
		$price = $_SESSION['price'];

		// Translate address
		$address = createAddress($fields['address1'], $fields['address2'], $fields['city'], $fields['state'], $fields['zip']);

		// Setup email HEADERS
		$headers = "From: " . $email . " <" . $email . ">\r\nMIME-Version: 1.0" . "\r\nContent-type:text/html;charset=UTF-8" . "\r\n";

		// Setup email PARAMETERS
		$params = "-f " . $email;

		// Connect to DB
		$con = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
        if(!$con) {
            die('Could not connect: ' . mysqli_error($con));
        }
        mysqli_select_db($con, DATABASE);

		// Insert Registration data into DB
        $sql = "INSERT INTO Registrations (QuoteID, SiteID, RegDate, RegCourse, RegLocation, RegPrice, RegAttendees, RegCompanyName, RegCompanyContact, RegPhone, RegFax, RegEmail, RegAddress1, RegAddress2, RegCity, RegState, RegZip, RegNotes) VALUES ('" . $_SESSION['quoteID'] . "', '" . $_SESSION['siteID'] . "', now(), '" . $_SESSION['course'] . "', '" . $_SESSION['location'] . "', '" . $_SESSION['price'] . "', '" . $fields['attendees'] . "', '" . $fields['company_name'] . "', '" . $fields['company_contact'] . "', '" . $fields['phone'] . "', '" . $fields['fax'] . "', '" . $fields['email'] . "', '" . $fields['address1'] . "', '" . $fields['address2'] . "', '" . $fields['city'] . "', '" . $fields['state'] . "', '" . $fields['zip'] . "', '" . $fields['notes'] . "')";
        $result = mysqli_query($con, $sql);

		// Save SiteID
		$site_id = $_SESSION['siteID'];

		// Kill the remaining Session
		//session_unset();

		// Set new Session variables
		$_SESSION['email'] = $fields['email'];
		$_SESSION['registrationID'] = mysqli_insert_id($con);
		$_SESSION['siteID'] = $site_id;

		// Get email bodys
        $body = emailHTML($_SESSION['registrationID'], $name, $logo, $phone, $fax, $email, $url);
        $bodyRecord = emailHTMLRecord($fields, $course, $location, $price, $address, $name);

		// Send potential CLIENT email
        mail($to, $subject, $body, $headers, $params);

		// If SENDRECORD is true, send RECORD email
		if(SENDRECORD) {
			mail($to_record, $subject, $bodyRecord, $headers, $params);
			if($to_record2 != "") {
				mail($to_record2, $subject, $bodyRecord, $headers, $params);
			}
		}

		// Never leave DB connection open!
        mysqli_close($con);

		// Send user to registration success page
        header("location: " . $url . "registered.php?PHPSESSID=" . $sessID . "&s=" . $site_id);
    }
?>
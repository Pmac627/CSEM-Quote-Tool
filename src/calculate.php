<?php /* calculate.php */
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

	// Format value into US currency format
    function format($val) {
        //setlocale(LC_MONETARY, 'en_US');
        return number_format(money_format('%i', $val), 2);
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
	$hotel = SITEHOTEL;
	$rental = SITERENTAL;
	$instructor = SITEINSTRUCTOR;
	$perdiem = SITEPERDIEM;
	$books = SITEBOOKS;
	$shipping = SITESHIPPING;

	// Connect to DB
	$con = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
	if(!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	mysqli_select_db($con, DATABASE);

	// Select SITE information from DB
	$sql = "SELECT SiteName, SiteURL, SiteEmail, SiteEmailRecord, SiteEmailRecord2, SitePhone, SiteFax, SiteLogo, SiteLogoWidth, SiteLogoHeight, SiteHotel, SiteRental, SiteInstructor, SitePerDiem, SiteBooks, SiteShipping FROM Site WHERE SiteID = '" . $SiteID . "'";
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
		$hotel = $row['SiteHotel'];
		$rental = $row['SiteRental'];
		$instructor = $row['SiteInstructor'];
		$perdiem = $row['SitePerDiem'];
		$books = $row['SiteBooks'];
		$shipping = $row['SiteShipping'];
	}

	// Never leave DB connection open!
	mysqli_close($con);

	// Calculate the quote
	function calculate($days, $students, $location, $urgency, $class_title, $site_id, $hotel, $rental, $instructor, $perdiem, $books, $shipping) {
		$price = $airfare = $quote = 0;

		// Connect to DB
		$con = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
        if(!$con) {
            die('Could not connect: ' . mysqli_error($con));
        }
        mysqli_select_db($con, DATABASE);

		// Select Base Price from DB based on PriceDays and PriceStudents
        $sql = "SELECT PriceAmount FROM Prices WHERE PriceDays = '" . $days . "' AND PriceStudents = '" . $students . "'";
        $result = mysqli_query($con, $sql);
		while($row = mysqli_fetch_array($result)) {
			$price = $row['PriceAmount'];
		}

		// Never leave DB connection open!
		mysqli_close($con);

		// Get location classification
		$location = transLocation($location);

		// Get airfare fee
		$airfare = getAirfare($location, $urgency);

		// Adjust SITE values by Days
		$hotel = ($hotel * $days);
		$rental = ($rental * $days);
		$perdiem = ($perdiem * $days);
		$books = ($books * $students);

		// If MSHA class, give 25% discount
		//$msha = (substr($class_title, 0, 4) == 'msha') ? 0.75 : 1;

		// Figure discount
		$discount = getDiscount($students);

		$quote = ($hotel + $rental + $perdiem + $books + $airfare + $shipping + $price);

		// Factor in discount, if applicable
		if($discount > 0) {
			$quote = ($quote * $discount);
		}
		//$quote = ($quote * $msha);

		return format($quote);
	}

	// Calculate discount
	function getDiscount($students) {
		$discount = 0;
		for($i = 1; $i <= $students; $i++) {
			if($i % 5 == 0) {
				if($i > 9) {
					$discount .= 1.5;
				}
			}
		}

		if($discount > 0) {
			$discount = ((100 - $discount) / 100);
		}
		return $discount;
	}

	// Calculate airfare
	function getAirfare($location, $urgency) {
		switch($urgency) {
			case '1':
				switch($location) {
					case 'E':
						return 670;
						break;
					case 'C':
						return 925;
						break;
					case 'W':
						return 1325;
						break;
					case 'A':
						return 1147;
						break;
					case 'H':
						return 1551;
						break;
					default:
						return 1551;
						break;
				}
				break;
			case '2':
				switch($location) {
					case 'E':
						return 592;
						break;
					case 'C':
						return 700;
						break;
					case 'W':
						return 1000;
						break;
					case 'A':
						return 847;
						break;
					case 'H':
						return 1326;
						break;
					default:
						return 1551;
						break;
				}
				break;
			case '3':
				switch($location) {
					case 'E':
						return 486;
						break;
					case 'C':
						return 475;
						break;
					case 'W':
						return 675;
						break;
					case 'A':
						return 805;
						break;
					case 'H':
						return 1063;
						break;
					default:
						return 1551;
						break;
				}
				break;
			default:
				return 1551;
				break;
		}
	}

	// Convert Location to State Zone
    function transLocation($location) {
        switch($location) {
            case 'AZ':
            case 'CA':
            case 'CO':
            case 'ID':
            case 'KS':
            case 'MT':
            case 'NE':
            case 'NV':
            case 'NM':
            case 'ND':
            case 'OK':
            case 'OR':
            case 'SD':
            case 'TX':
            case 'UT':
            case 'WA':
            case 'WY':
                return 'W';
                break;
            case 'CT':
            case 'DE':
            case 'ME':
            case 'MD':
            case 'MA':
            case 'NH':
            case 'NJ':
            case 'NY':
            case 'OH':
            case 'PA':
            case 'RI':
            case 'VA':
            case 'WV':
            case 'DC':
                return 'E';
                break;
            case 'AL':
            case 'AR':
            case 'FL':
            case 'GA':
            case 'IA':
            case 'IL':
            case 'IN':
            case 'KY':
            case 'LA':
            case 'MI':
            case 'MN':
            case 'MO':
            case 'MS':
            case 'NC':
            case 'SC':
            case 'TN':
            case 'VT':
            case 'WI':
                return 'C';
                break;
            case 'AK':
                return 'A';
                break;
            case 'HI':
                return 'H';
                break;
            default:
                return 'H';
                break;
        }
    }

	// Convert Urgency to text
    function transUrgency($urgency) {
        switch($urgency) {
            case '1':
                return "1-13 days";
                break;
            case '2':
                return "14-20 days";
                break;
            case '3':
                return "21 or more days";
                break;
            default:
                return "N/A";
                break;
        }
    }

	// Check if Students is less than MINIMUMSTUDENTS
	function transStudents($students) {
        return ($students < MINIMUMSTUDENTS) ? TRUE : FALSE;
    }

	// Grab class name from DB
    function transClass($class_title) {
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

	// Create email body for RECORD
	// emailHTMLRecord($fields, $course, $students, $days, $estimate, $students_old, $name);
    function emailHTMLRecord($fields, $course, $students, $days, $estimate, $students_old, $name) {
		if($students_old > 0) {
			$students_old = " (" . $students_old . ")";
		} else {
			$students_old = "";
		}
		if($days > 1) {
			$days = " (" . $days . ")";
		} else {
			$days = "";
		}
        $body = "<html lang='en'>
<head>
	<meta charset='utf-8' />
	<title></title>
</head>
<body>
	<table>
		<tr>
			<td colspan='2'>
				<h1>We received another " . $name . " Quote!</h1>
			</td>
		</tr>
		<tr>
			<td>
				<b>Date:</b>
			</td>
			<td>
				" . date("m/d/Y g:i:s") . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Requester:</b>
			</td>
			<td>
				" . $fields['name'] . "
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
				<b>Phone:</b>
			</td>
			<td>
				" . $fields['phone'] . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Urgency:</b>
			</td>
			<td>
				" . $fields['urgency'] . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Location:</b>
			</td>
			<td>
				" . $fields['location'] . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Days:</b>
			</td>
			<td>
				" . $fields['days'] . $days . " 
			</td>
		</tr>
		<tr>
			<td>
				<b>Students:</b>
			</td>
			<td>
				" . $fields['students'] . $students_old . "
			</td>
		</tr>
		<tr>
			<td>
				<b>Class Title:</b>
			</td>
			<td>
				" . $course . " (" . $fields['class_title'] . ")
			</td>
		</tr>
		<tr>
			<td>
				<b>Estimated Quote:</b>
			</td>
			<td>
				$" . $estimate . "
			</td>
		</tr>
	</table>
</body>
</html>";

        return $body;
    }

	// Create email body for potential CLIENT
	// emailHTML($QuoteID, $fields, $course, $students, $days, $days_old, $estimate, $students_old, $name, $logo, $logoW, $logoH, $phone, $fax);
	function emailHTML($QuoteID, $fields, $course, $students, $days, $days_old, $estimate, $students_old, $name, $logo, $logoW, $logoH, $phone, $fax, $url) {
		if($students_old > 0) {
			if($students_old = 1) {
				$students_old = "You specified " . $students_old . " student.";
			} else {
				$students_old = "You specified " . $students_old . " students.";
			}
		} else {
			$students_old = "";
		}
		if($days > 1) {
			if($days_old = 1) {
				$days_old = "You specified " . $days_old . " day.";
			} else {
				$days_old = "You specified " . $days_old . " days.";
			}
		} else {
			$days_old = "";
		}
        $body = "<html lang='en'>
<head>
	<meta charset='utf-8' />
	<title></title>
</head>
<body>
		<table style='width:600px;'>
		<thead>
			<tr>
				<td style='width:300px;'>
					<img src='" . $url . "img/" . $logo . "' style='margin-left:85px;' width='" . $logoW . "' height='" . $logoH . "' alt='" . $name . " - Safety Training | Certification Classes for OSHA, MSHA, DOT, EPA & Marcellus Shale' />
				</td>
				<td style='vertical-align:top;'>
					<b>Your " . $name . " Online Quote:</b><br>
					Quote ID: " . $QuoteID . "
				</td>
			</tr>
			<tr>
				<td style='vertical-align:top;'>
					<b>Attn:</b> " . $fields['name'] . "<br>
					<b>Phone:</b> " . $fields['phone'] . "<br>
					<b>Email:</b> <a href='mailto:" . $fields['email'] . "'>" . $fields['email'] . "</a><br>
				</td>
				<td style='vertical-align:top;'>
					<b>Date:</b> " . DATE("m/d/Y") . "
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan='2' style='text-align:justify;padding-top:15px;font-size:12px;'>
					<b>NOTICE:</b> Price is valid for day of quote only. Confirmation must be received on the same day 
					that the quote is generated. Quoted cost shall include all travel, labor, course materials and 
					certificates of completion. Additional students will be charged at $135/student/day.
					<br><br>
					When your authorized company representative affixes their signature to this proposal, the proposal 
					becomes a binding contract between " . $name . ", Inc. and your firm for schedule and delivery of service. 
					If you have any questions, please feel free to contact " . $name . " at " . $phone . ". Please fax proposal 
					to " . $name . " at " . $fax . ".
					<br><br>
					Payment is due prior to delivery of services.
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<td style='min-height: 40px;height: 40px;'>&nbsp;</td>
				</td>
			</tr>
			<tr>
				<td>
					<div style='width: 300px;font-size: 11px;text-align: right;margin: 0 75px 20px auto;border-top: 1px solid #000;'>Authorized Company Representative</div>
				</td>
				<td>
					<div style='width: 150px;font-size: 11px;text-align: right;margin: 0 75px 20px auto;border-top: 1px solid #000;'>Date</div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td colspan='2' style='padding-top:15px;'>
					<table style='border:1px solid #000;border-collapse:collapse;width:95%;margin:10px auto;'>
						<thead>
							<tr style='background-color:#3399FF;'>
								<th style='border:1px solid #000;padding:3px;width:40%;'>Product</th>
								<th style='border:1px solid #000;padding:3px;width:15%;'>Course No.</th>
								<th style='border:1px solid #000;padding:3px;width:10%;'>Days</th>
								<th style='border:1px solid #000;padding:3px;width:20%;'>Student Qty.</th>
								<th style='border:1px solid #000;padding:3px;width:15%;'>Location</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style='border:1px solid #000;padding:3px;'>" . $course . "</td>
								<td style='border:1px solid #000;padding:3px;text-align:center;'>" . $fields['class_title'] . "</td>
								<td style='border:1px solid #000;padding:3px;text-align:center;'>" . $days . "</td>
								<td style='border:1px solid #000;padding:3px;text-align:center;'>" . $fields['students'] . "</td>
								<td style='border:1px solid #000;padding:3px;text-align:center;'>" . $fields['location'] . "</td>
							</tr>
							<tr>
								<td style='border:1px solid #000;padding:3px;'><br></td>
								<td style='border:1px solid #000;padding:3px;'></td>
								<td style='border:1px solid #000;padding:3px;'></td>
								<td style='border:1px solid #000;padding:3px;'></td>
								<td style='border:1px solid #000;padding:3px;'></td>
							</tr>
							<tr>
								<td style='border:1px solid #000;padding:3px;'><br></td>
								<td style='border:1px solid #000;padding:3px;'></td>
								<td style='border:1px solid #000;padding:3px;'></td>
								<td style='border:1px solid #000;padding:3px;'></td>
								<td style='border:1px solid #000;padding:3px;'></td>
							</tr>
							<tr>
								<td style='border:1px solid #000;padding:3px;'></td>
								<td style='border:1px solid #000;padding:3px;'></td>
								<td style='border:1px solid #000;padding:3px;'></td>
								<td style='border:1px solid #000;padding:3px;text-align:right;'>Total:</td>
								<td style='border:1px solid #000;padding:3px;text-align:center;'>$" . $estimate . "</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>";
		if($students) {
			$body .= "
			<tr>
				<td colspan='2' style='color:#ff0000;text-align:justify;padding-top:15px;font-style:italic;font-weight:bold;font-size:12px;'>
					Please Note: " . $students_old . " The minimum number of students required for 
					this class is " . MINIMUMSTUDENTS . ". An adjustment has been made to increase the number of students attending this class 
					to meet the minimum requirement.
				</td>
			</tr>";
		}
		if($days > 1) {
			$body .= "<tr>
				<td colspan='2' style='color:#ff0000;text-align:justify;padding-top:15px;font-style:italic;font-weight:bold;font-size:12px;'>
					Please Note: " . $days_old . " The number of days required for this class as set 
					by OSHA is " . $days . " days. An adjustment has been made to the number of days you have chosen to comply with the standard.
				</td>
			</tr>";
		}
		$body .= "
		</tbody>
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
        'type' => 'required|alpha|max_len,4',
        'class_title' => 'required|alpha_dash|max_len,10',
        'students' => 'required|numeric|max_len,3|min_numeric,1|max_numeric,100',
        'days' => 'required|numeric|max_len,2|min_numeric,1|max_numeric,10',
        'location' => 'required|alpha|exact_len,2',
        'urgency' => 'required|numeric|max_len,1|min_numeric,1|max_numeric,3',
        'email' => 'required|valid_email|max_len,75',
        'name' => 'required|valid_name|max_len,60',
        'phone' => 'required|max_len,14',
        'radiobutton' => 'required|max_len,17',
        'checker' => 'required|max_len,19'
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
        header("location: " . $url . "index.php?PHPSESSID=" . $sessID . "&s=" . $_SESSION['siteID']);
    } else {
		// Clear session arrays
        $_SESSION['ErrArray'] = $_SESSION['OrigArray'] = '';

		// Setup email TO LINE
        $to = $fields['email'];
        $to_record = $emailRecord;
        $to_record2 = $emailRecord2;

		// Setup email SUBJECT LINE
        $subject = $name . " Quote Tool";

		// Make sure minimum students is reached
		$students = transStudents($fields['students']);
		$students_old = 0;
        if($students) {
			$students_old = $fields['students'];
			$fields['students'] = MINIMUMSTUDENTS;
		}

		// Initialize variables
		$course = "";
		$days = 1;

		// Connect to DB
		$con = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
        if(!$con) {
            die('Could not connect: ' . mysqli_error($con));
        }
        mysqli_select_db($con, DATABASE);

		// Select Class Name from DB based on OptionID
        $sql = "SELECT OptionName, OptionDays FROM Options WHERE OptionID = '" . $fields['class_title'] . "'";
        $result = mysqli_query($con, $sql);
		while($row = mysqli_fetch_array($result)) {
			$course = $row['OptionName'];
			$days = $row['OptionDays'];
		}

		// Generate QUOTE
		$estimate = calculate($days, $fields['students'], $fields['location'], $fields['urgency'], $fields['class_title'], $_SESSION['siteID'], $hotel, $rental, $instructor, $perdiem, $books, $shipping);

		// Translate urgency
		$urgency = $fields['urgency'];
        $fields['urgency'] = transUrgency($fields['urgency']);

		// Setup email HEADERS
		$headers = "From: " . $email . " <" . $email . ">\r\nMIME-Version: 1.0" . "\r\nContent-type:text/html;charset=UTF-8" . "\r\n";

		// Setup email PARAMETERS
		$params = "-f " . $email;

		// Insert Quote data into DB
        $sql = "INSERT INTO Quotes (QuoteDate, QuoteType, QuoteClassTitle, QuoteStudents, QuoteDays, QuoteUrgency, QuoteEmail, QuoteName, QuotePhone, QuoteLocation, QuotePrice) VALUES (now(), '" . $fields['type'] . "', '" . $fields['class_title'] . "', '" . $fields['students'] . "', '" . $days . "', '" . $urgency . "', '" . $fields['email'] . "', '" . $fields['name'] . "', '" . $fields['phone'] . "', '" . $fields['location'] . "', '" . $estimate . "')";
        $result = mysqli_query($con, $sql);

		// Save SiteID
		$site_id = $_SESSION['siteID'];

		$days_old = $fields['days'];

		// Kill the remaining Session
		//session_unset();

		// Set new Session variables
		$_SESSION['email'] = $fields['email'];
		$_SESSION['quoteID'] = mysqli_insert_id($con);
		$_SESSION['siteID'] = $site_id;

		// Get email bodys
        $body = emailHTML($_SESSION['quoteID'], $fields, $course, $students, $days, $days_old, $estimate, $students_old, $name, $logo, $logoW, $logoH, $phone, $fax, $url);
        $bodyRecord = emailHTMLRecord($fields, $course, $students, $days, $estimate, $students_old, $name);

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

		// Send user to success page
        header("location: " . $url . "success.php?PHPSESSID=" . $sessID . "&s=" . $site_id);
    }
?>
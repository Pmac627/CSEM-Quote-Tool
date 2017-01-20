<?php /* register.php */
    require_once('src/sessions.class.php');
	$session = new Session();

	$sessID = $_GET['PHPSESSID'];

	// Communication between pages requires Sessions
    //session_start();

	//if($sessID != "") {
	//	session_id($sessID);
	//}

	// Initialize array variables
    $ary = array();
    $err = array();

	// Get QuoteID from Query String or Session
	if(is_numeric(htmlspecialchars($_GET['q']))) {
		$QuoteID = htmlspecialchars($_GET['q']);
		$_SESSION['quoteID'] = $QuoteID;
	} elseif(isset($_SESSION['quoteID'])) {
		$QuoteID = $_SESSION['quoteID'];
	} else {
		die("You never received a quote.");
	}

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

	// Connect to DB
	$con = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
	if(!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	mysqli_select_db($con, DATABASE);

	// Select SITE information from DB
	$sql = "SELECT SiteName, SiteURL FROM Site WHERE SiteID = '" . $SiteID . "'";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)) {
		$name = $row['SiteName'];
		$url = $row['SiteURL'];
	}

	if(!isset($_SESSION['course']) || !isset($_SESSION['location']) || !isset($_SESSION['price'])) {
		// Select Quote information from DB based on QuoteID
		$sql = "SELECT o.OptionName, q.QuoteLocation, q.QuotePrice, q.QuoteName, q.QuoteEmail, q.QuotePhone FROM Quotes q LEFT JOIN Options o ON o.OptionID = q.QuoteClassTitle WHERE QuoteID = '" . $QuoteID . "'";
		$result = mysqli_query($con, $sql);
		while($row = mysqli_fetch_array($result)) {
			$ary['course'] = $row['OptionName'];
			$ary['location'] = $row['QuoteLocation'];
			$ary['price'] = $row['QuotePrice'];
			$ary['company_contact'] = $row['QuoteName'];
			$ary['email'] = $row['QuoteEmail'];
			$ary['phone'] = $row['QuotePhone'];
		}
	} else {
		$ary['course'] = $_SESSION['course'];
		$ary['location'] = $_SESSION['location'];
		$ary['price'] = $_SESSION['price'];
	}

	// Never leave DB connection open!
	mysqli_close($con);

	$_SESSION['quoteID'] = $QuoteID;
	$_SESSION['siteID'] = $SiteID;
	$_SESSION['course'] = $ary['course'];
	$_SESSION['location'] = $ary['location'];
	$_SESSION['price'] = $ary['price'];

	// If there are errors, convert them into JSON for JS validator
    if($_SESSION['ErrArray']) {
		// Default as many fields to their previous values as possible
        $ary['attendees'] = $_SESSION['OrigArray']['attendees'];
        $ary['company_name'] = ' value="' . $_SESSION['OrigArray']['company_name'] . '"';
        $ary['company_contact'] = $_SESSION['OrigArray']['company_contact'];
        $ary['phone'] = $_SESSION['OrigArray']['phone'];
        $ary['fax'] = ' value="' . $_SESSION['OrigArray']['fax'] . '"';
        $ary['email'] = $_SESSION['OrigArray']['email'];
        $ary['address1'] = ' value="' . $_SESSION['OrigArray']['address1'] . '"';
        $ary['address2'] = ' value="' . $_SESSION['OrigArray']['address2'] . '"';
        $ary['city'] = ' value="' . $_SESSION['OrigArray']['city'] . '"';
        $ary['zip'] = ' value="' . $_SESSION['OrigArray']['zip'] . '"';
        $ary['notes'] = $_SESSION['OrigArray']['notes'];
        switch($_SESSION['OrigArray']['state']) {
            case 'AK':
                $ary['state1'] = ' selected="selected"';
                break;
            case 'AL':
                $ary['state2'] = ' selected="selected"';
                break;
            case 'AR':
                $ary['state3'] = ' selected="selected"';
                break;
            case 'AZ':
                $ary['state4'] = ' selected="selected"';
                break;
            case 'CA':
                $ary['state5'] = ' selected="selected"';
                break;
            case 'CO':
                $ary['state6'] = ' selected="selected"';
                break;
            case 'CT':
                $ary['state7'] = ' selected="selected"';
                break;
            case 'DC':
                $ary['state8'] = ' selected="selected"';
                break;
            case 'DE':
                $ary['state9'] = ' selected="selected"';
                break;
            case 'FL':
                $ary['state10'] = ' selected="selected"';
                break;
            case 'GA':
                $ary['state11'] = ' selected="selected"';
                break;
            case 'HI':
                $ary['state12'] = ' selected="selected"';
                break;
            case 'IA':
                $ary['state13'] = ' selected="selected"';
                break;
            case 'ID':
                $ary['state14'] = ' selected="selected"';
                break;
            case 'IL':
                $ary['state15'] = ' selected="selected"';
                break;
            case 'IN':
                $ary['state16'] = ' selected="selected"';
                break;
            case 'KS':
                $ary['state17'] = ' selected="selected"';
                break;
            case 'KY':
                $ary['state18'] = ' selected="selected"';
                break;
            case 'LA':
                $ary['state19'] = ' selected="selected"';
                break;
            case 'MA':
                $ary['state20'] = ' selected="selected"';
                break;
            case 'MD':
                $ary['state21'] = ' selected="selected"';
                break;
            case 'ME':
                $ary['state22'] = ' selected="selected"';
                break;
            case 'MI':
                $ary['state23'] = ' selected="selected"';
                break;
            case 'MN':
                $ary['state24'] = ' selected="selected"';
                break;
            case 'MO':
                $ary['state25'] = ' selected="selected"';
                break;
            case 'MS':
                $ary['state26'] = ' selected="selected"';
                break;
            case 'MT':
                $ary['state27'] = ' selected="selected"';
                break;
            case 'NC':
                $ary['state28'] = ' selected="selected"';
                break;
            case 'ND':
                $ary['state29'] = ' selected="selected"';
                break;
            case 'NE':
                $ary['state30'] = ' selected="selected"';
                break;
            case 'NH':
                $ary['state31'] = ' selected="selected"';
                break;
            case 'NJ':
                $ary['state32'] = ' selected="selected"';
                break;
            case 'NM':
                $ary['state33'] = ' selected="selected"';
                break;
            case 'NV':
                $ary['state34'] = ' selected="selected"';
                break;
            case 'NY':
                $ary['state35'] = ' selected="selected"';
                break;
            case 'OH':
                $ary['state36'] = ' selected="selected"';
                break;
            case 'OK':
                $ary['state37'] = ' selected="selected"';
                break;
            case 'OR':
                $ary['state38'] = ' selected="selected"';
                break;
            case 'PA':
                $ary['state39'] = ' selected="selected"';
                break;
            case 'RI':
                $ary['state40'] = ' selected="selected"';
                break;
            case 'SC':
                $ary['state41'] = ' selected="selected"';
                break;
            case 'SD':
                $ary['state42'] = ' selected="selected"';
                break;
            case 'TN':
                $ary['state43'] = ' selected="selected"';
                break;
            case 'TX':
                $ary['state44'] = ' selected="selected"';
                break;
            case 'UT':
                $ary['state45'] = ' selected="selected"';
                break;
            case 'VA':
                $ary['state46'] = ' selected="selected"';
                break;
            case 'VT':
                $ary['state47'] = ' selected="selected"';
                break;
            case 'WA':
                $ary['state48'] = ' selected="selected"';
                break;
            case 'WI':
                $ary['state49'] = ' selected="selected"';
                break;
            case 'WV':
                $ary['state50'] = ' selected="selected"';
                break;
            case 'WY':
                $ary['state51'] = ' selected="selected"';
                break;
            default:
                $ary['state0'] = ' selected="selected"';
                break;
        }

		// Iteration counter
        $c = 0;
        $br = '';
        foreach($_SESSION['ErrArray'] as $e) {
            $field = ucwords(str_replace(array('_','-'), chr(32), $e['field']));
            $param = $e['param'];

            switch($e['rule']) {
                case 'validate_required':
                    $err[$c]['id'] = $e['field'];
                    $err[$c]['message'] = "The $field field is required.$br";
                    break;
                case 'validate_valid_email':
                    $err[$c]['id'] = $e['field'];
                    $err[$c]['message'] = "The $field field must contain a valid email address.$br";
                    break;
                case 'validate_max_len':
                    if($param == 1) {
                        $err[$c]['id'] = $e['field'];
                        $err[$c]['message'] = "The $field field must be at least $param character in length.$br";
                    } else {
                        $err[$c]['id'] = $e['field'];
                        $err[$c]['message'] = "The $field field must be at least $param characters in length.$br";
                    }
                    break;
                case 'validate_min_len':
                    if($param == 1) {
                        $err[$c]['id'] = $e['field'];
                        $err[$c]['message'] = "The $field field must not exceed $param character in length.$br";
                    } else {
                        $err[$c]['id'] = $e['field'];
                        $err[$c]['message'] = "The $field field must not exceed $param characters in length.$br";
                    }
                    break;
                case 'validate_exact_len':
                    if($param == 1) {
                        $err[$c]['id'] = $e['field'];
                        $err[$c]['message'] = "The $field field must be exactly $param character in length.$br";
                    } else {
                        $err[$c]['id'] = $e['field'];
                        $err[$c]['message'] = "The $field field must be exactly $param characters in length.$br";
                    }
                    break;
                case 'validate_alpha':
                    $err[$c]['id'] = $e['field'];
                    $err[$c]['message'] = "The $field field must only contain alphabetical characters.$br";
                    break;
                case 'validate_alpha_numeric':
                    $err[$c]['id'] = $e['field'];
                    $err[$c]['message'] = "The $field field must only contain alpha-numeric characters.$br";
                    break;
                case 'validate_alpha_dash':
                    $err[$c]['id'] = $e['field'];
                    $err[$c]['message'] = "The $field field must only contain alpha-numeric characters, underscores, and dashes.$br";
                    break;
                case 'validate_numeric':
                    $err[$c]['id'] = $e['field'];
                    $err[$c]['message'] = "The $field field must only contain alpha-numeric characters, underscores, and dashes.$br";
                    break;
                case 'validate_integer':
                    $err[$c]['id'] = $e['field'];
                    $err[$c]['message'] = "The $field field must contain an integer.$br";
                    break;
                case 'validate_float':
                    $err[$c]['id'] = $e['field'];
                    $err[$c]['message'] = "The $field field must contain a decimal number.$br";
                    break;
                case 'validate_date':
                    $err[$c]['id'] = $e['field'];
                    $err[$c]['message'] = "The $field format must be MM-DD-YYYY.$br";
                    break;
                case 'validate_min_numeric':
                    $err[$c]['id'] = $e['field'];
                    $err[$c]['message'] = "The $field field must contain a number greater than $param.$br";
                    break;
                case 'validate_max_numeric':
                    $err[$c]['id'] = $e['field'];
                    $err[$c]['message'] = "The $field field must contain a number less than $param.$br";
                    break;
                case 'validate_captcha':
                    $err[$c]['id'] = $e['field'];
                    $err[$c]['message'] = "The selection you made on the security question was incorrect.$br";
                    break;
            }
            if($c > 0) {
                $br = '<br>';
            }
            $c++;
        }

		// Set error array into JSON for output in JS below
        if (!empty($err)) {
            $errAry = json_encode($err);
        }
    }

	// Randomly get 1 of 4 security images.
    $img = rand(1,4) . "_securityimage.jpg";
    $src = "img/" . $img;
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <link rel="stylesheet" href="<?php echo $url; ?>css/tool.css" />
    <script type="text/javascript" src="<?php echo $url; ?>js/validate-min.js"></script>
    <script type="text/javascript" src="<?php echo $url; ?>js/slideToggle.js"></script>
</head>
<body>
    <form name="csemRegister" action="<?php echo $url; ?>src/calculateR.php?s=<?php echo $SiteID; ?>" method="post" id="csemRegisterForm">
        <div id="errorMsg"></div>
        <table class="form-table">
            <tbody>
				<tr>
					<td class="form-table-col-both" colspan="2">
						<span class="required">*</span> <i>denotes required field</i>
					</td>
				</tr>
				<tr>
					<td class="form-table-col-left">
                        <label class="form-label">Course</label>
                    </td>
                    <td class="form-table-col-right">
                        <b class="form-bold"><?php echo $ary['course']; ?></b>
                    </td>
				</tr>
				<tr>
					<td class="form-table-col-left">
                        <label class="form-label">Location</label>
                    </td>
                    <td class="form-table-col-right">
						<span class="form-bold"><?php echo $ary['location']; ?></span>
                    </td>
				</tr>
				<tr>
					<td class="form-table-col-left">
                        <label class="form-label">Price</label>
                    </td>
                    <td class="form-table-col-right">
                        <span class="form-bold">$<?php echo $ary['price']; ?></span>
                    </td>
				</tr>
				<tr>
					<td class="form-table-col-left" id="attendeesErr">
                        <label for="attendees" class="form-label">Attendees <span class="required">*</span></label>
                    </td>
                    <td class="form-table-col-right">
                        <textarea name="attendees" id="attendees" class="form-textbox"><?php echo $ary['attendees']; ?></textarea>
						<br />
                        <small>(list all people attending the class separated by commas)</small>
                    </td>
				</tr>
                <tr>
                    <td class="form-table-col-left" id="company_nameErr">
                        <label for="company_name" class="form-label">Company Name</label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="text" name="company_name" id="company_name" class="form-input" placeholder="e.g. John Doe, Inc."<?php echo $ary['company_name']; ?> />
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="company_contactErr">
                        <label for="company_contact" class="form-label">Company Contact <span class="required">*</span></label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="text" name="company_contact" id="company_contact" class="form-input" value="<?php echo $ary['company_contact']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="phoneErr">
                        <label for="phone" class="form-label">Phone number <span class="required">*</span></label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="text" name="phone" id="phone" class="form-input" placeholder="e.g. 724-555-4321" value="<?php echo $ary['phone']; ?>" />
                        <br />
                        <small>(Format: XXX-XXX-XXXX or 1-XXX-XXX-XXXX)</small>
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="faxErr">
                        <label for="fax" class="form-label">Fax number</label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="text" name="fax" id="fax" class="form-input" placeholder="e.g. 724-555-4321"<?php echo $ary['fax']; ?> />
                        <br />
                        <small>(Format: XXX-XXX-XXXX or 1-XXX-XXX-XXXX)</small>
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="emailErr">
                        <label for="email" class="form-label">E-mail Address <span class="required">*</span></label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="text" name="email" id="email" class="form-input" value="<?php echo $ary['email']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="address1Err">
                        <label for="address1" class="form-label">Address <span class="required">*</span></label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="text" name="address1" id="address1" class="form-input" placeholder="e.g. 123 Fake Street"<?php echo $ary['address1']; ?> />
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="address2Err">
                        <label for="address2" class="form-label">Address 2</label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="text" name="address2" id="address2" class="form-input"<?php echo $ary['address2']; ?> />
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="cityErr">
                        <label for="city" class="form-label">City <span class="required">*</span></label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="text" name="city" id="city" class="form-input" placeholder="e.g. Pittsburgh"<?php echo $ary['city']; ?> />
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="stateErr">
                        <label for="state" class="form-label">State <span class="required">*</span></label>
                    </td>
                    <td class="form-table-col-right">
                        <select name="state" id="state" class="form-dropdown">
							<option value=""<?php echo $ary['state0']; ?>>--SELECT--</option>
							<option value="AK"<?php echo $ary['state1']; ?>>Alaska</option>
							<option value="AL"<?php echo $ary['state2']; ?>>Alabama</option>
							<option value="AR"<?php echo $ary['state3']; ?>>Arkansas</option>
							<option value="AZ"<?php echo $ary['state4']; ?>>Arizona</option>
							<option value="CA"<?php echo $ary['state5']; ?>>California</option>
							<option value="CO"<?php echo $ary['state6']; ?>>Colorado</option>
							<option value="CT"<?php echo $ary['state7']; ?>>Connecticut</option>
							<option value="DC"<?php echo $ary['state8']; ?>>Dist. Columbia</option>
							<option value="DE"<?php echo $ary['state9']; ?>>Delaware</option>
							<option value="FL"<?php echo $ary['state10']; ?>>Florida</option>
							<option value="GA"<?php echo $ary['state11']; ?>>Georgia</option>
							<option value="HI"<?php echo $ary['state12']; ?>>Hawaii</option>
							<option value="IA"<?php echo $ary['state13']; ?>>Iowa</option>
							<option value="ID"<?php echo $ary['state14']; ?>>Idaho</option>
							<option value="IL"<?php echo $ary['state15']; ?>>Illinois</option>
							<option value="IN"<?php echo $ary['state16']; ?>>Indiana</option>
							<option value="KS"<?php echo $ary['state17']; ?>>Kansas</option>
							<option value="KY"<?php echo $ary['state18']; ?>>Kentucky</option>
							<option value="LA"<?php echo $ary['state19']; ?>>Louisiana</option>
							<option value="MA"<?php echo $ary['state20']; ?>>Massachusetts</option>
							<option value="MD"<?php echo $ary['state21']; ?>>Maryland</option>
							<option value="ME"<?php echo $ary['state22']; ?>>Maine</option>
							<option value="MI"<?php echo $ary['state23']; ?>>Michigan</option>
							<option value="MN"<?php echo $ary['state24']; ?>>Minnesota</option>
							<option value="MO"<?php echo $ary['state25']; ?>>Missouri</option>
							<option value="MS"<?php echo $ary['state26']; ?>>Mississippi</option>
							<option value="MT"<?php echo $ary['state27']; ?>>Montana</option>
							<option value="NC"<?php echo $ary['state28']; ?>>North Carolina</option>
							<option value="ND"<?php echo $ary['state29']; ?>>North Dakota</option>
							<option value="NE"<?php echo $ary['state30']; ?>>Nebraska</option>
							<option value="NH"<?php echo $ary['state31']; ?>>New Hampshire</option>
							<option value="NJ"<?php echo $ary['state32']; ?>>New Jersey</option>
							<option value="NM"<?php echo $ary['state33']; ?>>New Mexico</option>
							<option value="NV"<?php echo $ary['state34']; ?>>Nevada</option>
							<option value="NY"<?php echo $ary['state35']; ?>>New York</option>
							<option value="OH"<?php echo $ary['state36']; ?>>Ohio</option>
							<option value="OK"<?php echo $ary['state37']; ?>>Oklahoma</option>
							<option value="OR"<?php echo $ary['state38']; ?>>Oregon</option>
							<option value="PA"<?php echo $ary['state39']; ?>>Pennsylvania</option>
							<option value="RI"<?php echo $ary['state40']; ?>>Rhode Island</option>
							<option value="SC"<?php echo $ary['state41']; ?>>South Carolina</option>
							<option value="SD"<?php echo $ary['state42']; ?>>South Dakota</option>
							<option value="TN"<?php echo $ary['state43']; ?>>Tennessee</option>
							<option value="TX"<?php echo $ary['state44']; ?>>Texas</option>
							<option value="UT"<?php echo $ary['state45']; ?>>Utah</option>
							<option value="VA"<?php echo $ary['state46']; ?>>Virginia</option>
							<option value="VT"<?php echo $ary['state47']; ?>>Vermont</option>
							<option value="WA"<?php echo $ary['state48']; ?>>Washington</option>
							<option value="WI"<?php echo $ary['state49']; ?>>Wisconsin</option>
							<option value="WV"<?php echo $ary['state50']; ?>>West Virginia</option>
							<option value="WY"<?php echo $ary['state51']; ?>>Wyoming</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="zipErr">
                        <label for="zip" class="form-label">Zip Code <span class="required">*</span></label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="text" name="zip" id="zip" class="form-input" placeholder="e.g. 12345"<?php echo $ary['zip']; ?> />
                    </td>
                </tr>
				<tr>
					<td class="form-table-col-left" id="notesErr">
                        <label for="notes" class="form-label">Notes</label>
                    </td>
                    <td class="form-table-col-right">
                        <textarea name="notes" id="notes" class="form-textbox"><?php echo $ary['notes']; ?></textarea>
						<br />
                        <small>(place any notes you may have here)</small>
                    </td>
				</tr>
				<tr>
					<td class="form-table-col-both" colspan="2">
						For security purposes, please identify the image below before submitting.
					</td>
				</tr>
				<tr>
					<td class="form-table-col-left" id="checkerErr">
						<img src="<?php echo $src; ?>" id="sec" alt="Security Image" />
						<input type="hidden" name="checker" value="<?php echo $img; ?>" />
					</td>
					<td class="form-table-col-right">
						<input name="radiobutton" id="choice1" type="radio" value="Safety_Glasses"><label for="choice1">Safety Glasses</label><br>
						<input name="radiobutton" id="choice2" type="radio" value="Ear_Muffs"><label for="choice2">Ear Muffs</label><br>
						<input name="radiobutton" id="choice3" type="radio" value="Hard_Hat"><label for="choice3">Hard Hat</label><br>
						<input name="radiobutton" id="choice4" type="radio" value="Respiratory_Masks"><label for="choice4">Respiratory Masks</label>
					</td>
				</tr>
                <tr>
                    <td class="form-table-col-both" colspan="2">
                        <button name="register" type="submit" value="register">Register!</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <script type="text/javascript">
        function findId(id) {
            return document.getElementById(id);
        }

        function errClose() {
            var s = new SlideToggle('errorMsg');
            s.slideToggle();
        }

        function filter(a, f) {
            var ret = [];
            for (var i = 0; i < a.length; ++i) {
                if (f(a[i])) {
                    ret.push(a[i]);
                }
            }
            return ret;
        }

        function resetErr() {
            var elements = document.getElementsByTagName("*");
            var logEl = filter(elements, function(el) {
                return /Err/.test(el.id);
            });

            for (var i = 0; i < logEl.length; ++i) {
                logEl[i].style.color = '#000';
            }
            return logEl;
        }

        function formValReq(f) {
            return (f !== null && f !== '');
        }

        function checkErrors(e) {
			if(e) {
				var eMsg = new SlideToggle('errorMsg');
				var errorMsg = findId('errorMsg');
				if(errorMsg.style.display === 'block') {
					errorMsg.style.display = 'none';
				}
				errorMsg.innerHTML = '<span id="errorClose" onclick="errClose()">close</span>';
				for (var i = 0, errorLength = e.length; i < errorLength; i++) {
					var el = findId(e[i].id + 'Err');
					var br = '';
					if (i !== parseInt(e.length - 1)) {
						br = '<br>';
					}
					el.style.color = '#ff0000';
					errorMsg.innerHTML += e[i].message + br;
				}
				eMsg.slideToggle();
			}
        }

        var postBackErrors = checkErrors(<?php echo $errAry; ?>);

        var validator = new FormValidator('csemRegister', [{
                name: 'attendees',
                display: 'Attendees',
                rules: 'required|max_length[4000]'
            }, {
                name: 'company_name',
                display: 'Company Name',
                rules: 'max_length[150]'
            }, {
                name: 'company_contact',
                display: 'Company Contact',
                rules: 'required|max_length[60]'
            }, {
                name: 'phone',
                display: 'Phone',
                rules: 'required|max_length[14]|callback_PhoneRegEx'
            }, {
                name: 'fax',
                display: 'Fax',
                rules: 'max_length[14]|callback_PhoneRegEx'
            }, {
                name: 'email',
                display: 'Email',
                rules: 'required|valid_email|max_length[75]'
            }, {
                name: 'address1',
                display: 'Address',
                rules: 'required|max_length[150]'
            }, {
                name: 'address2',
                display: 'Address 2',
                rules: 'max_length[150]'
            }, {
                name: 'city',
                display: 'City',
                rules: 'required|alpha|max_length[60]'
            }, {
                name: 'state',
                display: 'State',
                rules: 'required|alpha|max_length[2]'
            }, {
                name: 'zip',
                display: 'Zip Code',
                rules: 'required|alpha_dash|min_length[5]|max_length[10]'
            }, {
                name: 'notes',
                display: 'Notes',
                rules: 'max_length[2000]'
            }], function(errors, evt) {
                evt.preventDefault();
                resetErr();
                if (errors.length > 0) {
                    checkErrors(errors);
                } else {
                    //var form = findId('csemRegisterForm');
                    document.getElementById('csemRegisterForm').submit();
                }
            }
        );

        validator.registerCallback('PhoneRegEx', function(v) {
            var RegEx = /^[0-9]{3,3}-[0-9]{3,3}-[0-9]{4,4}|[0-9]{1,1}-[0-9]{3,3}-[0-9]{3,3}-[0-9]{4,4}$/;
            return RegEx.test(v);
        }).setMessage('PhoneRegEx', 'The %s format must be XXX-XXX-XXXX or 1-XXX-XXX-XXXX.');

        var p = findId('phone');
        p.maxLength = 14;
        p.onkeyup = function(e) {
            e = e || window.event;
            if (e.keyCode >= 65 && e.keyCode <= 90) {
                this.value = this.value.substr(0, this.value.length - 1);
                return false;
            } else if (e.keyCode >= 37 && e.keyCode <= 40) {
                return true;
            }
            var v = (this.value.replace(/[^\d]/g, ''));
            if (v.length === 10) {
                this.value = (v.substring(0, 3) + "-" + v.substring(3, 6) + "-" + v.substring(6, 10));
            } else if (v.length === 11) {
                this.value = (v.substring(0, 1) + "-" + v.substring(1, 4) + "-" + v.substring(4, 7) + "-" + v.substring(7, 11));
            };
        }

		var f = findId('fax');
        f.maxLength = 14;
        f.onkeyup = function(e) {
            e = e || window.event;
            if (e.keyCode >= 65 && e.keyCode <= 90) {
                this.value = this.value.substr(0, this.value.length - 1);
                return false;
            } else if (e.keyCode >= 37 && e.keyCode <= 40) {
                return true;
            }
            var v = (this.value.replace(/[^\d]/g, ''));
            if (v.length === 10) {
                this.value = (v.substring(0, 3) + "-" + v.substring(3, 6) + "-" + v.substring(6, 10));
            } else if (v.length === 11) {
                this.value = (v.substring(0, 1) + "-" + v.substring(1, 4) + "-" + v.substring(4, 7) + "-" + v.substring(7, 11));
            };
        }
    </script>
</body>
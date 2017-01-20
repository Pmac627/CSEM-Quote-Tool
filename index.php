<?php /* index.php */
    require_once('src/sessions.class.php');
	$session = new Session();
	//$sessID = "";
	//session_start();
	//if(!isset($_COOKIE['PHPSESSID'])) {
	//	$sessID = session_id();

	//	if(session_id() == '') {
	//		session_regenerate_id();
	//		$sessID = session_id();
	//	} else {
	//		session_id(session_id());
	//		$sessID = session_id();
	//	}
	//} elseif($_GET['x'] != "") {
	//	session_id($_GET['x']);
	//	$sessID = session_id();
	//}

	// Initialize array variables
    $ary = array();
    $err = array();

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

	// Never leave DB connection open!
	mysqli_close($con);

	// If there are errors, convert them into JSON for JS validator
    if($_SESSION['ErrArray']) {
		// Default as many fields to their previous values as possible
        $ary['type'] = ' value="' . $_SESSION['OrigArray']['type'] . '"';
		$ary['class_title_original'] = $_SESSION['OrigArray']['class_title'];
        $ary['class_title'] = ' value="' . $_SESSION['OrigArray']['class_title'] . '"';
        $ary['students'] = ' value="' . $_SESSION['OrigArray']['students'] . '"';
        $ary['days'] = ' value="' . $_SESSION['OrigArray']['days'] . '"';
        switch($_SESSION['OrigArray']['location']) {
            case 'AK':
                $ary['location1'] = ' selected="selected"';
                break;
            case 'AL':
                $ary['location2'] = ' selected="selected"';
                break;
            case 'AR':
                $ary['location3'] = ' selected="selected"';
                break;
            case 'AZ':
                $ary['location4'] = ' selected="selected"';
                break;
            case 'CA':
                $ary['location5'] = ' selected="selected"';
                break;
            case 'CO':
                $ary['location6'] = ' selected="selected"';
                break;
            case 'CT':
                $ary['location7'] = ' selected="selected"';
                break;
            case 'DC':
                $ary['location8'] = ' selected="selected"';
                break;
            case 'DE':
                $ary['location9'] = ' selected="selected"';
                break;
            case 'FL':
                $ary['location10'] = ' selected="selected"';
                break;
            case 'GA':
                $ary['location11'] = ' selected="selected"';
                break;
            case 'HI':
                $ary['location12'] = ' selected="selected"';
                break;
            case 'IA':
                $ary['location13'] = ' selected="selected"';
                break;
            case 'ID':
                $ary['location14'] = ' selected="selected"';
                break;
            case 'IL':
                $ary['location15'] = ' selected="selected"';
                break;
            case 'IN':
                $ary['location16'] = ' selected="selected"';
                break;
            case 'KS':
                $ary['location17'] = ' selected="selected"';
                break;
            case 'KY':
                $ary['location18'] = ' selected="selected"';
                break;
            case 'LA':
                $ary['location19'] = ' selected="selected"';
                break;
            case 'MA':
                $ary['location20'] = ' selected="selected"';
                break;
            case 'MD':
                $ary['location21'] = ' selected="selected"';
                break;
            case 'ME':
                $ary['location22'] = ' selected="selected"';
                break;
            case 'MI':
                $ary['location23'] = ' selected="selected"';
                break;
            case 'MN':
                $ary['location24'] = ' selected="selected"';
                break;
            case 'MO':
                $ary['location25'] = ' selected="selected"';
                break;
            case 'MS':
                $ary['location26'] = ' selected="selected"';
                break;
            case 'MT':
                $ary['location27'] = ' selected="selected"';
                break;
            case 'NC':
                $ary['location28'] = ' selected="selected"';
                break;
            case 'ND':
                $ary['location29'] = ' selected="selected"';
                break;
            case 'NE':
                $ary['location30'] = ' selected="selected"';
                break;
            case 'NH':
                $ary['location31'] = ' selected="selected"';
                break;
            case 'NJ':
                $ary['location32'] = ' selected="selected"';
                break;
            case 'NM':
                $ary['location33'] = ' selected="selected"';
                break;
            case 'NV':
                $ary['location34'] = ' selected="selected"';
                break;
            case 'NY':
                $ary['location35'] = ' selected="selected"';
                break;
            case 'OH':
                $ary['location36'] = ' selected="selected"';
                break;
            case 'OK':
                $ary['location37'] = ' selected="selected"';
                break;
            case 'OR':
                $ary['location38'] = ' selected="selected"';
                break;
            case 'PA':
                $ary['location39'] = ' selected="selected"';
                break;
            case 'RI':
                $ary['location40'] = ' selected="selected"';
                break;
            case 'SC':
                $ary['location41'] = ' selected="selected"';
                break;
            case 'SD':
                $ary['location42'] = ' selected="selected"';
                break;
            case 'TN':
                $ary['location43'] = ' selected="selected"';
                break;
            case 'TX':
                $ary['location44'] = ' selected="selected"';
                break;
            case 'UT':
                $ary['location45'] = ' selected="selected"';
                break;
            case 'VA':
                $ary['location46'] = ' selected="selected"';
                break;
            case 'VT':
                $ary['location47'] = ' selected="selected"';
                break;
            case 'WA':
                $ary['location48'] = ' selected="selected"';
                break;
            case 'WI':
                $ary['location49'] = ' selected="selected"';
                break;
            case 'WV':
                $ary['location50'] = ' selected="selected"';
                break;
            case 'WY':
                $ary['location51'] = ' selected="selected"';
                break;
            default:
                $ary['location0'] = ' selected="selected"';
                break;
        }
        switch($_SESSION['OrigArray']['urgency']) {
            case '1':
                $ary['urgency1'] = ' selected="selected"';
                break;
            case '2':
                $ary['urgency2'] = ' selected="selected"';
                break;
            case '3':
                $ary['urgency3'] = ' selected="selected"';
                break;
            default:
                $ary['urgency0'] = ' selected="selected"';
                break;
        }
        $ary['email'] = ' value="' . $_SESSION['OrigArray']['email'] . '"';
        $ary['name'] = ' value="' . $_SESSION['OrigArray']['name'] . '"';
        $ary['phone'] = ' value="' . $_SESSION['OrigArray']['phone'] . '"';

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
    <title><?php echo $name; ?></title>
    <link rel="stylesheet" href="<?php echo $url; ?>css/tool.css" />
    <script type="text/javascript" src="<?php echo $url; ?>js/validate-min.js"></script>
    <script type="text/javascript" src="<?php echo $url; ?>js/slideToggle.js"></script>
</head>
<body>
    <form name="csemQuote" action="<?php echo $url; ?>src/calculate.php?s=<?php echo $SiteID; ?>" method="post" id="csemQuoteForm">
        <div id="errorMsg"></div>
<!--
		<div id="safariMsg" style="display:none;position:absolute;z-index:999;top:0;left:0;bottom:0;right:0;background-color: rgba(255,255,255,0.90);font-weight: bold;">
			Oops! Looks like your browser has "Third Party Cookies" disabled.<br>
			<br>
			We utilize cookies to help make the quote tool experience as dynamic and fluid as possible and we <u>do not</u> track or maintain any records about users of this quote tool.<br>
			<br>
			To use this quote tool, you may:
			<ol>
				<li style="padding:5px 0 5px 0;">Access the quote tool directly at <a href="http://www.csemquotetool.com/index.php?s=<?php echo $SiteID; ?>" target="_blank">csemquotetool.com</a></li>
				<li style="padding:5px 0 5px 0;">Enable "Third Party Cookies" in this browser's settings</li>
				<li style="padding:5px 0 5px 0;">Try this quote tool in an alternate browser like:
					<ul style="list-style-type: none;">
						<li><a href="https://www.google.com/intl/en-US/chrome/browser/" target="_blank">Chrome</a></li>
						<li><a href="https://www.mozilla.org/en-US/firefox/new/" target="_blank">Firefox</a></li>
						<li><a href="http://www.opera.com/" target="_blank">Opera</a></li>
					</ul>
				</li>
			</ol>
			We apologize for any inconvenience and look forward to receiving your quote request soon!
		</div>
-->
        <table class="form-table">
            <thead>
				<tr>
					<td class="form-table-col-both" colspan="2">
						Switch a regulation... 
						<span class="form-span" onClick="changeReg('osha')">OSHA</span> | 
						<span class="form-span" onClick="changeReg('dot')">DOT</span> | 
						<span class="form-span" onClick="changeReg('epa')">EPA</span> | 
						<span class="form-span" onClick="changeReg('msha')">MSHA</span>
						<input type="hidden" name="type" id="type"<?php echo $ary['type']; ?> />
					</td>
				</tr>
			</thead>
            <tbody>
                <tr>
                    <td class="form-table-col-left" id="class_titleErr">
                        <label for="class_title" class="form-label">Training Class</label>
                    </td>
                    <td class="form-table-col-right">
                        <select name="class_title" id="class_title" class="form-dropdown"></select>
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="studentsErr">
                        <label for="students" class="form-label">Number of Students</label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="number" name="students" id="students" class="form-input" min="1" max="100" placeholder="e.g. 1-100"<?php echo $ary['students']; ?> />
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="daysErr">
                        <label for="days" class="form-label">Number of Days</label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="number" name="days" id="days" class="form-input" min="1" max="10" placeholder="e.g. 1-10"<?php echo $ary['days']; ?> />
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="locationErr">
                        <label for="location" class="form-label">Training Location</label>
                    </td>
                    <td class="form-table-col-right">
                        <select name="location" id="location" class="form-dropdown">
							<option value=""<?php echo $ary['location0']; ?>>--SELECT--</option>
							<option value="AK"<?php echo $ary['location1']; ?>>Alaska</option>
							<option value="AL"<?php echo $ary['location2']; ?>>Alabama</option>
							<option value="AR"<?php echo $ary['location3']; ?>>Arkansas</option>
							<option value="AZ"<?php echo $ary['location4']; ?>>Arizona</option>
							<option value="CA"<?php echo $ary['location5']; ?>>California</option>
							<option value="CO"<?php echo $ary['location6']; ?>>Colorado</option>
							<option value="CT"<?php echo $ary['location7']; ?>>Connecticut</option>
							<option value="DC"<?php echo $ary['location8']; ?>>Dist. Columbia</option>
							<option value="DE"<?php echo $ary['location9']; ?>>Delaware</option>
							<option value="FL"<?php echo $ary['location10']; ?>>Florida</option>
							<option value="GA"<?php echo $ary['location11']; ?>>Georgia</option>
							<option value="HI"<?php echo $ary['location12']; ?>>Hawaii</option>
							<option value="IA"<?php echo $ary['location13']; ?>>Iowa</option>
							<option value="ID"<?php echo $ary['location14']; ?>>Idaho</option>
							<option value="IL"<?php echo $ary['location15']; ?>>Illinois</option>
							<option value="IN"<?php echo $ary['location16']; ?>>Indiana</option>
							<option value="KS"<?php echo $ary['location17']; ?>>Kansas</option>
							<option value="KY"<?php echo $ary['location18']; ?>>Kentucky</option>
							<option value="LA"<?php echo $ary['location19']; ?>>Louisiana</option>
							<option value="MA"<?php echo $ary['location20']; ?>>Massachusetts</option>
							<option value="MD"<?php echo $ary['location21']; ?>>Maryland</option>
							<option value="ME"<?php echo $ary['location22']; ?>>Maine</option>
							<option value="MI"<?php echo $ary['location23']; ?>>Michigan</option>
							<option value="MN"<?php echo $ary['location24']; ?>>Minnesota</option>
							<option value="MO"<?php echo $ary['location25']; ?>>Missouri</option>
							<option value="MS"<?php echo $ary['location26']; ?>>Mississippi</option>
							<option value="MT"<?php echo $ary['location27']; ?>>Montana</option>
							<option value="NC"<?php echo $ary['location28']; ?>>North Carolina</option>
							<option value="ND"<?php echo $ary['location29']; ?>>North Dakota</option>
							<option value="NE"<?php echo $ary['location30']; ?>>Nebraska</option>
							<option value="NH"<?php echo $ary['location31']; ?>>New Hampshire</option>
							<option value="NJ"<?php echo $ary['location32']; ?>>New Jersey</option>
							<option value="NM"<?php echo $ary['location33']; ?>>New Mexico</option>
							<option value="NV"<?php echo $ary['location34']; ?>>Nevada</option>
							<option value="NY"<?php echo $ary['location35']; ?>>New York</option>
							<option value="OH"<?php echo $ary['location36']; ?>>Ohio</option>
							<option value="OK"<?php echo $ary['location37']; ?>>Oklahoma</option>
							<option value="OR"<?php echo $ary['location38']; ?>>Oregon</option>
							<option value="PA"<?php echo $ary['location39']; ?>>Pennsylvania</option>
							<option value="RI"<?php echo $ary['location40']; ?>>Rhode Island</option>
							<option value="SC"<?php echo $ary['location41']; ?>>South Carolina</option>
							<option value="SD"<?php echo $ary['location42']; ?>>South Dakota</option>
							<option value="TN"<?php echo $ary['location43']; ?>>Tennessee</option>
							<option value="TX"<?php echo $ary['location44']; ?>>Texas</option>
							<option value="UT"<?php echo $ary['location45']; ?>>Utah</option>
							<option value="VA"<?php echo $ary['location46']; ?>>Virginia</option>
							<option value="VT"<?php echo $ary['location47']; ?>>Vermont</option>
							<option value="WA"<?php echo $ary['location48']; ?>>Washington</option>
							<option value="WI"<?php echo $ary['location49']; ?>>Wisconsin</option>
							<option value="WV"<?php echo $ary['location50']; ?>>West Virginia</option>
							<option value="WY"<?php echo $ary['location51']; ?>>Wyoming</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="urgencyErr">
                        <label for="urgency" class="form-label">How Soon is Training Needed</label>
                    </td>
                    <td class="form-table-col-right">
                        <select name="urgency" id="urgency" class="form-dropdown">
                            <option value=""<?php echo $ary['urgency0']; ?>>--SELECT--</option>
							<option value="1"<?php echo $ary['urgency1']; ?>>1-13 days</option>
							<option value="2"<?php echo $ary['urgency2']; ?>>14-20 days</option>
							<option value="3"<?php echo $ary['urgency3']; ?>>21 or more days</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="emailErr">
                        <label for="email" class="form-label">E-mail Address</label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="text" name="email" id="email" class="form-input" placeholder="e.g. johndoe@email.co"<?php echo $ary['email']; ?> />
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="nameErr">
                        <label for="name" class="form-label">Your Name</label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="text" name="name" id="name" class="form-input" placeholder="e.g. John Doe"<?php echo $ary['name']; ?> />
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-left" id="phoneErr">
                        <label for="phone" class="form-label">Phone number</label>
                    </td>
                    <td class="form-table-col-right">
                        <input type="text" name="phone" id="phone" class="form-input" placeholder="e.g. 724-555-4321"<?php echo $ary['phone']; ?> />
                        <br />
                        <small>(Format: XXX-XXX-XXXX or 1-XXX-XXX-XXXX)</small>
                    </td>
                </tr>
				<tr>
					<td class="form-table-col-both" colspan="2">
						For security purposes, please identify the image below before submitting.
					</td>
				</tr>
				<tr>
					<td class="form-table-col-left">
						<img src="<?php echo $src; ?>" id="sec" alt="Security Image" />
						<input type="hidden" name="checker" id="checkerErr" value="<?php echo $img; ?>" />
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
                        All fields required. Please complete form and submit.
                    </td>
                </tr>
                <tr>
                    <td class="form-table-col-both" colspan="2">
                        <button name="send_quote" type="submit" value="send_quote">Send me a quote!</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <script type="text/javascript">
        function findId(id) {
            return document.getElementById(id);
        }

		function changeReg(r) {
			var s = findId('class_title');
			var t = findId('type');
			loadXMLDoc(s, r, t);
		}

        function loadXMLDoc(s, r, t) {
			var xh;
			if (window.XMLHttpRequest) {
				xh = new XMLHttpRequest();
			} else {
				xh = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xh.onreadystatechange = function() {
				if (xh.readyState === 4) {
					s.innerHTML = xh.responseText;
				}
			}

            var url = "";
            if (t.value != "") {
				var c = "<?php echo $ary['class_title_original']; ?>";
                url = "src/options.php?q=" + r + "&c=" + c;
            } else {
                url = "src/options.php?q=" + r;
            }

            t.value = r;
			xh.open("GET", url, true);
			xh.send();
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

        var validator = new FormValidator('csemQuote', [{
                name: 'class_title',
                display: 'Training Class',
                rules: 'required|alpha_dash|max_length[50]'
            }, {
                name: 'students',
                display: 'Students',
                rules: 'required|numeric|less_than[101]|greater_than[0]|max_length[3]'
            }, {
                name: 'days',
                display: 'Days',
                rules: 'required|numeric|less_than[11]|greater_than[0]|max_length[2]'
            }, {
                name: 'location',
                display: 'Location',
                rules: 'required|alpha|max_length[2]'
            }, {
                name: 'urgency',
                display: 'Urgency',
                rules: 'required|numeric|less_than[4]|greater_than[0]|exact_length[1]'
            }, {
                name: 'email',
                display: 'Email',
                rules: 'required|valid_email|max_length[75]'
            }, {
                name: 'name',
                display: 'Name',
                rules: 'required|max_length[60]'
            }, {
                name: 'phone',
                display: 'Phone',
                rules: 'required|max_length[14]|callback_PhoneRegEx'
            }], function(errors, evt) {
                evt.preventDefault();
                resetErr();
                if (errors.length > 0) {
                    checkErrors(errors);
                } else {
                    //var form = findId('csemQuoteForm');
                    document.getElementById('csemQuoteForm').submit();
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

        var t = findId('type');
        if (t.value != "") {
            changeReg(t.value);
		} else {
			changeReg('osha');
		}

		//function getCookie(cname) {
		//	var name = cname + "=";
		//	var ca = document.cookie.split(';');
		//	for(var i=0; i<ca.length; i++) {
		//		var c = ca[i];
		//		while (c.charAt(0)==' ') c = c.substring(1);
		//		if (c.indexOf(name) != -1) {
		//			return c.substring(name.length, c.length);
		//		}
		//	}
		//	return "";
		//}

		//var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
		//var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
		//var isFirefox = typeof InstallTrigger !== 'undefined';
		//var isChrome = !!window.chrome && !isOpera;
		//var isIE = /*@cc_on!@*/false || !!document.documentMode;
		//if (isSafari || isIE) {
		//	document.cookie = "test=true";
		//	var cookie = getCookie("test");
		//	if(cookie == '') {
		//		var x = findId('safariMsg');
		//		x.style.display = 'block';
		//	}
		//}
    </script>
</body>
<?php /* adm/email.php */
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

	function dateFormat($date) {
		$d = new DateTime($date);
		return $d->format('m/d/Y');
	}

	function dateFormatSQL($date) {
		$ary = explode('-', $date);
		$date = $ary[2] . '-' . $ary[0] . '-' . $ary[1];
		$d = new DateTime($date);
		return $d->format('Y-m-d');
	}

	function getUrgency($urgency) {
		switch($urgency) {
            case '1':
                return "background-color: #ff0000;";
                break;
            case '2':
                return "background-color: #ffff00;";
                break;
            case '3':
                return "background-color: #00ff00;";
                break;
            default:
                return "";
                break;
        }
	}

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

	function transReg($regID) {
		if($regID != "") {
			return "<a href='" . $url . "form.php?r=" . $regID . "' target='_blank' title='View this quotes registration form'>" . $regID . "</a>";
		} else {
			return "";
		}
	}

	$where = "";
	if($_POST['from'] != "" && $_POST['to'] != "") {
		if($_POST['from'] == $_POST['to']) {
			$where .= "WHERE DATE(q.QuoteDate) = '" . dateFormatSQL($_POST['from']) . "' ";
		} else {
			$where .= "WHERE DATE(q.QuoteDate) BETWEEN '" . dateFormatSQL($_POST['from']) . "' AND '" . dateFormatSQL($_POST['to']) . "' ";
		}
	}
	$sql = "SELECT q.QuoteID, q.QuoteDate, q.QuoteClassTitle, o.OptionName, q.QuoteName, q.QuoteEmail, q.QuotePhone, q.QuotePrice, q.QuoteStudents, q.QuoteDays, q.QuoteUrgency, q.QuoteLocation, r.RegID FROM Quotes q LEFT JOIN Options o ON o.OptionID = q.QuoteClassTitle LEFT JOIN Registrations r ON r.QuoteID = q.QuoteID " . $where . " ORDER BY q.QuoteID DESC";
	$result = mysqli_query($con, $sql);
	$tbody = "";
	$c = 1;
	while($row = mysqli_fetch_array($result)) {
		if ($c % 2 == 0) {
			$gray = " style='background-color: #ddd;'";
		} else {
			$gray = "";
		}
		$tbody .= "<tr" . $gray . ">
			<td style='border: 1px solid #000;padding: 6px;" . getUrgency($row['QuoteUrgency']) . "'></td>
			<td style='border: 1px solid #000;padding: 6px;'>" . $row['QuoteID'] . "</td>
			<td style='border: 1px solid #000;padding: 6px;'>" . dateFormat($row['QuoteDate']) . "</td>
			<td style='border: 1px solid #000;padding: 6px;'>" . $row['QuoteName'] . "</td>
			<td style='border: 1px solid #000;padding: 6px;'><a href='mailto:" . $row['QuoteEmail'] . "'>" . $row['QuoteEmail'] . "</a></td>
			<td style='border: 1px solid #000;padding: 6px;'>" . $row['QuotePhone'] . "</td>
			<td style='border: 1px solid #000;padding: 6px;' title='" . $row['OptionName'] . "'>" . $row['QuoteClassTitle'] . "</td>
			<td style='border: 1px solid #000;padding: 6px;'>$" . $row['QuotePrice'] . "</td>
			<td style='border: 1px solid #000;padding: 6px;'>" . $row['QuoteStudents'] . "</td>
			<td style='border: 1px solid #000;padding: 6px;'>" . $row['QuoteDays'] . "</td>
			<td style='border: 1px solid #000;padding: 6px;'>" . $row['QuoteLocation'] . "</td>
			<td style='border: 1px solid #000;padding: 6px;'>" . transUrgency($row['QuoteUrgency']) . "</td>
			<td style='border: 1px solid #000;padding: 6px;'>" . transReg($row['RegID']) . "</td>
		</tr>";
		$c++;
	}

	$sql = "SELECT Email FROM Admin WHERE UserID = " . $_SESSION['UserID'];
	$result = mysqli_query($con, $sql);
	$to = "";
	while($row = mysqli_fetch_array($result)) {
		$to = $row['Email'];
	}

	mysqli_close($con);

	$body = "<html>
	<head>
		<title></title>
	</head>
	<body style='font-family: arial, helvetica, sans-serif;font-size: 14px;'>
		<table style='border: 1px solid #000;border-collapse: collapse;width: 100%;'>
			<thead>
				<tr>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>&nbsp;</th>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>#</th>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>Date</th>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>Name</th>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>Email</th>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>Phone</th>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>Class</th>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>Price</th>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>Stu.</th>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>Days</th>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>Loc.</th>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>Urg.</th>
					<th style='border: 1px solid #000;padding: 6px;background-color: #000;color: #fff;'>Reg</th>
				</tr>
			</thead>
			<tbody>
				" . $tbody . "
			</tbody>
		</table>
	</body>
	</html>";

	$subject = "CSEM Quote Download";
	$headers = "From: quotegen.macmannis.com <noreply@quotegen.macmannis.com>\r\nMIME-Version: 1.0" . "\r\nContent-type:text/html;charset=UTF-8" . "\r\n";
	$params = "-f noreply@quotegen.macmannis.com";
	mail($to, $subject, $body, $headers, $params);

	header("location: " . $url . "adm/dash.php");
?>
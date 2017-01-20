<?php /* adm/dash.php */
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

	if(!isset($_SESSION['UserID'])) {
        header("location: " . $url . "adm/index.php");
	}
	$UserID = $_SESSION['UserID'];

	function dateFormat($date) {
		$d = new DateTime($date);
		return $d->format('m/d/Y g:i:s');
	}

	function dateFormatJS($date) {
		$d = new DateTime($date);
		return $d->format('m-d-Y');
	}

	function getUrgency($urgency) {
		switch($urgency) {
            case '1':
                return "urg-red";
                break;
            case '2':
                return "urg-yellow";
                break;
            case '3':
                return "urg-green";
                break;
            default:
                return "urg-blank";
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

	$tbody = "";
	$sql = "SELECT q.QuoteID, q.QuoteDate, q.QuoteClassTitle, o.OptionName, q.QuoteName, q.QuoteEmail, q.QuotePhone, q.QuotePrice, q.QuoteStudents, q.QuoteDays, q.QuoteUrgency, q.QuoteLocation, r.RegID FROM Quotes q LEFT JOIN Options o ON o.OptionID = q.QuoteClassTitle LEFT JOIN Registrations r ON r.QuoteID = q.QuoteID ORDER BY q.QuoteID DESC";
	$result = mysqli_query($con, $sql);

	$firstDay = "";
	$lastDay = "";
	while($row = mysqli_fetch_array($result)) {
		if($lastDay == "") {
			$lastDay = dateFormatJS($row['QuoteDate']);
		}
		$firstDay = dateFormatJS($row['QuoteDate']);
		$tbody .= "<tr>
			<td class='" . getUrgency($row['QuoteUrgency']) . "'>" . $row['QuoteUrgency'] . "</td>
			<td>" . $row['QuoteID'] . "</td>
			<td>" . dateFormat($row['QuoteDate']) . "</td>
			<td>" . $row['QuoteName'] . "</td>
			<td><a href='mailto:" . $row['QuoteEmail'] . "'>" . $row['QuoteEmail'] . "</a></td>
			<td>" . $row['QuotePhone'] . "</td>
			<td title='" . $row['OptionName'] . "'>" . $row['QuoteClassTitle'] . "</td>
			<td>$" . $row['QuotePrice'] . "</td>
			<td>" . $row['QuoteStudents'] . "</td>
			<td>" . $row['QuoteDays'] . "</td>
			<td>" . $row['QuoteLocation'] . "</td>
			<td>" . transUrgency($row['QuoteUrgency']) . "</td>
			<td>" . transReg($row['RegID']) . "</td>
		</tr>";
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
		.adm-dash {
			font-family: arial, helvetica, sans-serif;
			font-size: 14px;
		}
		.adm-table {
			border: 1px solid #000;
			border-collapse: collapse;
			width: 100%;
		}
		.adm-table th, .adm-table td {
			border: 1px solid #000;
			padding: 6px;
		}
		.adm-table th {
			background-color: #000;
			color: #fff;
		}
		.adm-table tr:nth-of-type(even) {
			background-color: #ddd;
		}
		.urg-red { background-color: #ff0000;color: #ff0000; }
		.urg-yellow { background-color: #ffff00;color: #ffff00; }
		.urg-green { background-color: #00ff00;color: #00ff00; }
		.urg-blank { background-color: transparent;color: transparent; }
		.adm-email {
			display: inline-block;
			position: relative;
			margin-right: auto;
			width: 700px;
		}
		.adm-welcome {
			display: inline-block;
			position: relative;
			float: right;
			width: 300px;
			text-align: right;
			padding: 5px;
		}
		.sortable th:not(.sorttable_sorted):not(.sorttable_sorted_reverse):after { 
			content: " \25B4\25BE" 
		}
	</style>
    <link rel="stylesheet" href="<?php echo $url; ?>css/pikaday.css" />
    <script type="text/javascript" src="<?php echo $url; ?>js/moment-min.js"></script>
    <script type="text/javascript" src="<?php echo $url; ?>js/pikaday.js"></script>
    <script type="text/javascript" src="<?php echo $url; ?>js/sorttable.js"></script>
</head>
<body>
	<div class="adm-dash">
		<div class="adm-email">
			<form name="emailList" action="<?php echo $url; ?>adm/email.php" method="post">
				<button type="submit" name="email">Email me all quotes</button> (optional) Between <input type="text" name="from" id="from" value="<?php echo 1; ?>" /> and <input type="text" name="to" id="to" value="<?php echo 1; ?>" />
			</form>
		</div>
		<div class="adm-welcome">Welcome <?php echo $_SESSION['Username']; ?>! <a href="<?php echo $url; ?>adm/logout.php">(logout)</a></div>
		<table class="adm-table sortable">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>#</th>
					<th>Date</th>
					<th>Name</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Class</th>
					<th>Price</th>
					<th>Stu.</th>
					<th>Days</th>
					<th>Loc.</th>
					<th>Urg.</th>
					<th>Reg</th>
				</tr>
			</thead>
			<tbody>
				<?php echo $tbody; ?>
			</tbody>
		</table>
	</div>
	<script type="text/javascript">
        function findId(id) {
			return document.getElementById(id);
        }
		var pickerFrom = new Pikaday({
			field: findId('from'),
			format: 'MM-DD-YYYY',
			firstDay: 0,
			defaultDate: new Date('<?php echo $firstDay; ?>'),
			setDefaultDate: true,
			minDate: new Date('<?php echo $firstDay; ?>'),
			maxDate: new Date('<?php echo $lastDay; ?>')
        });
		var pickerTo = new Pikaday({
			field: findId('to'),
			format: 'MM-DD-YYYY',
			firstDay: 0,
			defaultDate: new Date('<?php echo $lastDay; ?>'),
			setDefaultDate: true,
			minDate: new Date('<?php echo $firstDay; ?>'),
			maxDate: new Date('<?php echo $lastDay; ?>')
        });
	</script>
</body>
</html>
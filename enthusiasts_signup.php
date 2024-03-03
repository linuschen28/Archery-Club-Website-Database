<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set some parameters

// Database access configuration
$config["dbuser"] = "ora_linusc";		// change "cwl" to your own CWL
$config["dbpassword"] = "a445123";	// change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;	// login credentials are used in connectToDB()

$success = true;	// keep track of errors so page redirects only if there are no errors

$show_debug_alert_messages = False; // show which methods are being triggered (see debugAlertMessage())
?>

<html lang="en-US">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Archery Club Main Page</title>

		<!-- links to the dependent files -->
		
	</head>

    <body class="wrapper">
        <!--Dashboard: same for all pages-->
        <section class="dashboard">
            <div class="dashboard">
                <!--Logo: need resizing in the CSS-->
                <img width="120rem" src="https://upload.wikimedia.org/wikipedia/commons/2/23/Moon_Bow_and_Arrow.svg">
                <h1 class="title">Vancouver Archery Club</h1>
                <ul>
                    <!--The dashboard should contain a nevigation bar that contains the section of NEWS, EVENTS, LOCATIONS, ABOUT US, SERVICES-->
                    <!--Technical Details: use inline-block to flaten the display-->
                    <li><a href="">News</a></li>
                    <li><a href="./index.html">About Us</a></li>
                    <li><a href="./events.html">Events</a></li>
                </ul>
                <!--Button for the admin login-->
                <a href="./admin_login.html">Admin Login</a>
            </div>
        </section>

        <section class="enthusiasts_signup form">
            <form action="enthusiasts_signup.php" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="John Smith"> 
                <br><br>
                <label for="email">email</label>
                <input type="email" id="email" name="email" value="xxx@gamil.com">
                <br><br>
                <input type="submit" value="Submit">

            </form>

        </section>

        <!--Footer: same for all pages-->
        <section class="contact">
            <div class="address">
                <!--img for address marker-->
                <img width = "60rem" src="https://upload.wikimedia.org/wikipedia/commons/8/88/Map_marker.svg" alt="">
                <!--The actual addresss-->
                <ul>
                    <li>xx-xx-xx way</li>
                    <li>Vancouver BC</li>
                    <li>Canada</li>
                </ul>
            </div>

            <div class="phone-number">
                <img width = "60rem" src="img/blue-phone-7152.svg" alt="">
                <p>xxx-xxx-xxxx</p>
            </div>

            <div class="email">
                <img width = "60rem" src="img/blue-mail-logo.png" alt="">
                <p>xxxxxx@gmail.com</p>

            </div>

        </section>
        
    </body>

	<?php

	function debugAlertMessage($message)
	{
		global $show_debug_alert_messages;

		if ($show_debug_alert_messages) {
			echo "<script type='text/javascript'>alert('" . $message . "');</script>";
		}
	}

	function connectToDB()
	{
		global $db_conn;
		global $config;

		// Your username is ora_(CWL_ID) and the password is a(student number). For example,
		// ora_platypus is the username and a12345678 is the password.
		// $db_conn = oci_connect("ora_cwl", "a12345678", "dbhost.students.cs.ubc.ca:1522/stu");
		$db_conn = oci_connect($config["dbuser"], $config["dbpassword"], $config["dbserver"]);

		if ($db_conn) {
			debugAlertMessage("Database is Connected");
			return true;
		} else {
			debugAlertMessage("Cannot connect to Database");
			$e = OCI_Error(); // For oci_connect errors pass no handle
			echo htmlentities($e['message']);
			return false;
		}
	}

	function disconnectFromDB()
	{
		global $db_conn;

		debugAlertMessage("Disconnect from Database");
		oci_close($db_conn);
	}

	function executeBoundSQL($cmdstr, $list)
	{
		/* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

		global $db_conn, $success;
		$statement = oci_parse($db_conn, $cmdstr);

		if (!$statement) {
			echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($db_conn);
			echo htmlentities($e['message']);
			$success = False;
		}

		foreach ($list as $tuple) {
			foreach ($tuple as $bind => $val) {
				//echo $val;
				//echo "<br>".$bind."<br>";
				oci_bind_by_name($statement, $bind, $val);
				unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
			}

			$r = oci_execute($statement, OCI_DEFAULT);
			if (!$r) {
				echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
				$e = OCI_Error($statement); // For oci_execute errors, pass the statementhandle
				echo htmlentities($e['message']);
				echo "<br>";
				$success = False;
			}
		}
	}

	function executeSQLWithTuples($cmdstr, $tuples)
	{
		global $db_conn, $success;
	
		$statement = oci_parse($db_conn, $cmdstr);
	
		if (!$statement) {
			echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($db_conn);
			echo htmlentities($e['message']);
			$success = False;
		}
	
		foreach ($tuples as $tuple) {
			foreach ($tuple as $bind => $val) {
				if (strpos($cmdstr, $bind) !== false) {
					oci_bind_by_name($statement, $bind, $val);
					unset($val);
				}
			}
		}
	
		$r = oci_execute($statement, OCI_DEFAULT);
		if (!$r) {
			echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($statement);
			echo htmlentities($e['message']);
			echo "<br>";
			$success = False;
		}
	}

	function handleInsertEnthusiast()
	{
		global $db_conn;

		if (!empty($_POST['name']) && !empty($_POST['email'])) {
			//Getting the values from user and insert data into the table
			$tuple = array(
				":bind1" => $_POST['name'],
				":bind2" => $_POST['email']
			);

			$alltuples = array(
				$tuple
			);

			executeBoundSQL("insert into Enthusiasts values (:bind2, :bind1)", $alltuples);
			executeSQLWithTuples("insert into Contact values (:bind2, 'UBC Archery Club', 2023)", $alltuples);
			oci_commit($db_conn);
			echo "SUCCESS";
		} else {
			echo "MISSING INPUT VALS";
		}

		
	}

	// HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
	function handlePOSTRequest()
	{
		if (connectToDB()) {
			handleInsertEnthusiast();
			// disconnectFromDB();
		}
	}

	if (isset($_POST['name']) || isset($_POST['email'])) {
		handlePOSTRequest();
	}

	// End PHP parsing and send the rest of the HTML content
	?>
</body>
	
</html>
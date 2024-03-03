<html lang="en-US">

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
                        // edit the login credentials in true

    $success = true;	// keep track of errors so page redirects only if there are no errors
                        //keep track of errors so it redirects the page only if there are no errors

    $show_debug_alert_messages = false; // show which methods are being triggered (see debugAlertMessage())
                                        // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())
    ?>

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

        <section class="Admin Login form">
            <form action="">
                <!--Here we check the ID, Birthday, email to verify Admin identity-->
                <label for="ID">ID</label>
                <input type="text" id="ID" name="ID"> 
                <br><br>
                <label for="Birthday">Birthday</label>
                <input type="date" id="Birthday" name="Birthday"> 
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

        // Clicking Submit should check whether the tuple in question is in the members list, and if that member id is in the Admin table as well.
        // If both are true, a message should appear. In the final product, it should redirect the user to the admin page, but that is for the future.

        // The first thing that it should do is when you insert the email, the ID, and the birthday, it should match up with the neighbours
        // And if it matches with the SQL file, it should print out success!

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            // echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }
            
            return $statement;
        }

        function connectToDB() {
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

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            oci_close($db_conn);
        }

        function handleCheckRequest() {
            global $db_conn;

            $ID = $_GET['ID'];
            $Birthday = $_GET['Birthday'];
            $email = $_GET['email'];

            //Exists ((select club_name, club_year from clubs) Except (select club_name,club_year from Enlist1)))

            $result = executePlainSQL("SELECT * FROM Members WHERE member_ID='" . $ID . "' AND member_Birthday='" . $Birthday . "' AND member_email='" . $email . "' AND MEMBER_ID IN (SELECT member_ID FROM Admin)");
            // $result = executePlainSQL("SELECT COUNT(*) FROM Members WHERE MEMBER_ID='" . $ID . "' AND MEMBER_BIRTHDAY='" . $Birthday . "' AND MEMBER_EMAIL='" . $email . "' AND MEMBER_ID IN (SELECT '". $ID . "' FROM Admin)");

            if (($row = oci_fetch_row($result)) != false) {
                debugAlertMessage("SQL statement has been processed");
                echo "<br>Login successful! Now you must redirect this to the admin page.<br>";
            }
            OCICommit($db_conn);
        }

        // HANDLE ALL GET ROUTES
        // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                handleCheckRequest();
                disconnectFromDB();
            }
        }

        if (isset($_GET['ID']) || isset($_GET['Birthday']) || isset($_GET['email'])) {
            handleGETRequest();
        }

        ?>

</html>
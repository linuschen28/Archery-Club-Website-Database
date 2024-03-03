<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set some parameters

// Database access configuration
$config["dbuser"] = "ora_linusc";			// change "cwl" to your own CWL
$config["dbpassword"] = "a445123";	// change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;	// login credentials are used in connectToDB()

$success = true;	// keep track of errors so page redirects only if there are no errors

$show_debug_alert_messages = False; // show which methods are being triggered (see debugAlertMessage())
?>


<html
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Achery Club Admin Page</title>
	</head>

    <body class = 'wrapper'>
        <!-- This is the header section of the website-->
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


        <section class = "form">
            <!--This is the form part of the HTML which should give the admin the following powers:-->
            <!--Create new events-->
            <!--Fullfilling the DELETE part of the grading scheme: DELETE events by TIME & LOCATION-->
            <!--Fullfilling the UPDATE part of the grading scheme: update details of the events BY TIME & LOCATION-->

            <h2>INSERTION: Create New Events:</h2>
            <form method="POST" action="admin_page.php">
                <input type="hidden" id="CreateEventRequest" name="CreateEventRequest">
                Admin Number: <input type="text" name="NewAdminNum" placeholder = "Ex: 001 OR 002 OR 003"> <br /><br />
                Event Name: <input type="text" name="NewName" placeholder = "Meeting OR Shootout"> <br /><br />
                Event Time: <input type="text" name="NewTime" placeholder = "Ex: 2023-10-31 @ 7PM"> <br /><br />
                Event Number: <input type="text" name="NewNum" placeholder = "Ex: 1 OR 2 OR 3 OR 4"> <br /><br />
                Event Location: <input type="text" name="NewLoc" placeholder = "Ex: 1234 Main St"> <br /><br />
                <input type="submit" value="Insert" name="NewEventSubmit"></p>
            </form>

            <?php
            // PHP code that handles CreateEventRequest

            if (isset($_POST['CreateEventRequest'])) {
                $string_1 = $_POST['NewAdminNum'] .  $_POST['NewName']. $_POST['NewLoc'].$_POST['NewNum'];
                $string_2 = $_POST["NewTime"];
                //Sanitizing the input
                if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string_1) && !preg_match('/[\'^£$%&*()}{#~?><>,|=_+¬]/', $string_2) && 
                    (is_numeric($_POST['NewNum']) || $_POST['NewNum'] == "") && (!is_numeric($_POST['NewName']) || $_POST['NewName'] == "") && (!is_numeric($_POST['NewTime']) || $_POST['NewTime'] == "") &&
                    (!is_numeric($_POST['NewLoc']) || $_POST['NewLoc'] == "") && (is_numeric($_POST['NewAdminNum']) || $_POST['NewAdminNum'] == "")) {
                    handlePOSTRequest();
                } else {
                    echo "INPUTS CONTAINING ILLEGAL CHARACTERS";
                }
            }
            ?>
            
            <h2>DELETION: Delete Events:</h2>
            <form method="POST" action="admin_page.php">
                <input type="hidden" id="DeleteEventRequest" name="DeleteEventRequest">
                Event Time: <input type="text" name="DeleteTime" placeholder = "Ex: 2023-10-31 @ 7PM"> <br /><br />
                Event Location: <input type="text" name="DeleteLoc" placeholder = "Ex: 1234 Main St"> <br /><br />
                <input type="submit" value="Delete" name="DeleteEventSubmit"></p>
            </form>

            <?php
            // PHP code that handles DeleteEventRequest
            if (isset($_POST['DeleteEventRequest'])) {

                //Sanitizing the input
                if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['DeleteLoc']) && !preg_match('/[\'^£$%&*()}{#~?><>,|=_+¬]/', $_POST['DeleteTime'])
                    && (!is_numeric($_POST['DeleteLoc']) || $_POST['DeleteLoc'] == "") && (!is_numeric($_POST['DeleteTime']) || $_POST['DeleteTime'] == "")) {
                    handlePOSTRequest();
                } else {
                    echo "INPUTS CONTAINING ILLEGAL CHARACTERS";
                }


            }
            ?>
            
            <h2>UPDATE: Update Events Name & Number by Time & Location:</h2>
            <form method="POST" action="admin_page.php">
                <input type="hidden" id="UpdateEventRequest" name="UpdateEventRequest"> 
                New Event Name: <input type="text" name="UpdateNewName" placeholder = "Meeting OR Shootout"> <br /><br />
                New Event Number: <input type="text" name="UpdateNewNum" placeholder = "Ex: 1 OR 2 OR 3 OR 4"> <br /><br />
                Event Time: <input type="text" name="UpdateOldTime" placeholder = "Ex: 2023-10-31 @ 7PM">  <br /><br />
                Event Location: <input type="text" name="UpdateOldLoc" placeholder = "Ex: 1234 Main St"> <br /><br />

                <input type="submit" value="Update" name="UpdateEventSubmit"></p>
            </form>

            <?php
            // PHP code that handles DeleteEventRequest
            if (isset($_POST['UpdateEventRequest'])) {
                $string_1 = $_POST['UpdateOldLoc'] . $_POST['UpdateNewName'];
                //Sanitizing the input
                if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string_1) && !preg_match('/[\'^£$%&*()}{#~?><>,|=_+¬]/', $_POST['UpdateOldTime']) && 
                    (is_numeric($_POST['UpdateNewNum']) || $_POST['UpdateNewNum'] == "") && (!is_numeric($_POST['UpdateOldLoc']) || $_POST['UpdateOldLoc'] == "")
                    && (!is_numeric($_POST['UpdateNewName']) || $_POST['UpdateNewName'] == "") && (!is_numeric($_POST['UpdateOldTime']) || $_POST['UpdateOldTime'] == "")) {
                    handlePOSTRequest();
                } else {
                    echo "INPUTS CONTAINING ILLEGAL CHARACTERS";
                }
            }
            ?>

            <h2> DIVISION: Find the members who have registered in all events</h2>

            <form method="GET" action="admin_page.php">
                <input type="hidden" id="DivisionEventRequest" name="DivisionEventRequest"> 
                <input type="submit" value="Query" name="DivisionEventSubmit"></p>
            </form>

            <?php
            // PHP code that handles DivisionEventRequest
            if (isset($_GET['DivisionEventRequest'])) {
                handleGetRequest();
            }
            ?>

            <h2>GROUP BY: Finding Youngest Coach By Type:</h2>
            <form method="GET" action="admin_page.php">
                <input type="hidden" id="GroupByEventRequest" name="GroupByEventRequest"> 
                <input type="submit" value="submit" name="GroupByEventSubmit"></p>
            </form>

            <?php
            // PHP code that handles DivisionEventRequest
            if (isset($_GET['GroupByEventRequest'])) {
                handleGetRequest();
            }
            ?>


        </section>


        <!-- This is the footer section of the website -->
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
</html>


<?php

// Debugging Parts
function debugAlertMessage($message) {
    global $show_debug_alert_messages;
    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
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

        
function executePlainSQL($cmdstr) { 
    //takes a plain (no bound variables) SQL command and executes it
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;
    $statement = oci_parse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work
            
    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For oci_parse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
    }
            
    $r = oci_execute($statement, OCI_DEFAULT);
            
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . " BECAUSE THE EVENT_NUMBER NEED TO BE UNIQUE<br>";
        $e = oci_error($statement); // For oci_execute errors pass the statementhandle
        echo htmlentities($e['message']);
        $success = False;
    }

    if ($success = true) {
        echo "<br>ACTION SUCCEEDED <br>";
    }


    return $statement;
}

function executeBoundSQL($cmdstr, $list) {

    //Used for Creating New Events

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
            echo "<br>Cannot execute the following command: " . $cmdstr . " BECAUSE YOU ARE TRYING TO INSERT DUPLICATES"."<br>";
            $e = OCI_Error($statement); // For oci_execute errors, pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }

    if ($success = true) {
        echo "<br>ACTION SUCCEEDED <br>";
    }


}


function printResult($result) { 
    //prints results from a select statement
    echo "<h3>Retrieved data from table Events:</h3>";
    echo "<table>";
    echo "<tr><th>Name</th><th>Time</th><th>Location</th><th>ID</th></tr>";
    
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        echo "<tr><td>" . $row["EVENT_NAME"] . "</td><td>" . $row["EVENT_TIME"] ."</td>" ."<td>".$row["EVENT_ADDRESS"]."</td><td>".$row["EVENT_NUMBER"]. "</td></tr>"; //or just use "echo $row[0]"
    }
    
    echo "</table>";
}

function handleCreateEvents() {
    global $db_conn;

    if (!empty($_POST['NewName']) && !empty($_POST['NewTime']) && !empty($_POST['NewLoc']) && !empty($_POST['NewAdminNum']) && !empty($_POST['NewNum'])) {
        $tuple1 = array(
            ":bind1" => $_POST['NewName'],
            ":bind2" => $_POST['NewTime'],
            ":bind3" => $_POST['NewLoc'],
            ":bind4" => $_POST['NewNum']
        );
    
        $alltuples1 = array(
            $tuple1
        );

        $tuple2 = array(
            ":bind2" => $_POST['NewTime'],
            ":bind3" => $_POST['NewLoc'],
            ":bind4" => $_POST['NewAdminNum']
        );

        $alltuples2 = array(
            $tuple2
        );

    
        // Events(Time, Address, Name) , Schedule (Member_id, Time, Address)
        executeBoundSQL("insert into Events values (:bind2, :bind3, :bind1, :bind4)", $alltuples1);
        executeBoundSQL("insert into Schedule values (:bind4, :bind2, :bind3)", $alltuples2);
        //test Event: ('2023-11-28 @ 7PM', '1234 Main St', 'Shootout')
        oci_commit($db_conn);
        
        
        
    } else {
        echo "MISSING INPUT VALS";

    }

    //Display the Entire Event Table for demonstration Reasons
    $cmd = "SELECT * FROM EVENTS";

    $result = executePlainSQL($cmd);
    
    printResult($result);

}

function handleDeleteEvents() {
    global $db_conn;

    if (!empty($_POST['DeleteLoc']) || !empty($_POST['DeleteTime'])) {

        $cmd1 = "Delete From Events where ";

        if (!empty($_POST['DeleteLoc'])) {
            $cmd1 .= "event_address = ". "'".$_POST['DeleteLoc']."'";
        }

        if (!empty($_POST['DeleteTime'])) {
            if (!empty($_POST['DeleteLoc'])) {
                $cmd1 .= " AND ";
            }
            $cmd1 .= "event_time = ". "'".$_POST['DeleteTime']."'";
        }
        // Remove from participate, schedule, and events table
        $result1 = executePlainSQL($cmd1);
        oci_commit($db_conn);
        
    } else {
        echo "MISSING INPUT VALS";
    }

    //Display the Entire Event Table for demonstration Reasons
    $cmd = "SELECT * FROM EVENTS";

    $result = executePlainSQL($cmd);
    
    printResult($result);
}

function handleUpdateEvents() {
    global $db_conn;
    if (!empty($_POST['UpdateOldTime']) && !empty($_POST['UpdateOldLoc']) && (!empty($_POST['UpdateNewName']) || !empty($_POST['UpdateNewNum']))) {
        //We need 1 cell from both columns to update

        $cmd1 = "Update Events SET ";
        

        if (!empty($_POST['UpdateNewName'])) {

            $cmd1 .= "event_name = ". "'".$_POST['UpdateNewName']."'";

        }

        if(!empty($_POST['UpdateNewNum'])) {
            if (!empty($_POST['UpdateNewName'])) {
                $cmd1 .= " , ";
            }
            $cmd1 .= "event_number = ". "'".$_POST['UpdateNewNum']."'";

        }


        $cmd1 .= " WHERE ";

        $cmd1 .= "event_time = ". "'".$_POST['UpdateOldTime']."'";
        $cmd1 .= " AND ";
        $cmd1 .= "event_address = ". "'".$_POST['UpdateOldLoc']."'";

        

        // Update events table
    
        $result1 = executePlainSQL($cmd1);

        oci_commit($db_conn);





    } else {
        echo "MISSING INPUT VALS";
    }

    $cmd = "SELECT * FROM EVENTS";

    $result = executePlainSQL($cmd);
    
    printResult($result);

}

function handleDivisionEvent() {
    global $db_conn;

    // This cmd works with BATMAN returned as a result. 
    $cmd = "SELECT * FROM Members M WHERE NOT EXISTS (SELECT E.event_time, E.event_address FROM Events E WHERE NOT EXISTS (SELECT P.member_id FROM
    Participate P WHERE P.event_address = E.event_address AND P.event_time = E.event_time AND P.member_id = M.member_id))";
    
    $result = executePlainSQL($cmd);

    //prints results from a select statement
    echo "<h3>Retrieved data from table Members:</h3>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Birthday</th><th>Email</th></tr>";
        
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        echo "<tr><td>" . $row["MEMBER_ID"] . "</td><td>" . $row["MEMBER_NAME"] ."</td>" ."<td>".$row["MEMBER_BIRTHDAY"]. "</td><td>".$row["MEMBER_EMAIL"]."</td></tr>"; //or just use "echo $row[0]"
    }
        
    echo "</table>";
}

function handleGroupByEvent() {
    global $db_conn;

    $cmd = "SELECT coach_type, MIN(coach_age) FROM Coach GROUP BY coach_type";


    $result = executePlainSQL($cmd);

    //prints results from a select statement
    echo "<h3>Retrieved data from table Coach:</h3>";
    echo "<table>";
    echo "<tr><th>Type</th><th>Age</th></tr>";
        
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        echo "<tr><td>" . $row["COACH_TYPE"] . "</td><td>" . $row["MIN(COACH_AGE)"] ."</td></tr>"; //or just use "echo $row[0]"
    }
        
    echo "</table>";
}

        
function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('CreateEventRequest', $_POST)) {
            handleCreateEvents();
        } else if (array_key_exists('DeleteEventSubmit', $_POST)) {
            handleDeleteEvents();
        } else if (array_key_exists('UpdateEventSubmit', $_POST)) {
            handleUpdateEvents();
        }


        disconnectFromDB();
    }
}

function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('DivisionEventSubmit', $_GET)) {
            //Correct Output here should be BATMAN
            handleDivisionEvent();
        } else if (array_key_exists('GroupByEventSubmit', $_GET)) {
            //Correct Output here should be BATMAN
            handleGroupByEvent();
        }

        disconnectFromDB();
    }

    
}

?>
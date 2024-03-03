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

<html>

    <head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Achery Club Events Page</title>
		
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

        <!-- This section is for query using the table events -->
        <section class = 'events_query'>
            <!-- Here is the main section of the events page where it allows users to query on events:-->
            <!-- Functionalities: Query based on Event Name; Time; Location ; Projection: Showing all event time & Location & Name-->

            <h2>SELECTION: Find Events By Name, Time, and Location</h2>

            <form method="GET" action="events.php">
                <input type="hidden" id="SelectQueryRequest" name="SelectQueryRequest">
                Event Name: <input type="text" name="QryName" placeholder = "Meeting OR Shootout"> <br /><br />
                Event Time: <input type="text" name="QryTime" placeholder = "Ex: 2023-10-31 @ 7PM"> <br /><br />
                Event Location: <input type="text" name="QryLoc" placeholder = "Ex: 1234 Main St"> <br /><br />
                <input type="submit" value="Select" name="QrySubmit"></p>
            </form>

            <?php

            if (isset($_GET['SelectQueryRequest'])) {
                //Checking all special charcters in Event Name, Location; Also check all special characters in time but allow - @ because of the formatting
                $string_1 = $_GET['QryName'] .  $_GET['QryLoc'];
                $string_2 = $_GET["QryTime"];
                if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string_1) && !preg_match('/[\'^£$%&*()}{#~?><>,|=_+¬]/', $string_2)
                    && ($_GET['QryLoc'] == "" || !is_numeric($_GET['QryLoc'])) && ($_GET['QryName'] == "" || !is_numeric($_GET['QryName'])) &&
                    (!is_numeric($_GET['QryTime']) || $_GET['QryTime'] == "")) {
                    handleGETRequest();
                } else {
                    echo "INPUTS CONTAINING ILLEGAL CHARACTERS";
                }
            }

            ?>

            <h2>PROJECTION: Find ALL Events By Name, Time, Location, and Number</h2>

            <form method="GET" action="events.php">
                <input type="hidden" id="ProjectQueryRequest" name="ProjectQueryRequest">
                Event Name: <input type="checkbox" value = "event_name" name="ProjectName" > 
                Event Time: <input type="checkbox" value = "event_time" name="ProjectTime" > 
                Event Location: <input type="checkbox" value = "event_address" name="ProjectLoc">
                Event Number: <input type="checkbox" value = "event_number" name="ProjectNum"> <br /><br />
                <input type="submit" value="Project" name="ProjectSubmit"></p>
            </form>

            <?php
            if (isset($_GET['ProjectQueryRequest'])) {
                handleGETRequest();
            }

            ?>

            <h2>JOIN: Find the Registered Events by ID:</h2>

            <form method="GET" action="events.php">
                <input type="hidden" id="JoinQueryRequest" name="JoinQueryRequest">
                Member ID <input type="text" name="JoinID"  placeholder = "Ex: 001 OR 002 OR 003"> <br /><br />

                <input type="submit" value="submit" name="JoinSubmit"></p>
            </form>

            <?php
            if (isset($_GET['JoinQueryRequest'])) {

                if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_GET['JoinID']) && (is_numeric($_GET['JoinID']) || $_GET['JoinID'] == "")) {
                    handleGETRequest();
                } else {
                    echo "INPUTS CONTAINING ILLEGAL CHARACTERS";
                }
            }

            ?>

            <h2>GROUP BY HAVING: Find All the Events that Have Participants:</h2>

            <form method="GET" action="events.php">
                <input type="hidden" id="HavingEventRequest" name="HavingEventRequest">
                <input type="submit" value="submit" name="HavingSubmit"></p>
            </form>

            <?php
            if (isset($_GET['HavingEventRequest'])) {
                handleGETRequest();
            }

            ?>
            
            <h2>NESTED GROUP BY: Maximum Participants for each Event Location</h2>
            <form method="GET" action="events.php">
                <input type="hidden" id="SelectQueryRequest" name="NestedAggregateQueryRequest">
                <!-- Member ID <input type="text" name="JoinID"> <br /><br /> -->
                <input type="submit" value="submit" name="NestedAggregateSubmit"></p>
            </form>
            
            <?php
            if (isset($_GET['NestedAggregateQueryRequest'])) {
                handleGETRequest();
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
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For oci_execute errors pass the statementhandle
        echo htmlentities($e['message']);
        $success = False;
    }

    return $statement;
}



function printResult($result) { 
    //prints results from a select statement
    echo "<h3>Retrieved data from table Events:</h3>";
    echo "<table>";
    echo "<tr><th>Name</th><th>Time</th><th>Location</th></tr>";
    
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        echo "<tr><td>" . $row["EVENT_NAME"] . "</td><td>" . $row["EVENT_TIME"] ."</td>" ."<td>".$row["EVENT_ADDRESS"]. "</td></tr>"; //or just use "echo $row[0]"
    }
    
    echo "</table>";
}

function handleSelectEvent() {
    global $db_conn;
    //Getting the values from user and insert data into the table

    $cmd = "SELECT * FROM Events";

    if (!empty($_GET["QryName"]) || !empty($_GET["QryTime"]) || !empty($_GET["QryLoc"])) {

        $cmd .= " WHERE ";

    }

    if (!empty($_GET["QryName"])) {
        // Event Name text box non-empty

        $cmd .= "event_name = ". "'".$_GET["QryName"]."'";

    }

    if (!empty($_GET["QryTime"])) {
        // Event Time box non-empty

        if (!empty($_GET["QryName"])) {
            // Event Time text box non-empty AND Event Name text box non-empty
            $cmd .= " AND ";
        }

        $cmd .= "event_time = ". "'".$_GET["QryTime"]."'" ;

    }

    if (!empty($_GET["QryLoc"])) {
        // Event Location text box non-empty

        if (!empty($_GET["QryTime"]) || !empty($_GET["QryName"])) {
            // Event Location text box non-empty AND Event Time text box non-empty AND/OR Event Name text box non-empty
            $cmd .= " AND ";
        }

        $cmd .= "event_address = ". "'".$_GET["QryLoc"]."'" ;

    }



    $result = executePlainSQL($cmd);

    printResult($result);
    
    
}
        

        
function handleProjectEvent() {
    global $db_conn;
    //Getting the values from user and insert data into the table


   


    $cmd = "SELECT ";

    $table_col = "<tr>";

    $char_Name = "";
    $char_Time = "";
    $char_Loc = "";
    $char_Num = "";

    if (!empty($_GET["ProjectName"])) {
        // Event Name box is checked

        $table_col .= "<th>Name</th>";
        $char_Name = "EVENT_NAME";

        $cmd .= $_GET["ProjectName"];

    }

    if (!empty($_GET["ProjectTime"])) {
        // Event Name box is checked

        $table_col .= "<th>Time</th>";
        $char_Time = "EVENT_TIME";

        if (!empty($_GET["ProjectName"])) {
            $cmd .= ", ";
        }

        $cmd .= $_GET["ProjectTime"];

    }

    if (!empty($_GET["ProjectLoc"])) {
        // Event Name box is checked

        $table_col .= "<th>Location</th>";
        $char_Loc = "EVENT_ADDRESS";

        if (!empty($_GET["ProjectName"]) || !empty($_GET["ProjectTime"])) {
            $cmd .= ", ";
        }

        $cmd .= $_GET["ProjectLoc"];
    }

    if (!empty($_GET["ProjectNum"])) {
        // Event Name box is checked

        $table_col .= "<th>Number</th>";
        $char_Num = "EVENT_NUMBER";

        if (!empty($_GET["ProjectName"]) || !empty($_GET["ProjectTime"]) || !empty($_GET["ProjectLoc"])) {
            $cmd .= ", ";
        }

        $cmd .= $_GET["ProjectNum"];
    }

    $table_col .= "</tr>";

    if (empty($_GET["ProjectNum"]) && empty($_GET["ProjectLoc"]) && empty($_GET["ProjectName"]) && empty($_GET["ProjectTime"])) {
        $cmd .= " * ";
        $cmd .= " FROM Events";
        $result = executePlainSQL($cmd);
        printResult($result);
    } else {
        $cmd .= " FROM Events";
    
        $result = executePlainSQL($cmd);
    
        echo "<h3>Retrieved data from table Events:</h3>";
        echo "<table>";
    
        echo $table_col;
    
        while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
    
            $table_row = "<tr>";
    
            if ($char_Name != "") {
                
                $table_row .= "<td>". $row[$char_Name]."</td>";
            }
    
            if ($char_Time != "") {
                $table_row .= "<td>". $row[$char_Time]."</td>";
            }
    
            if ($char_Loc != "") {
                $table_row .= "<td>". $row[$char_Loc]."</td>";
            }
    
            if ($char_Num != "") {
                $table_row .= "<td>". $row[$char_Num]."</td>";
            }
    
            $table_row .= "</tr>";
    
            echo $table_row;
    
        }
        
        echo "</table>";
    }

    
}

function handleJoinEvent() {
    // This function allows users to find all their registered events by joining Table Partcipate & Events:

    global $db_conn;


    $cmd = "SELECT e.event_time, e.event_address, e.event_name
     FROM Events e, Participate p  WHERE e.event_time = p.event_time AND e.event_address = p.event_address ";

    if (!empty($_GET["JoinID"])) {
        $cmd .= "AND member_id =". "'".$_GET["JoinID"]."'";
    } else {
        echo "NO MEMBER ID INPUTED";
        $cmd = "SELECT * FROM Events";
    }

    $result = executePlainSQL($cmd);

    printResult($result);

}

function handleHavingEvent() {
    // This function allows users to find all their registered events by joining Table Partcipate & Events:

    global $db_conn;


    $cmd = "SELECT E.event_name, P.event_time, P.event_address, COUNT(P.member_id) FROM Participate P, Events E WHERE P.event_time = E.event_time AND 
    P.event_address = E.event_address GROUP BY E.event_name, P.event_time, P.event_address HAVING COUNT(P.member_id) > 0";

    
    $result = executePlainSQL($cmd);

    //prints results from a select statement
    echo "<h3>Retrieved data from table Events, Participate:</h3>";
    echo "<table>";
    echo "<tr><th>Name</th><th>Time</th><th>Location</th><th>Count</th></tr>";
    
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        echo "<tr><td>" . $row["EVENT_NAME"] . "</td><td>" . $row["EVENT_TIME"] ."</td>" ."<td>".$row["EVENT_ADDRESS"]."</td><td>". $row["COUNT(P.MEMBER_ID)"]. "</td></tr>"; //or just use "echo $row[0]"
    }
    
    echo "</table>";


}

function handleNestedAggregateEvent() {
    // This functions allows all users to see the max amount of participants that have been to each location 
    
    global $db_conn;


    $cmd = "SELECT event_address, MAX(AttendeeCount) AS MaxAttendeeCount
            FROM (SELECT p.event_address, COUNT(p.member_id) AS AttendeeCount
                    FROM Participate p
                    GROUP BY p.event_address)
    GROUP BY event_address";

    $result = executePlainSQL($cmd);

    //prints results from a select statement
    echo "<h3>Retrieved data from table Events:</h3>";
    echo "<table>";
    echo "<tr><th>Location</th><th>Max Attendee Count</th></tr>";
    
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        echo "<tr><td>" . $row["EVENT_ADDRESS"] . "</td><td>" . $row["MAXATTENDEECOUNT"] . "</td></tr>"; //or just use "echo $row[0]"
    }
    
    echo "</table>";

}

function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('QrySubmit', $_GET)) {
            handleSelectEvent();
        } else if (array_key_exists('ProjectSubmit', $_GET)) {
            handleProjectEvent();
        } else if (array_key_exists('JoinSubmit', $_GET)) {
            handleJoinEvent();
        } else if (array_key_exists('HavingSubmit', $_GET)) {
            handleHavingEvent();
        } else if (array_key_exists('NestedAggregateSubmit', $_GET)) {
            handleNestedAggregateEvent();
        }

        disconnectFromDB();
    }

    
}

?>
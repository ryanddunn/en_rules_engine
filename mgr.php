<?php
    $servername = "localhost";
    $username = "root";
    $password = "appl35!@";
    $dbname = "en_RulesEngine";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    //$user_id = "1"; // sandbox
    //$user_id = "2"; // production
    //http://10.0.0.9/en_rules_engine/mgr.php?user=1
    $user_id = $_GET["user_id"]; 

    if(!isset($_GET["user_id"])){ $user_id = "1"; }

    echo "<h2> User: " . $user_id . "</h2>";

    echo "Menu: ";
    echo "<a href=\"mgr.php\">Main</a> |  ";    
    echo "<a href=\"mgr_rule_add.php?user_id=".$user_id."\">Add Rule</a> ";    
    echo "<hr>";

    $rules_result = $conn->query("SELECT * FROM Rules where user_id=" . $user_id);
    if ($rules_result->num_rows > 0) {
        while($row = $rules_result->fetch_assoc()) {
            echo "<b>Rule " . $row["id"] . " - " . $row["title"] . "</b></br>\n";
            echo "... Search Term: " . $row["search_term"];
            echo " (<a href=\"mgr_action_add.php?rule_id=".$row["id"]."&user_id=".$user_id."\">Add Action</a>)<br \>";
            //echo "... User: " . $row["user_id"] ."<br>\n";
            
            $action_result = $conn->query("SELECT * FROM Actions where rule_id=" . $row["id"]);
            if ($action_result->num_rows > 0) {
                while($row_action = $action_result->fetch_assoc()) {    
                    echo "...... Action " . $row_action["id"] . " (type:" . $row_action["type"] . 
                        ") Tag Name: " .$row_action["tag_name"] . "</br>\n";
                }
            }
            echo "<hr>";
        }
    }

    $conn->close(); 
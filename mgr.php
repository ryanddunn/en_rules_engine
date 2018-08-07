<?php
    include 'config.php';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    function truncate($string, $length, $dots = "...") {
        return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
    }

    //$user_id = "1"; // sandbox
    //$user_id = "2"; // production
    //http://10.0.0.9/en_rules_engine/mgr.php?user=1
    $user_id = $_GET["user_id"];

    if(!isset($_GET["user_id"])){
        echo "<script>window.location.replace(\"mgr.php?user_id=1\");</script>";
    }

    echo "<h2> User: " . $user_id . "</h2>";
    echo "Menu: ";
    echo "<a href=\"mgr.php?" . $_GET["user_id"] . "\">Main</a> |  ";
    echo "<a href=\"mgr_rule_add.php?user_id=".$user_id."\">Add Rule</a> ";
    echo "<hr>";

    $rules_result = $conn->query("SELECT * FROM Rules where user_id=" . $user_id);
    if ($rules_result->num_rows > 0) {
        while($row = $rules_result->fetch_assoc()) {
            // delete link
            echo "<a href=\"mgr_delete_all.php?user_id=" . $_GET["user_id"]
                . "&rule_id=" . $row["id"] . "\">[delete]</a>";

            // note title
            echo " <textarea readonly rows=1 style=\"border: none; width:500px \">" . $row["search_term"] . "</textarea> ";


            $action_result = $conn->query("SELECT * FROM Actions where rule_id=" . $row["id"]);
            if ($action_result->num_rows > 0) {
                while($row_action = $action_result->fetch_assoc()) {
                    //e cho "<b style=\"background-color: green; color:white\">" . $row_action["id"] . "-" . $row_action["type"] .
                    //    " " .$row_action["tag_name"] ."</b> ";
                    echo "<b style=\"font-size: .8em; background-color: green; color:white\">&nbsp;" .$row_action["tag_name"] ."&nbsp;</b> ";
                        //. " (<a href=\"\">Delete ... not done</a>)</br>\n";
                }
            }
            echo "<br>";
        }
    }

    $conn->close();

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

    echo "<ul>";
    $result = $conn->query("SELECT * FROM Logs Order By timestamp DESC Limit 0,20");
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            echo "<li>";
            echo $row["timestamp"];

            echo "<ul>";
            $detail_result = $conn->query("SELECT * FROM Logs_Detail where log_id=" . $row["id"]);
            if ($detail_result->num_rows > 0) {
                while($row_detail = $detail_result->fetch_assoc()) {
                    echo "<li>";
                    echo "<a target=\"_blank\" href=\"https://www.evernote.com/Home.action#n=" . $row_detail["note_guid"] .
                        "\">" . $row_detail["note_title"] . "</a>";
                    // https://www.evernote.com/Home.action#n=a349ca9b-04d0-4567-807a-f71f0c3c7c18
                    echo "</li>";
                }
            }
            echo "</ul>";
            echo "</li>";


        }
    }
    echo "</ul>";
    $conn->close();

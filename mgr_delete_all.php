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
    $user_id = $_GET["user_id"]; 
    echo "<h2> User: " . $_GET["user_id"] . "</h2>";

    echo "Menu: ";
    echo "<a href=\"mgr.php?user_id=".$user_id."\">Main</a> |  ";    
    echo "<a href=\"mgr_rule_add.php?user_id=".$user_id."\">Add Rule</a> ";    
    echo "<hr>";

    if (!empty($_GET["rule_id"])){ // --------- show blank form
        $sql =  "DELETE FROM Actions WHERE rule_id=" . $_GET["rule_id"];
        if ($conn->query($sql) === TRUE) { 
            $sql =  "DELETE FROM Rules WHERE id=" . $_GET["rule_id"];
            if ($conn->query($sql) === TRUE) { 
                echo "<script>window.location.replace(\"mgr.php?user_id=".$user_id."\");</script>";                
            }
            else {
                echo "Delete Rules - Error: " . $sql . "<br>" . $conn->error;
            } 
        } 
        else {
            echo "Delete Actions - Error: " . $sql . "<br>" . $conn->error;
        } 
        $conn->close();   
    }
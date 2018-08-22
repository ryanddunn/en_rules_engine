<?php
    include 'config.php';

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

    $action_id = $_GET["action_id"];


    echo "Action ID: " . $_POST["action_id"] . "<br />";


    // add the new ACTION
    $sql =  "delete from Actions where id=" . $action_id;
    if ($conn->query($sql) === TRUE)
    {
        echo "<script>window.location.replace(\"mgr_rule_edit.php?rule_id=".$_GET["rule_id"]."\");</script>";
    }
    else {
        $no_errors = false;
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

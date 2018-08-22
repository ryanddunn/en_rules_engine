<?php
    include 'config.php';

    //$user_id = "1"; // sandbox
    //$user_id = "2"; // production

    include 'inc_menu.php';

    // ----- process form and redirect to mgr.php
    //echo "Rule ID: " . $_GET["rule_id"] . "<br />";

    echo "<form action=\"mgr_action_edit.php\" method=\"post\">";

    // show the rule information
    $sql = "select * from Actions where id = " . $_GET["action_id"];
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row_action = $result->fetch_assoc()) {

            // list all information for that specific rule
            echo "<a href=\"http://en-rules.ryandunn.co/mgr_rule_edit.php?rule_id=".$row_action["rule_id"]."\">Back to Rule</a><br />";
            echo "Action ID: ".$row_action["id"]."<br />";
            echo "<input type=\"hidden\" name=\"action_id\" value=\"".$row_action["id"]."\">";
            echo "Action Tag Name: <input type=\"text\" name=\"action_tag_name\" value=\"".$row_action["tag_name"]."\"> <br />";
            echo "<input type=\"hidden\" name=\"action\" value=\"save\"> <br />";
            // ====================================
            echo "<input value=\"Save\" type=\"submit\">";
        }
    }
    echo "</form>";

    if (!empty($_POST["action"])){ // --------- show blank form
        // update the rules
        $action_id = $_POST["action_id"];
        $action_tag_name = $_POST["action_tag_name"];
        echo "save button pressed, saving rules information <br />";
        $sql = "UPDATE Actions SET " .
            "tag_name='$action_tag_name' " .
            "WHERE id = " . $action_id;
        if ($conn->query($sql) === TRUE) {
            echo "Action updated successfully <br />";
            echo "<script> window.location = \"http://en-rules.ryandunn.co/mgr_action_edit.php?action_id=".$_POST["action_id"]."\"; </script>";
        } else {
            echo "Error updating Rules: " . $conn->error;
        }
    }
    $conn->close();

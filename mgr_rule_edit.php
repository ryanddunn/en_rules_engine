<?php
    include 'config.php';

    //$user_id = "1"; // sandbox
    //$user_id = "2"; // production

    include 'inc_menu.php';

    // ----- process form and redirect to mgr.php
    //echo "Rule ID: " . $_GET["rule_id"] . "<br />";

    echo "<form action=\"mgr_rule_edit.php\" method=\"post\">";

    // show the rule information
    $sql = "select * from Rules where id = " . $_GET["rule_id"];
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row_rule = $result->fetch_assoc()) {

            // list all information for that specific rule
            echo "Rule Title: <input type=\"text\" name=\"rule_title\" value=\"".$row_rule["title"]."\"> <br />";
            echo "<input type=\"hidden\" name=\"rule_id\" value=\"".$row_rule["id"]."\">";
            echo "Rule Search Term: <input type=\"text\" name=\"rule_search_term\" value=\"".$row_rule["search_term"]."\"> <br />";
            echo "<input type=\"hidden\" name=\"action\" value=\"save\"> <br />";
            // ====================================
            echo "<input value=\"Save\" type=\"submit\">";
            echo "<br /><br /><b><u>Manage Actions</u></b><br />";
            echo "<ul>";
            echo "<li>Add a new Action (not done!)</li>";
            $conn2 = $conn;
            //$counter_action = 0;
            $sql = "select * from Actions where rule_id = " . $row_rule["id"];
            $result = $conn2->query($sql);
            if ($result->num_rows > 0) {
                while($row_action = $result->fetch_assoc()) {
                    echo "<li>";
                    echo $row_action["tag_name"]." - <a href=\"mgr_action_edit.php?action_id=".$row_action["id"]."\">[Edit]</a>";
                    echo "  <ul>";
                    echo "      <li>Type: ".$row_action["type"]."</li>";
                    echo "      <li>Notebook GUID: ".$row_action["nb_guid"]."</li>";
                    echo "  </ul>";
                    echo "</li>";
                }
            }
            echo "</ul>";
        }
    }
    echo "</form>";

    if (!empty($_POST["action"])){ // --------- show blank form
        // update the rules
        $rule_title = $_POST["rule_title"];
        $rule_id = $_POST["rule_id"];
        //$rule_user_id = $_POST["rule_user_id"];
        $rule_search_term = $_POST["rule_search_term"];
        //$counter_action = $_POST["counter_action"];
        echo "save button pressed, saving rules information <br />";
        $sql = "UPDATE Rules SET " .
            "title='$rule_title', " .
            "search_term='$rule_search_term' " .
            "WHERE id = " . $rule_id;
        if ($conn->query($sql) === TRUE) {
            echo "Rules updated successfully <br />";
            echo "<script> window.location = \"http://en-rules.ryandunn.co/mgr_rule_edit.php?rule_id=".$_POST["rule_id"]."\"; </script>";
        } else {
            echo "Error updating Rules: " . $conn->error;
        }
    }
    $conn->close();

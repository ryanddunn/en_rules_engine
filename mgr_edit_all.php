<?php
    include 'config.php';

    //$user_id = "1"; // sandbox
    //$user_id = "2"; // production

    include 'inc_menu.php';

    // ----- process form and redirect to mgr.php
    //echo "Rule ID: " . $_GET["rule_id"] . "<br />";

    echo "<form action=\"mgr_edit_all.php\" method=\"post\">";

    // show the rule information
    $sql = "select * from Rules where id = " . $_GET["rule_id"];
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row_rule = $result->fetch_assoc()) {

            // list all information for that specific rule
            echo "Rule Title: <input type=\"text\" name=\"rule_title\" value=\"".$row_rule["title"]."\"> <br />";
            echo "Rule ID: <input type=\"text\" name=\"rule_id\" value=\"".$row_rule["id"]."\"> <br />";
            echo "Rule Search Term: <input type=\"text\" name=\"rule_search_term\" value=\"".$row_rule["search_term"]."\"> <br />";
            echo "Rule User ID: <input type=\"text\" name=\"rule_user_id\" value=\"".$row_rule["user_id"]."\"> <br />";
            echo "Form Action: <input type=\"text\" name=\"action\" value=\"save\"> <br />";
            // ====================================
            $conn2 = $conn;
            $counter_action = 0;
            $sql = "select * from Actions where rule_id = " . $row_rule["id"];
            $result = $conn2->query($sql);
            if ($result->num_rows > 0) {
                while($row_action = $result->fetch_assoc()) {
                    echo "<hr />";
                    echo "Action ID: <input type=\"text\" name=\"action_id_$counter_action\" value=\"".$row_action["id"]."\"> <br />";
                    echo "Action Rule ID: <input type=\"text\" name=\"action_rule_id_$counter_action\" value=\"".$row_action["rule_id"]."\"> <br />";
                    echo "Action Type: <input type=\"text\" name=\"action_type_$counter_action\" value=\"".$row_action["type"]."\"> <br />";
                    echo "Action Tag Name: <input type=\"text\" name=\"action_tag_name_$counter_action\" value=\"".$row_action["tag_name"]."\"> <br />";
                    echo "Action Notebook GUID: <input type=\"text\" name=\"action_nb_guid_$counter_action\" value=\"".$row_action["nb_guid"]."\"> <br />";
                    echo "Action Count: <input type=\"text\" name=\"action_rule_id\" value=\"".$counter_action."\"> <br />";
                    $counter_action ++;
                }
            }
            echo "<hr>Total Actions to Process: <input type=\"text\" name=\"counter_action\" value=\"".$counter_action."\"> <br />";

            echo "<input value=\"Save\" type=\"submit\">";
        }
    }
    echo "</form>";

    if (!empty($_POST["action"])){ // --------- show blank form
        // update the rules
        $rule_title = $_POST["rule_title"];
        $rule_id = $_POST["rule_id"];
        $rule_user_id = $_POST["rule_user_id"];
        $rule_search_term = $_POST["rule_search_term"];
        $counter_action = $_POST["counter_action"];

        echo "save button pressed, saving rules information <br />";

        $sql = "UPDATE Rules SET " .
            "user_id=$rule_user_id, " .
            "title='$rule_title', " .
            "search_term='$rule_search_term' " .
            "WHERE id = " . $rule_id;
        //echo $sql;
        $conn2 = $conn;
        if ($conn->query($sql) === TRUE) {
            echo "Rules updated successfully, looping through Actions <br />";
            for ($x = 0; $x < $counter_action; $x++) {
                echo "Action ID: " . $_POST["action_id_".$x] . "<br />";
                echo "Action Rule ID: " . $_POST["action_rule_id_".$x] . "<br />";
                echo "Action Type: " . $_POST["action_type_".$x] . "<br />";
                echo "Action Tag Name: " . $_POST["action_tag_name_".$x] . "<br />";
                echo "Action Notebook GUID: " . $_POST["action_nb_guid_".$x] . "<br />";
                echo "<hr />";

                //UPDATE `Actions` SET `id`=[value-1],`rule_id`=[value-2],`type`=[value-3],`tag_name`=[value-4],`nb_guid`=[value-5] WHERE 1

                $sql_action = "UPDATE Actions SET " .
                    "type='".$_POST["action_type_".$x]."', " .
                    "tag_name='".$_POST["action_tag_name_".$x]."', " .
                    "nb_guid='".$_POST["action_nb_guid_".$x]."' " .
                    "WHERE id = " . $_POST["action_id_".$x];
                echo "Action SQL: " .$sql_action;
                // if ($conn2->query($sql_action) === TRUE) {
                //
                // } else {
                //     echo $sql_action." <br />Error updating Action: " . $conn->error;
                // }
                // $conn2->close();
            }
        } else {
            echo "Error updating Rules: " . $conn->error;
        }


        // loop through each action related to the rules
    }
    $conn->close();

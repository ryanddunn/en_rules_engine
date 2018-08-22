<?php
    include 'config.php';

    function nbDropDown($conn, $i_user_id, $i_selected_nb_guid = ""){
        echo "Notebook: ";
        echo "<select name=\"nb_guid\">\n";
        $sql = "select * from Notebooks where user_id = " . $i_user_id ." order by label";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row_action = $result->fetch_assoc()) {
                if(strcmp($i_selected_nb_guid, $row_action["nb_guid"]) == 0)
                {$nb_selected = "selected";}
                else
                {$nb_selected = "";}

                echo "<option ".$nb_selected." value=\"".$row_action["nb_guid"]."\">".$row_action["label"]."</option>\n";
            }
        }
        echo "</select><br />\n";
    }

    $user_id = $_GET["user_id"];
    echo "<h2> User: " . $user_id . "</h2>";

    echo "Menu: ";
    echo "<a href=\"mgr.php?user_id=".$user_id."\">Main</a> |  ";
    echo "<a href=\"mgr_rule_add.php?user_id=".$user_id."\">Add Rule</a> ";
    echo "<hr>";

    if (empty($_POST["action"])){ // --------- show blank form

        ?>
        <form action="mgr_rule_add.php?user_id=<?php echo $user_id; ?>" method="post">
            Rule Name: <input type="text" name="rule_name"><br>
            Search Term: <input type="text" name="search_term"><br />
            <?php
                nbDropDown($conn, "2");
            ?>
            <!-- ========================= -->
            <hr>
            #1 Tag Name: <input type="text" name="tag_name_1"><br>
            #1 Action Type: <input type="text" name="tag_type_1" value="tag"><br>
            <hr>
            #2 Tag Name: <input type="text" name="tag_name_2"><br>
            #2 Action Type: <input type="text" name="tag_type_2" value="tag"><br>
            <hr>
            #3 Tag Name: <input type="text" name="tag_name_3" value="EN Rules Engine"><br>
            #3 Action Type: <input type="text" name="tag_type_3" value="tag"><br>
            <input type="hidden" name="action" value="add_rule">
            <input type="submit">
        </form>
        <?php

    }else{ // ----- process form and redirect to mgr.php
        echo "Rule Name: " . $_POST["rule_name"] . "<br />";
        echo "Search Term: " . $_POST["search_term"] . "<br />";
        echo "Hidden Action: " . $_POST["action"] . "<br />";
        $new_rule_id = "";

        // add the new RULE
        $sql = "INSERT INTO Rules " .
            "(id, user_id, title, search_term, nb_guid) VALUES " .
            "(NULL, '".$user_id."', '".$_POST["rule_name"]."', '".$_POST["search_term"]."','".$_POST["nb_guid"]."')";
        //echo $sql . "<br>";


        // if successful, send the user back to the main rules page
        if ($conn->query($sql) === TRUE) {
            // echo "<script>window.location.replace(\"mgr.php?user_id=".$user_id."\");</script>";
            // echo "newly inserted rule is ". mysql_insert_id();
            $last_rule_id = $conn->insert_id;
            echo "newly inserted rule is ". $last_rule_id;

            if(strlen($_POST['tag_name_1'])>1){
                // add the new ACTION
                $sql =  "INSERT INTO Actions (id, rule_id, tag_name) VALUES " .
                    "(NULL, " .$last_rule_id .", '".$_POST["tag_name_1"]."')";
                if ($conn->query($sql) === TRUE) { }
                else {
                    $no_errors = false;
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
            if(strlen($_POST['tag_name_2'])>1){
                // add the new ACTION
                $sql =  "INSERT INTO Actions (id, rule_id, tag_name) VALUES " .
                    "(NULL, " .$last_rule_id .", '".$_POST["tag_name_2"]."')";
                if ($conn->query($sql) === TRUE) { }
                else {
                    $no_errors = false;
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
            if(strlen($_POST['tag_name_3'])>1){
                // add the new ACTION
                $sql =  "INSERT INTO Actions (id, rule_id, tag_name) VALUES " .
                    "(NULL, " .$last_rule_id .", '".$_POST["tag_name_3"]."')";
                if ($conn->query($sql) === TRUE) { }
                else {
                    $no_errors = false;
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
            $conn->close();
            echo "<script>window.location.replace(\"mgr.php?user_id=".$user_id."\");</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            $conn->close();
        }

    }

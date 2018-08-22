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

    $no_errors = true;

    if (empty($_POST["action"])){ // --------- show blank form
        ?>
        <form action="mgr_action_add.php?user_id=<?php echo $user_id; ?>" method="post">
            Tag Name: <input type="text" name="tag_name_1"><br>
            Rule ID: <input type="text" name="rule_id" value="<?php echo $_GET["rule_id"] ?>"><br>
            Action: <input type="text" name="action" value="add_action"><br>
            <input type="submit">
        </form>
        <?php
    }else{ // ----- process form and redirect to mgr.php
        echo "Rule ID: " . $_POST["rule_id"] . "<br />";
        echo "Hidden Action: " . $_POST["action"] . "<br />";

        if(strlen($_POST['tag_name_1'])>1){
            // add the new ACTION
            $sql =  "INSERT INTO Actions " .
                "(id, rule_id, tag_name) VALUES " .
                "(NULL, ".$_POST["rule_id"].
                ", '".$_POST["tag_name_1"]."')";
            if ($conn->query($sql) === TRUE) { }
            else {
                $no_errors = false;
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        if($no_errors){
            echo "<script>window.location.replace(\"mgr_rule_edit.php?rule_id=".$_POST["rule_id"]."\");</script>";
        }
        // if successful, send the user back to the main rules page
        $conn->close();
    }

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

    $no_errors = false;

    if (empty($_POST["action"])){ // --------- show blank form

        ?>
        <form action="mgr_action_add.php" method="post">
            #1 Tag Name: <input type="text" name="tag_name_1"><br>
            #1 Action Type: <input type="text" name="tag_type_1" value="tag"><br>
            <hr>
            #2 Tag Name: <input type="text" name="tag_name_2"><br>
            #2 Action Type: <input type="text" name="tag_type_2" value="tag"><br>
            <hr>
            #3 Tag Name: <input type="text" name="tag_name_3"><br>
            #3 Action Type: <input type="text" name="tag_type_3" value="tag"><br>
            <hr>
            #4 Tag Name: <input type="text" name="tag_name_4"><br>
            #4 Action Type: <input type="text" name="tag_type_4" value="tag"><br>
            <hr>
            #5 Tag Name: <input type="text" name="tag_name_5" value="EN Rules Engine"><br>
            #5 Action Type: <input type="text" name="tag_type_5" value="tag"><br>
            <hr>
            User ID: <input type="text" name="user_id" value="<?php echo $_GET["user_id"] ?>"><br>
            Rule ID: <input type="text" name="rule_id" value="<?php echo $_GET["rule_id"] ?>"><br>
            Action: <input type="text" name="action" value="add_action"><br>
            <input type="submit">
        </form>        
        <?php
        
    }else{ // ----- process form and redirect to mgr.php
        echo "User ID: " . $_POST["user_id"] . "<br />";
        echo "Rule ID: " . $_POST["rule_id"] . "<br />";
        echo "Hidden Action: " . $_POST["action"] . "<br />";
        
        if(isset($_POST['tag_name_1'])){
            // add the new ACTION
            $sql =  "INSERT INTO Actions " .
                "(id, rule_id, type, tag_name, nb_guid) VALUES " .
                "(NULL, '".$_POST["rule_id"]."', '".$_POST["tag_type_1"] .
                "', '".$_POST["tag_name_1"]."', '')";
            if ($conn->query($sql) === TRUE) { } 
            else {
                $no_errors = false;
                echo "Error: " . $sql . "<br>" . $conn->error;
            } 
            $conn->close();               
        }
        if(isset($_POST['tag_name_2'])){
            // add the new ACTION
            $sql =  "INSERT INTO Actions " .
                "(id, rule_id, type, tag_name, nb_guid) VALUES " .
                "(NULL, '".$_POST["rule_id"]."', '".$_POST["tag_type_2"] .
                "', '".$_POST["tag_name_2"]."', '')";
            if ($conn->query($sql) === TRUE) { } 
            else {
                $no_errors = false;
                echo "Error: " . $sql . "<br>" . $conn->error;
            } 
            $conn->close();              
        }
        if(isset($_POST['tag_name_3'])){
            // add the new ACTION
            $sql =  "INSERT INTO Actions " .
                "(id, rule_id, type, tag_name, nb_guid) VALUES " .
                "(NULL, '".$_POST["rule_id"]."', '".$_POST["tag_type_3"] .
                "', '".$_POST["tag_name_3"]."', '')";
            if ($conn->query($sql) === TRUE) { } 
            else {
                $no_errors = false;
                echo "Error: " . $sql . "<br>" . $conn->error;
            } 
            $conn->close();              
        }
        if(isset($_POST['tag_name_4'])){
            // add the new ACTION
            $sql =  "INSERT INTO Actions " .
                "(id, rule_id, type, tag_name, nb_guid) VALUES " .
                "(NULL, '".$_POST["rule_id"]."', '".$_POST["tag_type_4"] .
                "', '".$_POST["tag_name_4"]."', '')";
            if ($conn->query($sql) === TRUE) { } 
            else {
                $no_errors = false;
                echo "Error: " . $sql . "<br>" . $conn->error;
            } 
            $conn->close();              
        }
        if(isset($_POST['tag_name_5'])){
            // add the new ACTION
            $sql =  "INSERT INTO Actions " .
                "(id, rule_id, type, tag_name, nb_guid) VALUES " .
                "(NULL, '".$_POST["rule_id"]."', '".$_POST["tag_type_5"] .
                "', '".$_POST["tag_name_5"]."', '')";
            if ($conn->query($sql) === TRUE) { } 
            else {
                $no_errors = false;
                echo "Error: " . $sql . "<br>" . $conn->error;
            } 
            $conn->close();             
        }
        
        if($no_errors){
            echo "<script>window.location.replace(\"mgr.php?user_id=".$user_id."\");</script>";
        }
        // if successful, send the user back to the main rules page

    }   

    


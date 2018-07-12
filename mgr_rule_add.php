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

    //$user_id = "1"; // sandbox
    //$user_id = "2"; // production
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
            Search Term: <input type="text" name="search_term"><br>
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
            "(id, user_id, title, search_term) VALUES " . 
            "(NULL, '".$user_id."', '".$_POST["rule_name"]."', '".$_POST["search_term"]."')";
        
        // if successful, send the user back to the main rules page
        if ($conn->query($sql) === TRUE) {
            echo "<script>window.location.replace(\"mgr.php?user_id=".$user_id."\");</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }   
        $conn->close();
    }   


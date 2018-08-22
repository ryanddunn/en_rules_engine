<?php
    // Import the classes that we're going to be using
    use EDAM\Types\Data, EDAM\Types\Note, EDAM\Types\Resource, EDAM\Types\ResourceAttributes;
    use EDAM\Error\EDAMUserException, EDAM\Error\EDAMErrorCode;
    use Evernote\Client;

    include 'config.php';
    ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . "lib" . PATH_SEPARATOR);
    require_once $app_dir.'lib/autoload.php';
    require_once $app_dir.'lib/Evernote/Client.php';
    require_once $app_dir.'lib/packages/Errors/Errors_types.php';
    require_once $app_dir.'lib/packages/Types/Types_types.php';
    require_once $app_dir.'lib/packages/Limits/Limits_constants.php';

    function buildTagList($rule_id, $servername, $username, $password, $dbname)
    {
        $tag_array = array();
        $conn_a = new mysqli($servername, $username, $password, $dbname);
        if ($conn_a->connect_error) {die("Connection failed: " . $conn_a->connect_error); }
        $result = $conn_a->query("SELECT tag_name FROM Actions where rule_id=" . $rule_id);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($tag_array, $row["tag_name"]);
            }
        }
        $conn_a->close();
        return $tag_array;
    }

    function getNewNotebook($rule_id, $servername, $username, $password, $dbname)
    {
        $new_nb_guid = "";
        $conn_a = new mysqli($servername, $username, $password, $dbname);
        if ($conn_a->connect_error) {die("Connection failed: " . $conn_a->connect_error); }
        $result = $conn_a->query("SELECT nb_guid FROM Rules where id=" . $rule_id);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $new_nb_guid = $row["nb_guid"];
            }
        }
        $conn_a->close();
        // echo "\n -- getNewNotebook ran -- new_nb_guid='".$new_nb_guid."' \n";
        return $new_nb_guid;
    }

    // A global exception handler for our program so that error messages all go to the console
    function en_exception_handler($exception)
    {
        echo "Uncaught " . get_class($exception) . ":\n";
        if ($exception instanceof EDAMUserException) {
            echo "Error code: " . EDAMErrorCode::$__names[$exception->errorCode] . "\n";
            echo "Parameter: " . $exception->parameter . "\n";
        } elseif ($exception instanceof EDAMSystemException) {
            echo "Error code: " . EDAMErrorCode::$__names[$exception->errorCode] . "\n";
            echo "Message: " . $exception->message . "\n";
        } else {
            echo $exception;
        }
    }
    set_exception_handler('en_exception_handler');


    // DUPLICATED?!?! SEE BELOW ...
    // ======================================================================================
    // ======================================================================================
    if($dev_mode){
        $client = new Client(array('token' => $authToken, 'sandbox' => $dev_mode));
    }else{
        $client = new Client(array('token' => $authToken, 'sandbox' => $dev_mode));
    }
    // ======================================================================================
    // ======================================================================================

    $userStore = $client->getUserStore();

    // Connect to the service and check the protocol version
    $versionOK =
        $userStore->checkVersion("Evernote EDAMTest (PHP)",
             $GLOBALS['EDAM_UserStore_UserStore_CONSTANTS']['EDAM_VERSION_MAJOR'],
             $GLOBALS['EDAM_UserStore_UserStore_CONSTANTS']['EDAM_VERSION_MINOR']);
    //print "Is my Evernote API version up to date?  " . $versionOK . "\n\n";
    if ($versionOK == 0) { exit(1); }

    $noteStore = $client->getNoteStore(); // CHECK ME - is this line really needed??
    // ===============================================
    if($dev_mode){ echo "*** DEV MODE *** \n"; }

    use EDAM\NoteStore\NoteFilter;
    $client = new Client(array('token' => $authToken,'sandbox' => $dev_mode));
    $filter = new NoteFilter();

    // create an entry in the "logs" table for all actions to tracked from this point going forward
    $sql = "INSERT INTO Logs () VALUES ()"; // just make a timestamped entry in table
    if ($conn->query($sql) === TRUE) {
        $log_id = $conn->insert_id;
    }

    // create a second instance of the connection to DB to support
    // similateous transactions for log detail insertion
    $conn2 = $conn;
    $total_notes_updated = 0;

    // pull from DB for each rule
    $rules_result = $conn->query("SELECT * FROM Rules where user_id=" . $user_id);
    if ($rules_result->num_rows > 0) {
        while($row = $rules_result->fetch_assoc()) {
            // build the list of tags to apply to the to the note when the search term is found
            $tag_array = buildTagList($row["id"], $servername, $username, $password, $dbname);
            $filter->words = "\"" . $row["search_term"] . "\"";
            $filter->notebookGuid =  $notebook_working;
            $notes_result = $client->getNoteStore()->findNotes($filter, 0, 10);
            $notes = $notes_result->notes;
            $counter = 0;

            foreach ($notes as $note) {
                if($counter == 0)
                {
                	print count($notes_result->notes) . " notes with term " .
                        $row["search_term"] . "] \n";
                }
		        $counter ++; $total_notes_updated ++;

                //if a new NB is selected via action, use it, otherwise use default
                $new_nb_guid = getNewNotebook($row["id"], $servername, $username, $password, $dbname);
                if(strlen($new_nb_guid)<1){
                    $new_nb_guid = $notebook_working;
                }

                echo "... Note: " . $note->title . " [" . $note->guid . "]" . "\n";
                $updated_note = new Note();
                $updated_note->notebookGuid = $new_nb_guid; // required field
                $updated_note->guid = $note->guid; // required field
                $updated_note->title = $note->title; // required field
                $updated_note->tagGuids = $note->tagGuids; // keep the same tags in place

                // create a list of tags to be applied from the rule in the db
                $updated_note->tagNames = $new_tags =  $tag_array; // add new tags via string
                $returnedNote = $noteStore->updateNote($updated_note);
                //$conn2 = $conn;
                // create entry in "logs_detail" table for each note / rule execution
                $sql = "INSERT INTO Logs_Detail ( log_id, note_guid, note_title, rule_id) VALUES " .
                    "(" . $log_id . ",'" . $note->guid . "','" . substr($note->title,0,99) . "',".$row["id"].")";
                $conn2->query($sql);

                // catch the output so it stay's silent
                //$output = shell_exec("curl -X POST --data-urlencode 'payload={\"channel\": \"#notifications\", \"username\": \"webhookbot\", \"text\": \"".$slack_string."\", \"icon_emoji\": \":robot_face:\"}' https://hooks.slack.com/services/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");
            }
        }

        // delete the log entry if no work was done.
        if($total_notes_updated == 0){
            $sql = "DELETE FROM Logs WHERE id=" . $log_id;
            $conn->query($sql);
        }
    }
    $conn->close();
    echo "\n";

    //$slack_string = "EN Rules Engine";
    // catch the output so it stay's silent
    //$output = shell_exec("curl -X POST --data-urlencode 'payload={\"channel\": \"#notifications\", \"username\": \"webhookbot\", \"text\": \"".$slack_string."\", \"icon_emoji\": \":robot_face:\"}' https://hooks.slack.com/services/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 &");

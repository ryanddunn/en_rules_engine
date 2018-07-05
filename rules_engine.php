<?php

    // Import the classes that we're going to be using
    use EDAM\Types\Data, EDAM\Types\Note, EDAM\Types\Resource, EDAM\Types\ResourceAttributes;
    use EDAM\Error\EDAMUserException, EDAM\Error\EDAMErrorCode;
    use Evernote\Client;

    ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . "lib" . PATH_SEPARATOR);
    require_once 'autoload.php';
    require_once 'Evernote/Client.php';
    require_once 'packages/Errors/Errors_types.php';
    require_once 'packages/Types/Types_types.php';
    require_once 'packages/Limits/Limits_constants.php';
    include 'config.php';


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
    if ($versionOK == 0) {
        exit(1);
    }


/**

$result = $conn->query("SELECT * FROM Users where id=" . $user_id);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        //echo "id: " . $row["id"]. " - Name: " . $row["name"]. "\n";
    }
} 

*/



    $noteStore = $client->getNoteStore();

    // ===============================================
    if($dev_mode){ echo "*** DEV MODE *** \n"; }

    use EDAM\NoteStore\NoteFilter;
    $client = new Client(array('token' => $authToken,'sandbox' => $dev_mode));
    $filter = new NoteFilter();

    // pull form DB for each rule
    $filter->words = "dunn.ryan@gmail.com";
    $filter->notebookGuid =  "8968810a-f5e7-4aa8-9c7a-14bfe1beda0f"; // bound to discord notebook, sandbox
    $notes_result = $client->getNoteStore()->findNotes($filter, 0, 10);
    print "Search Results: " . count($notes_result->notes) . "\n";
    $notes = $notes_result->notes;
    foreach ($notes as $note) {
        //print_r($note);
        echo "Note: " . $note->title . " [" . $note->guid . "]" . "\n";
        $updated_note = new Note();
        $updated_note->guid = $note->guid; // required field
        $updated_note->title = $note->title." (.)"; // required field
        $updated_note->tagGuids = $note->tagGuids; // keep the same tags in place
        
        // create a list of tags to be applied from the rule in the db
        $updated_note->tagNames = $new_tags = array("Ryan Dunn","Automagic"); // add new tags via string
        $returnedNote = $noteStore->updateNote($updated_note);
        //print "update note with GUID: " . $returnedNote->guid . "\n";
    }
echo "\n";

$conn->close(); 
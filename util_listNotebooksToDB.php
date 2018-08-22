<?php
    include 'config.php';

    // =========================================
    // =========================================
    $user_id = "2"; // manually set the USER ID
    // =========================================
    // =========================================

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
    $client = new Client(array('token' => $authToken, 'sandbox' => $dev_mode));
    if($dev_mode){ echo "*** SANDBOX MODE *** \n"; }
    $noteStore = $client->getNoteStore();
    $notebooks = $noteStore->listNotebooks();

    // clear out all data for the user being sync'ed
    $sql = "DELETE FROM Notebooks WHERE user_id=" . $user_id;
    $conn->query($sql);

    foreach ($notebooks as $notebook) {
        echo $notebook->name . "[" . $notebook->guid . "]\n .";

        $sql = "INSERT INTO `Notebooks` (`user_id`, `nb_guid`, `label`, `last_updated`) " .
        "VALUES ('".$user_id."', '".$notebook->guid."', '".$notebook->name."', CURRENT_TIMESTAMP)";
        if (!$conn->query($sql) === TRUE) {
            echo "\n\nERROR: " . $sql;
        }
    }
    $conn->close();
    echo "\n\nTag List completed, written to dump_notebook_list.txt.\n";
    echo "\n";
?>

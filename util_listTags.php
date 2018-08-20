<?php
    // This file will output a physical file containing a list of all Tags and related metadata
    // such as GUID for reference.

    // Import the classes that we're going to be using
    use EDAM\Types\Data, EDAM\Types\Note, EDAM\Types\Resource, EDAM\Types\ResourceAttributes;
    use EDAM\Error\EDAMUserException, EDAM\Error\EDAMErrorCode;
    use Evernote\Client;

    //ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . "../../lib" . PATH_SEPARATOR);
    ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . "lib" . PATH_SEPARATOR);
    require_once 'autoload.php';
    require_once 'Evernote/Client.php';
    require_once 'packages/Errors/Errors_types.php';
    require_once 'packages/Types/Types_types.php';
    require_once 'packages/Limits/Limits_constants.php';
    require_once 'cliColors/cli_Colors.php';
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
    if($dev_mode){ echo "*** DEV MODE *** \n"; }

    $myfile = fopen("dump_tag_list.txt", "w") or die("Unable to open file!");
    if($dev_mode){ fwrite($myfile, "*** SANDBOX *** \n"); }
    $current_time = date("Y-m-d") . " - " . date("h:i:sa") . "\n";
    fwrite($myfile, $current_time);
    $userStore = $client->getUserStore();
    $tag_dump = print_r($client->getNoteStore()->listTags(), true);
    fwrite($myfile, $tag_dump);
    fclose($myfile);

    echo "Tag List completed, written to dump_tag_list.txt.\n";

    echo "\n";
?>

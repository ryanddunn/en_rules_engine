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

$noteStore = $client->getNoteStore();

// ===============================================
    if($dev_mode){ echo "*** DEV MODE *** \n"; }


    use EDAM\NoteStore\NoteFilter;
    //use Evernote\Client;
    $client = new Client(array(
     'token' => $authToken,
     'sandbox' => $dev_mode
    ));
    $filter = new NoteFilter();

    $filter->words = "HIGH";
    $filter->tagGuids =  $actionitem_tag_guid;
    $notes_result = $client->getNoteStore()->findNotes($filter, 0, 10);
    //    print "Search Results: " . count($notes_result->notes) . "  notes that match the search!";
    $notes = $notes_result->notes;

    // currently finds a note based on existing search string and tag,
    // then adds new tags while keeping any existing tags
    foreach ($notes as $note) {
        echo $note->title . "\n";
        $updated_note = new Note();
        $updated_note->guid = $note->guid; // required field
        $updated_note->title = $note->title." (.)"; // required field
        $updated_note->tagGuids = $note->tagGuids; // keep the same tags in place
        $updated_note->tagNames = $new_tags = array("Tag 3", "Tag 4"); // add new tags via string
        $createdNote = $noteStore->updateNote($updated_note);
        print "update note with GUID: " . $createdNote->guid . "\n";
    }
echo "\n";

// ===============================================

/*
                print"\nCreating a new note in the default notebook\n\n";

                // To create a new note, simply create a new Note object and fill in
                // attributes such as the note's title.
                $note = new Note();
                $note->title = "Test note from EDAMTest.php";

                // To include an attachment such as an image in a note, first create a Resource
                // for the attachment. At a minimum, the Resource contains the binary attachment
                // data, an MD5 hash of the binary data, and the attachment MIME type. It can also
                // include attributes such as filename and location.
                $filename = "enlogo.png";
                $image = fread(fopen($filename, "rb"), filesize($filename));
                $hash = md5($image, 1);

                $data = new Data();
                $data->size = strlen($image);
                $data->bodyHash = $hash;
                $data->body = $image;

                $resource = new Resource();
                $resource->mime = "image/png";
                $resource->data = $data;
                $resource->attributes = new ResourceAttributes();
                $resource->attributes->fileName = $filename;

                // Now, add the new Resource to the note's list of resources
                $note->resources = array( $resource );

                // To display the Resource as part of the note's content, include an <en-media>
                // tag in the note's ENML content. The en-media tag identifies the corresponding
                // Resource using the MD5 hash.
                $hashHex = md5($image, 0);

                // The content of an Evernote note is represented using Evernote Markup Language
                // (ENML). The full ENML specification can be found in the Evernote API Overview
                // at http://dev.evernote.com/documentation/cloud/chapters/ENML.php
                $note->content =
                    '<?xml version="1.0" encoding="UTF-8"?>' .
                    '<!DOCTYPE en-note SYSTEM "http://xml.evernote.com/pub/enml2.dtd">' .
                    '<en-note>Here is the Evernote logo:<br/>' .
                    '<en-media type="image/png" hash="' . $hashHex . '"/>' .
                    '</en-note>';

                // When note titles are user-generated, it's important to validate them
                $len = strlen($note->title);
                $min = $GLOBALS['EDAM_Limits_Limits_CONSTANTS']['EDAM_NOTE_TITLE_LEN_MIN'];
                $max = $GLOBALS['EDAM_Limits_Limits_CONSTANTS']['EDAM_NOTE_TITLE_LEN_MAX'];
                $pattern = '#' . $GLOBALS['EDAM_Limits_Limits_CONSTANTS']['EDAM_NOTE_TITLE_REGEX'] . '#'; // Add PCRE delimiters
                if ($len < $min || $len > $max || !preg_match($pattern, $note->title)) {
                    print "\nInvalid note title: " . $note->title . '\n\n';
                    exit(1);
                }

                // Finally, send the new note to Evernote using the createNote method
                // The new Note object that is returned will contain server-generated
                // attributes such as the new note's unique GUID.
                $createdNote = $noteStore->createNote($note);

                print "Successfully created a new note with GUID: " . $createdNote->guid . "\n";
*/

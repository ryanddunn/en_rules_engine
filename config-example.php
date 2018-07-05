<?php
// rename this file to "config.php" and update the "$authToken" & "$actionitem_tag_guid" 
// configure the application 

// toggle this variable to move between "Sandbox" and "Live"
$dev_mode = false;

if($dev_mode){
    // place the auth token from your sandbox acccount here
    $authToken = "...";

    // "Action Item" tag GUID as it relates to your sandbox account here
    $actionitem_tag_guid = array("...");
}else{
    // place the auth token from your live acccount here
    $authToken = "...";

    // "Action Item" tag GUID as it relates to your live account here
    $actionitem_tag_guid = array("42cdbc60-d081-45b5-960e-1c7d0f78b066");
}
?>

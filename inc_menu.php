<?php

echo "<h2> User: " . $user_id . "</h2>";
echo "Menu: ";
echo "<a href=\"mgr.php?user_id=2\">Main</a> |  ";
echo "<a href=\"log_general.php?user_id=" . $_GET["user_id"] . "\">Logs</a> |  ";
echo "<a href=\"mgr_rule_add.php?user_id=".$user_id."\">Add Rule</a> ";
echo "<hr>";

?>

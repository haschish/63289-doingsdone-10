<?php
$dbLink = mysqli_connect("127.0.0.1", "root", "root", "63289_doingsdone_10", 8889);
mysqli_set_charset($dbLink, 'utf8');

if (!$dbLink) {
    printErrorAndExit(mysqli_connect_error());
}
?>

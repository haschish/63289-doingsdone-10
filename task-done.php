<?php
require_once('./init.php');
require_once('./helpers.php');
require_once('./functions.php');
require_once('./db-init.php');

$user = $_SESSION['user'];
if (!$user) {
    redirect('index.php');
}

if (empty($_GET['id']) || empty($_GET['done']) || !is_numeric($_GET['id'])) {
    redirect('index.php');
}

$done = $_GET['done'] == 'true' ? 1 : 0;
$id = intval($_GET['id']);
$sql = "UPDATE tasks SET done = $done WHERE id = $id";
$result = mysqli_query($dbLink, $sql);

redirect('index.php');
?>

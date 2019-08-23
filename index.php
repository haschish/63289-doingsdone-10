<?php
require_once('./helpers.php');
require_once('./functions.php');
require_once('./db-init.php');
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

if (!$dbLink) {
    $content = include_template('error.php', ['message' => mysqli_connect_error()]);
} else {
    $projects = getProjects($dbLink, 1);
    $tasks = getTasks($dbLink, 1);
    $content = include_template('main.php', ['tasks' => $tasks, 'projects' => $projects, 'show_complete_tasks' => $show_complete_tasks]);
}

$result = include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $content]);
print($result);
?>

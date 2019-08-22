<?php
require_once('./helpers.php');
require_once('./functions.php');
require_once('./db-init.php');
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$content = '';

if (!$dbLink) {
    $content = include_template('error.php', ['message' => mysqli_connect_error()]);
} else {
    //получить категории
    $sql = "
        SELECT p.id, p.name, IF (pc.count IS NULL, 0, pc.count) AS count FROM projects AS p
        LEFT JOIN (SELECT count(id) AS `count`, project_id FROM tasks WHERE user_id = 1 GROUP BY project_id) AS pc ON (p.id = pc.project_id)
        WHERE p.user_id = 1;
    ";
    $result = mysqli_query($dbLink, $sql);
    if (!$result) {
        $content = include_template('error.php', ['message' => mysqli_error($dbLink)]);
    }

    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //получить задачи
    $sql = "SELECT * FROM tasks WHERE user_id = 1;";
    $result = mysqli_query($dbLink, $sql);
    if (!$result) {
        $content = include_template('error.php', ['message' => mysqli_error()]);
    }

    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (!$content) {
        $content = include_template('main.php', ['tasks' => $tasks, 'projects' => $projects, 'show_complete_tasks' => $show_complete_tasks]);
    }
}


$result = include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $content]);
print($result);
?>

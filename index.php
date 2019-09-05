<?php
require_once('./init.php');
require_once('./helpers.php');
require_once('./functions.php');
require_once('./db-init.php');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

if (!$dbLink) {
    $content = include_template('error.php', ['message' => mysqli_connect_error()]);
} else {
    $projects = getProjects($dbLink, 1);
    $category_id = (isset($_GET['category'])) ? (int)$_GET['category'] : null;

    if ($category_id && !hasCategory($projects, $category_id)) {
        header("HTTP/1.x 404 Not Found");
        exit();
    }

    $tasks = getTasks($dbLink, 1, $category_id);
    $categories_side = include_template('categories-side.php', ['projects' => $projects, 'category_id' => $category_id]);
    $content = include_template('main.php', ['tasks' => $tasks, 'categories_side' => $categories_side, 'show_complete_tasks' => $show_complete_tasks]);
}

$result = include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $content]);
print($result);
?>

<?php
require_once('./init.php');
require_once('./helpers.php');
require_once('./functions.php');
require_once('./db-init.php');

// показывать или нет выполненные задачи
$show_completed = getShowCompleted();
$_SESSION['show_completed'] = $show_completed;
$user = getSessionValue('user');
$content = '';

if ($user) {
    $projects = getProjects($dbLink, $user['id']);
    $category_id = (isset($_GET['category'])) ? (int)$_GET['category'] : null;

    if ($category_id && !hasCategory($projects, $category_id)) {
        header("HTTP/1.x 404 Not Found");
        exit();
    }

    $tasks = getTasks($dbLink, $user['id'], $category_id, getFilterValue(), getGetValue('search'));
    $categories_side = include_template('categories-side.php', ['projects' => $projects, 'category_id' => $category_id]);
    $content = include_template('main.php', ['tasks' => $tasks, 'categories_side' => $categories_side, 'show_complete_tasks' => $show_completed]);
} else {
    $content = include_template('guest.php');
}

printLayoutAndExit($content);

?>

<?php
require_once('./init.php');
require_once('./helpers.php');
require_once('./functions.php');
require_once('./db-init.php');

$user = getSessionValue('user');
if (!$user) {
    redirect('index.php');
}

$projects = getProjects($dbLink, $user['id']);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $projects_names = array_column($projects, 'name');

    $rules = [
        'name' => function() use ($projects_names) {
            return validateFilled('name')
                ?? validateNotExist(mb_strtolower(getPostValue('name')), $projects_names);
        }
    ];

    foreach ($rules as $key => $rule) {
        $errors[$key] = $rule();
    }
    $errors = array_filter($errors);

    if (count($errors) == 0) {
        insertProject($dbLink, [
            'user_id' => $user['id'],
            'name' => mb_strtolower(getPostValue('name'))
        ]);
        redirect('index.php');
	}
}

$categories_side = include_template('categories-side.php', ['projects' => $projects, 'category_id' => null]);
$content = include_template('form-project.php', ['categories_side' => $categories_side, 'projects' => $projects, 'errors' => $errors]);
printLayoutAndExit($content);
?>

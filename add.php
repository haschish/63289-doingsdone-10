<?php
require_once('./init.php');
require_once('./helpers.php');
require_once('./functions.php');
require_once('./db-init.php');

$user = $_SESSION['user'];
if (!$user) {
    redirect('index.php');
}

$projects = getProjects($dbLink, $user['id']);
$categories_side = include_template('categories-side.php', ['projects' => $projects, 'category_id' => null]);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $projects_ids = array_column($projects, 'id');

    $rules = [
        'name' => function () {
            return validateFilled('name');
        },
        'project' => function () use ($projects_ids) {
            return implode('; ', array_filter([
                validateFilled('project'),
                validateCategory('project', $projects_ids)
            ]));
        },
        'date' => function () {
            if (empty(getPostValue('date'))) {
                return null;
            }
            return validateDate('date');
        }
    ];

    foreach ($rules as $key => $rule) {
        $errors[$key] = $rule();
    }
    $errors = array_filter($errors);

    if (count($errors) == 0) {
        $filename = null;
        if (!empty($_FILES['file']['name'])) {
            $tmp_name = $_FILES['file']['tmp_name'];
            $nameArr = explode('.', $_FILES['file']['name']);
            $extension = $nameArr[count($nameArr) - 1];
            $filename = uniqid() . '.' . $extension;
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
        }

        insertTask($dbLink, [
            'user_id' => $user['id'],
            'project_id' => getPostValue('project'),
            'name' => getPostValue('name'),
            'file' => $filename,
            'date' => $_POST['date'] ?: null
        ]);
        redirect('index.php');
	}
}

$content = include_template('form-task.php', ['categories_side' => $categories_side, 'projects' => $projects, 'errors' => $errors]);
printLayoutAndExit($content);
?>

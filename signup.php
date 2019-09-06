<?php
require_once('./init.php');
require_once('./helpers.php');
require_once('./functions.php');
require_once('./db-init.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $rules = [
        'email' => function() use ($dbLink) {
            $error = validateEmail('email');
            if ($error) {
                return $error;
            }
            $user = findUserByEmail($dbLink, $_POST['email']);
            if ($user) {
                return 'Указанный email уже используется другим пользователем';
            }
        },
        'password' => function() {
            return validateFilled('password');
        },
        'name' => function() {
            return validateFilled('name');
        }
    ];

    foreach ($rules as $key => $rule) {
        $errors[$key] = $rule();
    }
    $errors = array_filter($errors);

    if (count($errors) == 0) {
        insertUser($dbLink, [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
        ]);
        redirect('index.php');
	}
}

$content = include_template('register.php', ['errors' => $errors]);
printLayoutAndExit($content);
?>

<?php
require_once('./init.php');
require_once('./helpers.php');
require_once('./functions.php');
require_once('./db-init.php');

if ($_SESSION['user']) {
    redirect('index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rules = [
        'email' => function() {
            return validateEmail('email');
        },
        'password' => function() {
            return validateFilled('password');
        }
    ];

    foreach ($rules as $key => $rule) {
        $errors[$key] = $rule();
    }

    $errors = array_filter($errors);
    if (count($errors) == 0) {
        $user = findUserByEmail($dbLink, $_POST['email']);
        if (!$user) {
            $errors['email'] = 'Указанный email не найден';
            $errors['account'] = true;
        } else if (!password_verify(getPostValue('password'), $user['password'])) {
            $errors['password'] = 'Пароль неверен';
            $errors['account'] = true;
        } else {
            $_SESSION['user'] = $user;
            redirect('index.php');
        }
    }
}
$content = include_template('form-authorization.php', ['errors' => $errors]);
printLayoutAndExit($content);
?>

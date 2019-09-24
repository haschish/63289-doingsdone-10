<?php
require_once('./init.php');
require_once('./helpers.php');
require_once('./functions.php');
require_once('./db-init.php');
require_once('./vendor/autoload.php');

$sql = "
    SELECT t.user_id, t.name, DATE_FORMAT(t.date, '%d.%m.%Y') AS date, u.name AS user_name, u.email AS user_email
    FROM tasks AS t
    LEFT JOIN users AS u ON u.id = t.user_id
    WHERE t.done = 0 AND t.date = CURDATE();
";
$result = mysqli_query($dbLink, $sql);
$tasks = ($result) ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];

$transport  = (new Swift_SmtpTransport('phpdemo.ru', 25))
    ->setUsername('keks@phpdemo.ru')
    ->setPassword('htmlacademy');

$mailer = new Swift_Mailer($transport);

$message = (new Swift_Message('Уведомление от сервиса «Дела в порядке»'))
    ->setFrom('keks@phpdemo.ru');

$tasksGroupByUser = groupBy($tasks, 'user_id');

foreach($tasksGroupByUser as $userTasks) {
    $user_name = $userTasks[0]['user_name'];
    $date = $userTasks[0]['date'];
    $email = $userTasks[0]['user_email'];
    $body = include_template('notify-text.php', ['user' => $user_name, 'date' => $date, 'tasks' => $userTasks]);

    $message->setTo($email);
    $message->setBody($body);
    $result = $mailer->send($message);
}
?>

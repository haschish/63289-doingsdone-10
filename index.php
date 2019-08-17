<?php
require_once('./helpers.php');
require_once('./functions.php');
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
$tasks = [
    ['name' => 'Собеседование в IT компании', 'date' => '01.12.2019', 'category' => 'Работа', 'done' => false],
    ['name' => 'Выполнить тестовое задание', 'date' => '25.12.2019', 'category' => 'Работа', 'done' => false],
    ['name' => 'Сделать задание первого раздела', 'date' => '21.12.2019', 'category' => 'Учеба', 'done' => true],
    ['name' => 'Встреча с другом', 'date' => '22.12.2019', 'category' => 'Входящие', 'done' => false],
    ['name' => 'Купить корм для кота', 'date' => null, 'category' => 'Домашние дела', 'done' => false],
    ['name' => 'Заказать пиццу', 'date' => null, 'category' => 'Домашние дела', 'done' => false],
];

$content = include_template('main.php', ['tasks' => $tasks, 'projects' => $projects, 'show_complete_tasks' => $show_complete_tasks]);
$result = include_template('layout.php', ['title' => 'Дела в порядке', 'content' => $content]);
print($result);
?>

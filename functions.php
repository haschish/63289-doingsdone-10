<?php
/**
 * Считает количество задач определенной категории
 *
 * @param array $tasks массив задач
 * @param string $category название категории
 *
 * @return int количество найденых задач
 */
function countCategory($tasks, $category) {
    $count = 0;
    foreach($tasks as $task) {
        if ($task['category'] == $category) {
            $count++;
        }
    }
    return $count;
};
?>

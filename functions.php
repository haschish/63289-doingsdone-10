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

/**
 * Проверяет, что переданная дата меньше текущего системного времени на 24 часа
 *
 * @param string $date дата в формате 'ДД.ММ.ГГГГ'
 *
 * @return boolean true если от $date до текущего системного времени осталось меньше чем 24 часа, иначе false
 */
function isLessThan24HoursLeft(string $date) {
    $timestamp = strtotime($date);
    return $timestamp - time() <= 24 * 60 * 60;
};
?>

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

/**
 * Возвращает массив найденых категорий
 *
 * @param mysqli $link объект представляющий подключение к серверу MySQL
 * @param int $user_id идентификатор пользователя
 *
 * @return array $projects массив найденых категорий
 */
function getProjects(mysqli $link, int $user_id) {
    $sql = "
        SELECT p.id, p.name, IF (pc.count IS NULL, 0, pc.count) AS count FROM projects AS p
        LEFT JOIN (SELECT count(id) AS `count`, project_id FROM tasks WHERE user_id = $user_id GROUP BY project_id) AS pc ON (p.id = pc.project_id)
        WHERE p.user_id = $user_id;
    ";
    $result = mysqli_query($link, $sql);
    return ($result) ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
};

/**
 * Возвращает массив найденых задач
 *
 * @param mysqli $link объект представляющий подключение к серверу MySQL
 * @param int $user_id идентификатор пользователя
 *
 * @return array $tasks массив найденых задач
 */
function getTasks(mysqli $link, int $user_id) {
    $sql = "SELECT * FROM tasks WHERE user_id = $user_id;";
    $result = mysqli_query($link, $sql);
    return ($result) ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
};
?>

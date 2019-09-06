<?php
/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function getPrepareStmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}

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
 * проверяет, существует ли в списке категория с идентификатором $id
 *
 * @param array $categories массив категорий
 * @param string $id идентификатор категории
 *
 * @return boolean
 */
function hasCategory($categories, $id) {
    return in_array($id, array_column($categories, 'id'));
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
function getTasks(mysqli $link, int $user_id, int $category_id = null) {
    $categoryCondition = ($category_id) ? "project_id = $category_id" : "1=1";
    $sql = "SELECT * FROM tasks WHERE user_id = $user_id AND $categoryCondition;";
    $result = mysqli_query($link, $sql);
    return ($result) ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
};

/**
 * Вставляет задачу в базу
 *
 * @param mysqli $link объект представляющий подключение к серверу MySQL
 * @param array $task задача
 *
 * @return $id идентификатор вставленной задачи
 */
function insertTask(mysqli $link, array $task) {
    $sql = 'INSERT INTO tasks (user_id, project_id, name, file, date) VALUES (?, ?, ?, ?, ?)';
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'sssss', $task['user_id'], $task['project_id'], $task['name'], $task['file'], $task['date']);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_errno($stmt)) {
        printErrorAndExit(mysqli_stmt_error($stmt));
    }

    return mysqli_stmt_insert_id($stmt);
};

function findUserByEmail(mysqli $link, string $email) {
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        printErrorAndExit(mysqli_error($link));
    }

    return mysqli_fetch_assoc($result);
}

function insertUser(mysqli $link, array $user) {
    $sql = 'INSERT INTO users (name, email, password) VALUES (?, ?, ?)';
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $user['name'], $user['email'], $user['password']);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_errno($stmt)) {
        printErrorAndExit(mysqli_stmt_error($stmt));
    }

    return mysqli_stmt_insert_id($stmt);
};

/**
 * Возвращает значение $name из массива $_POST
 *
 * @param string $name
 *
 * @return string $value значение из массива $_POST, либо пустая строка
 */
function getPostValue($name = null) {
    return $_POST[$name] ?? '';
};

function printErrorAndExit(string $message = '') {
    $content = include_template('error.php', ['message' => $message]);
    printLayoutAndExit($content);
}

function printLayoutAndExit(string $content = '', string $title = 'Дела в порядке') {
    $result = include_template('layout.php', ['title' => $title, 'content' => $content]);
    print($result);
    exit;
}

function redirect(string $url = 'index.php') {
    header("Location: $url");
    exit;
}

function validateFilled($name) {
    if (empty($_POST[$name])) {
        return "Это поле должно быть заполнено";
    }

    return null;
}

function validateCategory($name, $allowed_list) {
    if (!in_array($_POST[$name], $allowed_list)) {
        return "Указана несуществующая категория";
    }

    return null;
}

function validateDate($name) {
    if (!preg_match("/^\d{4}\-\d{2}-\d{2}$/", $_POST[$name])) {
        return "Это поле должно быть датой в формате «ГГГГ-ММ-ДД»";
    } else if (strtotime($_POST['date']) < strtotime('today')) {
        return "Дата должна быть больше или равна текущей";
    }

    return null;
}

function validateEmail($name) {
    if (!filter_var($_POST[$name], FILTER_VALIDATE_EMAIL)) {
        return 'E-mail введён некорректно';
    }
    return null;
}

?>

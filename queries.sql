USE 63289_doingsdone_10;

INSERT INTO users (id, name, email, password) VALUES
  (1, 'test', 'test@test.com', 'pass'),
  (2, 'test2', 'test2@test.com', 'pass');

INSERT INTO projects (id, user_id, name) VALUES
  (1, 1, 'Входящие'),
  (2, 1, 'Учеба'),
  (3, 1, 'Работа'),
  (4, 1, 'Домашние дела'),
  (5, 1, 'Авто');

INSERT INTO tasks (id, user_id, project_id, name, file, date, done) VALUES
  (1, 1, 3, 'Собеседование в IT компании', NULL, '2019-08-18', 0),
  (2, 1, 3, 'Выполнить тестовое задание', NULL, '2019-12-25', 0),
  (3, 1, 2, 'Сделать задание первого раздела', NULL, '2019-07-21', 1),
  (4, 1, 1, 'Встреча с другом', NULL, '2019-12-22', 0),
  (5, 1, 4, 'Купить корм для кота', NULL, NULL, 0),
  (6, 1, 4, 'Заказать пиццу', NULL, NULL, 0);

# получить список из всех проектов для одного пользователя;
SELECT * FROM projects WHERE user_id = 1;

# получить список из всех задач для одного проекта;
SELECT * FROM tasks WHERE project_id = 3;

# пометить задачу как выполненную;
UPDATE tasks SET done = 1 WHERE id = 1;

# обновить название задачи по её идентификатору
UPDATE tasks SET name = 'Собеседование в компании Apple' WHERE id = 1;

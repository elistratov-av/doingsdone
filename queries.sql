INSERT INTO users
	(email, name, password)
VALUES
	('konstantin@yandex.ru', 'Константин', '$2y$10$EkvXAcJOfg9ZBIffu6CLdOj07HfraxW2EhW.6Faj.9L4zgUyhjbZC');

INSERT INTO projects
	(name, user_id)
VALUES
	('Входящие', 1),
	('Учеба', 1),
	('Работа', 1),
	('Домашние дела', 1),
	('Авто', 1);

INSERT INTO tasks
	(name, date_end, completed, project_id, user_id, date_creation)
VALUES
	('Собеседование в IT компании', '2025-03-01', 0, (SELECT id FROM projects WHERE name = 'Работа'), 1, '2025-02-01'),
	('Выполнить тестовое задание', '2025-03-12', 0, (SELECT id FROM projects WHERE name = 'Работа'), 1, '2025-02-01'),
	('Сделать задание первого раздела', '2025-03-21', 1, (SELECT id FROM projects WHERE name = 'Учеба'), 1, '2025-02-01'),
	('Встреча с другом', '2025-03-22', 0, (SELECT id FROM projects WHERE name = 'Входящие'), 1, '2025-02-01'),
	('Купить корм для кота', NULL, 0, (SELECT id FROM projects WHERE name = 'Домашние дела'), 1, '2025-02-01'),
	('Заказать пиццу', NULL, 0, (SELECT id FROM projects WHERE name = 'Домашние дела'), 1, '2025-02-01');

-- получить список из всех проектов для одного пользователя
SELECT id, name
FROM projects p 
WHERE user_id = 1;

-- получить список из всех задач для одного проекта
SELECT id, name, file, completed, date_end
FROM tasks t 
WHERE project_id = 3;

-- пометить задачу как выполненную
UPDATE tasks 
SET completed = 1
WHERE id = 3;

-- обновить название задачи по её идентификатору
UPDATE tasks 
SET name = 'Заказать пиццу'
WHERE id = 6;

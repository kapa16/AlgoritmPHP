-- Создаём структуру, дерева, какой оно является в режиме AL
DROP TABLE IF EXISTS tree;

CREATE TABLE tree (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    parent_id INT NULL,
    level INT(10) NOT NULL DEFAULT 0
);

INSERT INTO tree (id, title, parent_id, level) VALUES
(1, 'Одежда', NULL, 0),
(2, 'Брюки', 1, 1),
(3, 'Платья', 1, 1),
(4, 'Юбки', 1, 1),
(5, 'Клёш', 2, 2),
(6, 'Футляр', 3, 2),
(7, 'В пол', 3, 2),
(8, 'С открытыми плечами', 7, 3);


-- Добавляем дереву NS колонки
ALTER TABLE tree
    ADD COLUMN lft INT(11) UNSIGNED,
    ADD COLUMN rgt INT(11) UNSIGNED;

DELIMITER //
-- Создаём ту самую функцию
DROP FUNCTION IF EXISTS rebuild_nested_set_tree//
CREATE FUNCTION rebuild_nested_set_tree()
    RETURNS INT DETERMINISTIC MODIFIES SQL DATA
BEGIN
    -- Изначально сбрасываем все границы в NULL
    UPDATE tree t SET lft = NULL, rgt = NULL;

    -- Устанавливаем границы корневым элементам
    SET @i := 0;
    UPDATE tree t SET lft = (@i := @i + 1), rgt = (@i := @i + 1)
    WHERE t.parent_id IS NULL;

    forever: LOOP
        -- Находим элемент с минимальной правой границей -- самый левый в дереве
        SET @parent_id := NULL;
        SELECT t.id, t.rgt FROM tree t, tree tc
        WHERE t.id = tc.parent_id AND tc.lft IS NULL AND t.rgt IS NOT NULL
        ORDER BY t.rgt LIMIT 1 INTO @parent_id, @parent_right;

        -- Выходим из бесконечности, когда у нас уже нет незаполненных элементов
        IF @parent_id IS NULL THEN LEAVE forever; END IF;

        -- Сохраняем левую границу текущего ряда
        SET @current_left := @parent_right;

        -- Вычисляем максимальную правую границу текущего ряда
        SELECT @current_left + COUNT(*) * 2 FROM tree
        WHERE parent_id = @parent_id INTO @parent_right;

        -- Вычисляем длину текущего ряда
        SET @current_length := @parent_right - @current_left;

        -- Обновляем правые границы всех элементов, которые правее
        UPDATE tree t SET rgt = rgt + @current_length
        WHERE rgt >= @current_left ORDER BY rgt;

        -- Обновляем левые границы всех элементов, которые правее
        UPDATE tree t SET lft = lft + @current_length
        WHERE lft > @current_left ORDER BY lft;

        -- И только сейчас обновляем границы текущего ряда
        SET @i := (@current_left - 1);
        UPDATE tree t SET lft = (@i := @i + 1), rgt = (@i := @i + 1)
        WHERE parent_id = @parent_id ORDER BY id;
    END LOOP;

    -- Возвращаем самый самую правую границу для дальнейшего использования
    RETURN (SELECT MAX(rgt) FROM tree t);
END//

-- Запускаем переезд ...
SELECT rebuild_nested_set_tree();
-- ... и смотрим на наше дерево!
SELECT * FROM tree;

SELECT id, `title`, lft, rgt FROM tree ORDER BY lft;
CREATE DATABASE alg;
USE alg;

CREATE TABLE IF NOT EXISTS `cats`
(
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `parent_id` int(11)     NOT NULL,
    `name`      varchar(50) NOT NULL,
    PRIMARY KEY (`id`)
);

INSERT INTO `cats` (`id`, `parent_id`, `name`)
VALUES (1, 0, 'Категория 1'),
       (2, 0, 'Категория 2'),
       (3, 0, 'Категория 3'),
       (4, 1, 'Категория 1.1'),
       (5, 1, 'Категория 1.2'),
       (6, 4, 'Категория 1.1.1'),
       (7, 2, 'Категория 2.1'),
       (8, 2, 'Категория 2.2');



SELECT *
FROM alg.categories AS c
         INNER JOIN alg.category_links AS cl
                    ON c.id_category = cl.child_id
WHERE cl.parent_id = 3;


CREATE TABLE `categories`
(
    `id_category`   int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_name` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id_category`)
);

INSERT INTO `categories` (`id_category`, `category_name`)
VALUES (1, 'Каталог'),
       (2, 'Одежда'),
       (3, 'Продукты'),
       (4, 'Верхняя одежда'),
       (5, 'Молочные продуткы');


CREATE TABLE `category_links`
(
    `parent_id` int(11) UNSIGNED NOT NULL,
    `child_id`  int(11) DEFAULT NULL,
    `level`     int(11) DEFAULT NULL
);

INSERT INTO `category_links` (`parent_id`, `child_id`, `level`)
VALUES (1, 1, 0),
       (1, 2, 1),
       (1, 3, 1),
       (1, 4, 2),
       (1, 5, 2),
       (2, 2, 1),
       (2, 4, 2),
       (3, 3, 1),
       (3, 5, 2);


SELECT c.id_category as id, c.category_name as `name`, cl.parent_id, cl.level
FROM `categories` AS `c`
INNER JOIN `category_links` AS `cl` ON `c`.`id_category` = `cl`.`child_id`;

SELECT * FROM category_links WHERE child_id = parent_id;

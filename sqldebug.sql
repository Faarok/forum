
2024-03-04 18:38:10 | CREATE TABLE `forum`.`user` (
                `id` INT PRIMARY KEY AUTO_INCREMENT,
            `mail` VARCHAR(255), `password` CHAR(60), `state` VARCHAR(255), `created_at` DATETIME, `creator` VARCHAR(255), `updated_at` DATETIME, `updator` VARCHAR(255)
);

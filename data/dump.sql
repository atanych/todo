CREATE TABLE `task` (
  `id`          SERIAL,
  `name`        VARCHAR(30) NOT NULL COMMENT 'Название задачи',
  `description` TEXT        NOT NULL COMMENT 'Описание задачи',
  `deadline`    INT(11)     NOT NULL COMMENT 'Дедлайн задачи',
  PRIMARY KEY (`id`)
)
  COMMENT 'Задачи'
  COLLATE = 'utf8_general_ci'
  ENGINE = InnoDB;
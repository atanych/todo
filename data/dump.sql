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

INSERT INTO `task` (`id`, `name`, `description`, `deadline`) VALUES
  (1, 'Заплатить за инет', 'Просто заплатить за интернет!!! Обязательно', 1437081400),
  (2, 'Еще одна задачка', 'Нужно сделать то, не знаю чего, но обязательно это нужно сделать!', 1437772600),
  (3, 'Сделать ТЗ для sms-vote', 'Обязательно сделать тестовое задание для компании sms-vote', 1436995000);
ALTER TABLE `task`;
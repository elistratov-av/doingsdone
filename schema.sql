-- -----------------------------------------------------
-- Schema doingsdone
--
-- Дела в порядке
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS doingsdone DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE doingsdone;

-- -----------------------------------------------------
-- Table users
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  date_creation DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(128) NOT NULL UNIQUE,
  name VARCHAR(128) NOT NULL,
  password VARCHAR(255) NOT NULL);

-- -----------------------------------------------------
-- Table projects
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS projects (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128) NOT NULL,
  user_id INT NOT NULL,
  UNIQUE KEY uq_project_name (user_id, name),
  CONSTRAINT fk_project_user
    FOREIGN KEY (user_id)
    REFERENCES users (id));


-- -----------------------------------------------------
-- Table tasks
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS tasks (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  date_creation DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  completed TINYINT NOT NULL DEFAULT 0,
  name VARCHAR(128) NOT NULL,
  file VARCHAR(255),
  date_end DATE NULL,
  user_id INT NOT NULL,
  project_id INT NOT NULL,
  CONSTRAINT fk_task_user
    FOREIGN KEY (user_id)
    REFERENCES users (id),
  CONSTRAINT fk_task_project
    FOREIGN KEY (project_id)
    REFERENCES projects (id));

ALTER TABLE tasks ADD FULLTEXT(name);

-- MySQL Script generated by MySQL Workbench
-- Thu Jan 11 16:26:23 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema batepapo
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema batepapo
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `batepapo` DEFAULT CHARACTER SET utf8 ;
USE `batepapo` ;

-- -----------------------------------------------------
-- Table `batepapo`.`room`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `batepapo`.`room` (
  `id` INT(10) UNSIGNED NOT NULL,
  `code` VARCHAR(10) NOT NULL,
  `title` VARCHAR(18) NOT NULL,
  `createdAt` INT(10) UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code_UNIQUE` (`code` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `batepapo`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `batepapo`.`user` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `roomId` INT(10) UNSIGNED NOT NULL,
  `nickname` VARCHAR(18) NOT NULL,
  `color` VARCHAR(6) NOT NULL,
  `token` VARCHAR(32) NOT NULL,
  `createdAt` INT(10) UNSIGNED NULL,
  PRIMARY KEY (`id`, `roomId`),
  INDEX `fk_user_room_idx` (`roomId` ASC),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC),
  CONSTRAINT `fk_user_room`
    FOREIGN KEY (`roomId`)
    REFERENCES `batepapo`.`room` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `batepapo`.`message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `batepapo`.`message` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `roomId` INT(10) UNSIGNED NOT NULL,
  `userId` INT(10) UNSIGNED NOT NULL,
  `message` TEXT NOT NULL,
  `ip` VARCHAR(50) NULL,
  `userAgent` TEXT NULL,
  `createdAt` INT(10) UNSIGNED NULL,
  PRIMARY KEY (`id`, `roomId`, `userId`),
  INDEX `fk_message_room1_idx` (`roomId` ASC),
  INDEX `fk_message_user1_idx` (`userId` ASC),
  CONSTRAINT `fk_message_room1`
    FOREIGN KEY (`roomId`)
    REFERENCES `batepapo`.`room` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_message_user1`
    FOREIGN KEY (`userId`)
    REFERENCES `batepapo`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `batepapo`;

DELIMITER $$
USE `batepapo`$$
CREATE DEFINER = CURRENT_USER TRIGGER `batepapo`.`room_BEFORE_INSERT` BEFORE INSERT ON `room` FOR EACH ROW
BEGIN
	SET NEW.createdAt = UNIX_TIMESTAMP();
END$$

USE `batepapo`$$
CREATE DEFINER = CURRENT_USER TRIGGER `batepapo`.`user_BEFORE_INSERT` BEFORE INSERT ON `user` FOR EACH ROW
BEGIN
	SET NEW.createdAt = UNIX_TIMESTAMP();
END$$

USE `batepapo`$$
CREATE DEFINER = CURRENT_USER TRIGGER `batepapo`.`message_BEFORE_INSERT` BEFORE INSERT ON `message` FOR EACH ROW
BEGIN
	SET NEW.createdAt = UNIX_TIMESTAMP();
END$$


DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

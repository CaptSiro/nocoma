-- MySQL Script generated by MySQL Workbench
-- Fri Dec 16 17:18:22 2022
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema nocoma
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `nocoma` ;

-- -----------------------------------------------------
-- Schema nocoma
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `nocoma` DEFAULT CHARACTER SET utf8 ;
USE `nocoma` ;

-- -----------------------------------------------------
-- Table `nocoma`.`appeals`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `nocoma`.`appeals` ;

CREATE TABLE IF NOT EXISTS `nocoma`.`appeals` (
  `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usersID` INT UNSIGNED NOT NULL,
  `websitesID` INT UNSIGNED NOT NULL,
  `message` VARCHAR(1024) NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `nocoma`.`comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `nocoma`.`comments` ;

CREATE TABLE IF NOT EXISTS `nocoma`.`comments` (
  `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `websitesID` INT UNSIGNED NOT NULL,
  `parentCommentID` INT UNSIGNED NULL,
  `timePosted` DATETIME NOT NULL DEFAULT NOW(),
  `content` TEXT NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `ID_UNIQUE` (`ID` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `nocoma`.`media`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `nocoma`.`media` ;

CREATE TABLE IF NOT EXISTS `nocoma`.`media` (
  `src` VARCHAR(10) NOT NULL,
  `usersID` INT UNSIGNED NOT NULL,
  `basename` VARCHAR(200) NULL,
  `extension` VARCHAR(8) NULL,
  `mimeContentType` VARCHAR(100) NOT NULL,
  `timeCreated` DATETIME NOT NULL DEFAULT NOW(),
  `hash` CHAR(40) NOT NULL,
  `size` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`src`),
  UNIQUE INDEX `src_UNIQUE` (`src` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `nocoma`.`passwordRecoveries`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `nocoma`.`passwordRecoveries` ;

CREATE TABLE IF NOT EXISTS `nocoma`.`passwordRecoveries` (
  `passwordRecoveriesID` INT UNSIGNED NOT NULL,
  `urlArg` CHAR(32) NOT NULL,
  PRIMARY KEY (`passwordRecoveriesID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `nocoma`.`themes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `nocoma`.`themes` ;

CREATE TABLE IF NOT EXISTS `nocoma`.`themes` (
  `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(24) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `ID_UNIQUE` (`ID` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `nocoma`.`timeoutMails`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `nocoma`.`timeoutMails` ;

CREATE TABLE IF NOT EXISTS `nocoma`.`timeoutMails` (
  `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usersID` INT UNSIGNED NOT NULL,
  `expires` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `ID_UNIQUE` (`ID` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `nocoma`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `nocoma`.`users` ;

CREATE TABLE IF NOT EXISTS `nocoma`.`users` (
  `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `themesID` INT UNSIGNED NOT NULL,
  `profileSRC` VARCHAR(10) NULL,
  `email` VARCHAR(320) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `level` TINYINT(1) UNSIGNED NOT NULL,
  `website` VARCHAR(64) NOT NULL,
  `isVerified` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `isDisabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `username` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `usersID_UNIQUE` (`ID` ASC) VISIBLE,
  UNIQUE INDEX `website_UNIQUE` (`website` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `nocoma`.`verificationCodes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `nocoma`.`verificationCodes` ;

CREATE TABLE IF NOT EXISTS `nocoma`.`verificationCodes` (
  `verificationCodesID` INT UNSIGNED NOT NULL,
  `code` CHAR(6) NOT NULL,
  PRIMARY KEY (`verificationCodesID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `nocoma`.`websites`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `nocoma`.`websites` ;

CREATE TABLE IF NOT EXISTS `nocoma`.`websites` (
  `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usersID` INT UNSIGNED NOT NULL,
  `thumbnailSRC` VARCHAR(10) NOT NULL,
  `src` CHAR(10) NOT NULL,
  `timeCreated` DATETIME NOT NULL DEFAULT NOW(),
  `title` VARCHAR(64) NOT NULL,
  `isTemplate` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `isPublic` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `areCommentsAvailable` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `isHomepage` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `isTakenDown` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `ID_UNIQUE` (`ID` ASC) VISIBLE,
  UNIQUE INDEX `src_UNIQUE` (`src` ASC) VISIBLE)
ENGINE = InnoDB;

USE `nocoma`;

DELIMITER $$

USE `nocoma`$$
DROP TRIGGER IF EXISTS `nocoma`.`timeoutMails_BEFORE_DELETE` $$
USE `nocoma`$$
CREATE DEFINER = CURRENT_USER TRIGGER `nocoma`.`timeoutMails_BEFORE_DELETE` BEFORE DELETE ON `timeoutMails` FOR EACH ROW
BEGIN
	DELETE FROM `passwordRecoveries` WHERE `passwordRecoveries`.passwordRecoveriesID = OLD.ID;
    DELETE FROM `verificationCodes` WHERE `verificationCodes`.verificationCodesID = OLD.ID;
END$$


DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

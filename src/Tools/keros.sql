DROP DATABASE IF EXISTS keros;
CREATE DATABASE IF NOT EXISTS keros;
USE keros;

CREATE TABLE cat (
  id     int NOT NULL AUTO_INCREMENT,
  name   varchar(255),
  height float(5, 2),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `core_position` (
  `id` int(11) AUTO_INCREMENT,
  `label` varchar(64),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `core_department` (
  `id` int(11) AUTO_INCREMENT,
  `label` varchar(5),
  `name` varchar(64),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `core_country` (
  `id` int(11) AUTO_INCREMENT,
  `label` varchar(64),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `core_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) UNIQUE NOT NULL,
  `password` varchar(100),
  `gender` ENUM('A','H','F') DEFAULT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postalCode` int(11) DEFAULT NULL,
  `countryId` int(11) DEFAULT NULL,
  `schoolYear` int(11) DEFAULT NULL,
  `departmentId` int(11) DEFAULT NULL,
  `positionId` int(11) DEFAULT NULL,
  `disabled` BOOLEAN NOT NULL DEFAULT FALSE,
  `creationDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `core_user_username_key` (`username`),
  CONSTRAINT `core_user_countryId_fk` FOREIGN KEY (`countryId`) REFERENCES `core_country` (`id`),
  CONSTRAINT `core_user_departmentId_fk` FOREIGN KEY (`departmentId`) REFERENCES `core_department` (`id`),
  CONSTRAINT `core_user_positionId_fk` FOREIGN KEY (`positionId`) REFERENCES `core_position` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Temporary Data
INSERT INTO core_position (label) VALUES ('Président(e)'), ('Chadaff'), ('DSI');
INSERT INTO core_department (label, name) VALUES ('IF', 'Informatique'), ('TC', 'Télécommunications, Services & Usages');
INSERT INTO core_country (label) VALUES ('France'), ('Irlande'), ('Sénégal');

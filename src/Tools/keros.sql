DROP DATABASE IF EXISTS keros;
CREATE DATABASE IF NOT EXISTS keros;
USE keros;

CREATE TABLE cat (
  id     int NOT NULL AUTO_INCREMENT,
  name   varchar(255),
  height float(5, 2),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `core_pole` (
  `id` int(5) AUTO_INCREMENT,
  `label` varchar(5) NOT NULL UNIQUE,
  `name` varchar(40) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `core_position` (
  `id` int(11) AUTO_INCREMENT,
  `label` varchar(64) NOT NULL UNIQUE,
  `poleId` int(5) NOT NULL,
  CONSTRAINT `core_position_poleId_fk` FOREIGN KEY (`poleId`) REFERENCES `core_pole` (`id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `core_department` (
  `id` int(11) AUTO_INCREMENT,
  `label` varchar(15) NOT NULL UNIQUE,
  `name` varchar(64) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `core_country` (
  `id` int(11) AUTO_INCREMENT,
  `label` varchar(64) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `core_user` (
  `id` int(11) AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE ,
  `password` varchar(100) NOT NULL,
  `lastConnectedAt` DATETIME,
  `createdAt` DATETIME DEFAULT NOW(),
  `disabled` BOOLEAN NOT NULL DEFAULT FALSE,
  `expiresAt` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `core_user_username_key` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `core_gender` (
  `id` int(1) AUTO_INCREMENT,
  `label` VARCHAR(1) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `core_address` (
  `id` int(1) AUTO_INCREMENT,
  `line1` VARCHAR(64) NOT NULL,
  `line2` VARCHAR(64),
  `postalCode` VARCHAR(10) NOT NULL,
  `city` VARCHAR(64) NOT NULL,
  `countryId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `core_address_countryId_fk` FOREIGN KEY (`countryId`) REFERENCES `core_country` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--- L'ID de core_member est le même le core_user qui lui est attaché
CREATE TABLE `core_member` (
  `id` int(11) NOT NULL,
  `genderId` int(1) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL UNIQUE,
  `email` varchar(255) NOT NULL UNIQUE,
  `addressId` int(11) NOT NULL UNIQUE,
  `schoolYear` int(11) DEFAULT NULL,
  `departmentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `core_user_userId_fk` FOREIGN KEY (`id`) REFERENCES `core_user` (`id`),
  CONSTRAINT `core_user_genderId_fk` FOREIGN KEY (`genderId`) REFERENCES `core_gender` (`id`),
  CONSTRAINT `core_user_addressId_fk` FOREIGN KEY (`addressId`) REFERENCES `core_address` (`id`),
  CONSTRAINT `core_user_departmentId_fk` FOREIGN KEY (`departmentId`) REFERENCES `core_department` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `core_member_position` (
  `memberId` int(11) NOT NULL,
  `positionId` int(11) NOT NULL,
  PRIMARY KEY (`memberId`, `positionId`),
  CONSTRAINT `core_member_position_memberId_fk` FOREIGN KEY (`memberId`) REFERENCES `core_member` (`id`),
  CONSTRAINT `core_position_position_positionId_fk` FOREIGN KEY (`positionId`) REFERENCES `core_position` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Temporary Test Data
INSERT INTO core_pole (id, label, name) VALUES
  (1, 'DSI', 'Direction des Systèmes d''Information'),
  (2, 'Bu', 'Bureau'),
  (3, 'UA', 'Unités d''affaires');

INSERT INTO core_position (label, poleId) VALUES
  ('Président(e)', 2),
  ('Chadaff', 3),
  ('Junior DSI', 1);

INSERT INTO core_department (id, label, name) VALUES
  (1, 'IF', 'Informatique'),
  (2, 'TC', 'Télécommunications, Services & Usages');

INSERT INTO core_country (label) VALUES
  ('France'),
  ('Irlande'),
  ('Sénégal');

INSERT INTO core_user (id, username, password, expiresAt) VALUES
  (1, 'cbreeze', 'hunter11' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')),
  (2, 'mcool', 'hunter12' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')),
  (3, 'lswollo', 'hunter13' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'));

INSERT INTO core_gender (label) VALUES ('M'), ('F'), ('A'), ('I');

INSERT INTO core_address (id, line1, line2, postalCode, city, countryId) VALUES
  (1, '13 rue renard', null, '69100', 'lyon', 1),
  (2, '11 baker street', 'appt 501', '6930A', 'dublin', 2),
  (3, '11 fish street', 'bat. b', '91002', 'paris', 1);

INSERT INTO core_member (id, genderId, firstName, lastName, birthdate, telephone, email, addressId, schoolYear, departmentId) VALUES
  (1, 1, 'Conor', 'Breeze', STR_TO_DATE('1975-12-25', '%Y-%m-%d'), '+332541254', 'fake.mail@fake.com', 2, 3, 1),
  (2, 1, 'Marah', 'Cool', STR_TO_DATE('1976-10-27', '%Y-%m-%d'), '+332541541', 'fake.mail2@fake.com', 1, 3, 1),
  (3, 1, 'Lolo', 'Swollo', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.mail3@fake.com', 3, 5, 2);

INSERT INTO core_member_position (memberId, positionId) VALUES
  (1, 3),
  (2, 3),
  (3, 1),
  (3, 2),
  (3, 3);

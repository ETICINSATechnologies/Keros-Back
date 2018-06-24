SET AUTOCOMMIT = 0;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;

DROP TABLE IF EXISTS cat;
CREATE TABLE cat (
  id     int NOT NULL AUTO_INCREMENT,
  name   varchar(255),
  height float(5, 2),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS core_pole;
CREATE TABLE `core_pole` (
  `id` int(5) AUTO_INCREMENT,
  `label` varchar(5) NOT NULL UNIQUE,
  `name` varchar(40) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS core_position;
CREATE TABLE `core_position` (
  `id` int(11) AUTO_INCREMENT,
  `label` varchar(64) NOT NULL UNIQUE,
  `poleId` int(5) NOT NULL,
  CONSTRAINT `core_position_poleId_fk` FOREIGN KEY (`poleId`) REFERENCES `core_pole` (`id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS core_department;
CREATE TABLE `core_department` (
  `id` int(11) AUTO_INCREMENT,
  `label` varchar(15) NOT NULL UNIQUE,
  `name` varchar(64) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS core_country;
CREATE TABLE `core_country` (
  `id` int(11) AUTO_INCREMENT,
  `label` varchar(64) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS core_user;
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

DROP TABLE IF EXISTS core_gender;
CREATE TABLE `core_gender` (
  `id` int(1) AUTO_INCREMENT,
  `label` VARCHAR(1) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS core_address;
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

# L'ID de core_member est le même le core_user qui lui est attaché
DROP TABLE IF EXISTS core_member;
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

DROP TABLE IF EXISTS core_member_position;
CREATE TABLE `core_member_position` (
  `memberId` int(11) NOT NULL,
  `positionId` int(11) NOT NULL,
  PRIMARY KEY (`memberId`, `positionId`),
  CONSTRAINT `core_member_position_memberId_fk` FOREIGN KEY (`memberId`) REFERENCES `core_member` (`id`),
  CONSTRAINT `core_position_position_positionId_fk` FOREIGN KEY (`positionId`) REFERENCES `core_position` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET AUTOCOMMIT = 1;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;

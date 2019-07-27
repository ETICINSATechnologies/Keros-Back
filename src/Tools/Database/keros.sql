SET AUTOCOMMIT = 0;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;

DROP TABLE IF EXISTS core_pole;
CREATE TABLE `core_pole` (
  `id`    int(5) AUTO_INCREMENT,
  `label` varchar(5)  NOT NULL UNIQUE,
  `name`  varchar(40) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS core_position;
CREATE TABLE `core_position` (
  `id`     int(11) AUTO_INCREMENT,
  `label`  varchar(64) NOT NULL UNIQUE,
  `poleId` int(5),
  CONSTRAINT `core_position_poleId_fk` FOREIGN KEY (`poleId`) REFERENCES `core_pole` (`id`),
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS core_department;
CREATE TABLE `core_department` (
  `id`    int(11) AUTO_INCREMENT,
  `label` varchar(15) NOT NULL UNIQUE,
  `name`  varchar(64) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS core_country;
CREATE TABLE `core_country` (
  `id`    int(11) AUTO_INCREMENT,
  `label` varchar(64) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS core_user;
CREATE TABLE `core_user` (
  `id`              int(11)               AUTO_INCREMENT,
  `username`        varchar(50)  NOT NULL UNIQUE,
  `password`        varchar(100) NOT NULL,
  `lastConnectedAt` DATETIME,
  `createdAt`       DATETIME              DEFAULT NOW(),
  `disabled`        BOOLEAN      NOT NULL DEFAULT FALSE,
  `expiresAt`       DATETIME,
  PRIMARY KEY (`id`),
  KEY `core_user_username_key` (`username`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS core_gender;
CREATE TABLE `core_gender` (
  `id`    int(1) AUTO_INCREMENT,
  `label` VARCHAR(1) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS core_address;
CREATE TABLE `core_address` (
  `id`         int(1) AUTO_INCREMENT,
  `line1`      VARCHAR(64) NOT NULL,
  `line2`      VARCHAR(64),
  `postalCode` VARCHAR(10) NOT NULL,
  `city`       VARCHAR(64) NOT NULL,
  `countryId`  int(11)     NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `core_address_countryId_fk` FOREIGN KEY (`countryId`) REFERENCES `core_country` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS core_ticket;
CREATE TABLE `core_ticket` (
  `id`      int(1) AUTO_INCREMENT,
  `userId`  int(11)     NOT NULL,
  `title`   VARCHAR(64) NOT NULL,
  `message` VARCHAR(64) NOT NULL,
  `type`    VARCHAR(64) NOT NULL,
  `status`  VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `core_ticket_userId_fk` FOREIGN KEY (`userId`) REFERENCES `core_member` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- L'ID de core_member est le même que celui de core_user qui lui est attaché
DROP TABLE IF EXISTS core_member;
CREATE TABLE `core_member` (
  `id`           int(11)      NOT NULL,
  `genderId`     int(1)       NOT NULL,
  `firstName`    varchar(100) NOT NULL,
  `lastName`     varchar(100) NOT NULL,
  `birthday`     date        DEFAULT NULL,
  `telephone`    varchar(20) DEFAULT NULL,
  `email`        varchar(255) NOT NULL UNIQUE,
  `addressId`    int(11)      NOT NULL UNIQUE,
  `schoolYear`   int(11)     DEFAULT NULL,
  `departmentId` int(11)     DEFAULT NULL,
  `company` varchar(255)     DEFAULT NULL,
  `profilePicture` varchar(255)     DEFAULT NULL,
  `droitImage` boolean DEFAULT TRUE,
  PRIMARY KEY (`id`),
  CONSTRAINT `core_member_userId_fk` FOREIGN KEY (`id`) REFERENCES `core_user` (`id`),
  CONSTRAINT `core_member_genderId_fk` FOREIGN KEY (`genderId`) REFERENCES `core_gender` (`id`),
  CONSTRAINT `core_member_addressId_fk` FOREIGN KEY (`addressId`) REFERENCES `core_address` (`id`),
  CONSTRAINT `core_member_departmentId_fk` FOREIGN KEY (`departmentId`) REFERENCES `core_department` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- L'ID de core_consultant est le même que celui de core_user qui lui est attaché
DROP TABLE IF EXISTS core_consultant;
CREATE TABLE `core_consultant` (
  `id`           int(11)      NOT NULL,
  `genderId`     int(1)       NOT NULL,
  `firstName`    varchar(100) NOT NULL,
  `lastName`     varchar(100) NOT NULL,
  `birthday`     date        DEFAULT NULL,
  `telephone`    varchar(20) DEFAULT NULL,
  `email`        varchar(255) NOT NULL UNIQUE,
  `addressId`    int(11)      NOT NULL UNIQUE,
  `schoolYear`   int(11)     DEFAULT NULL,
  `departmentId` int(11)     DEFAULT NULL,
  `company` varchar(255)     DEFAULT NULL,
  `profilePicture` varchar(255)     DEFAULT NULL,
  `droitImage` boolean DEFAULT TRUE,
  PRIMARY KEY (`id`),
  CONSTRAINT `core_consultant_userId_fk` FOREIGN KEY (`id`) REFERENCES `core_user` (`id`),
  CONSTRAINT `core_consultant_genderId_fk` FOREIGN KEY (`genderId`) REFERENCES `core_gender` (`id`),
  CONSTRAINT `core_consultant_addressId_fk` FOREIGN KEY (`addressId`) REFERENCES `core_address` (`id`),
  CONSTRAINT `core_consultant_departmentId_fk` FOREIGN KEY (`departmentId`) REFERENCES `core_department` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS core_member_position;
CREATE TABLE `core_member_position` (
  `id`         int(11) AUTO_INCREMENT,
  `memberId`   int(11) NOT NULL,
  `positionId` int(11) NOT NULL,
  `isBoard`    BOOLEAN NOT NULL DEFAULT FALSE,
  `year`       int(11),
  PRIMARY KEY (`id`),
  CONSTRAINT `core_member_position_memberId_fk` FOREIGN KEY (`memberId`) REFERENCES `core_member` (`id`),
  CONSTRAINT `core_position_position_positionId_fk` FOREIGN KEY (`positionId`) REFERENCES `core_position` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS ua_firm_type;
CREATE TABLE `ua_firm_type` (
  `id`    int(2) AUTO_INCREMENT,
  `label` varchar(20) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS ua_firm;
CREATE TABLE `ua_firm` (
  `id`        int(11) AUTO_INCREMENT,
  `siret`     varchar(20) UNIQUE,
  `name`      varchar(64) NOT NULL UNIQUE,
  `addressId` int(11)     NOT NULL UNIQUE,
  `typeId`    int(11)     NOT NULL,
  `mainContact`  int(11),
  PRIMARY KEY (`id`),
  CONSTRAINT `ua_firm_address_addressId_fk` FOREIGN KEY (`addressId`) REFERENCES `core_address` (`id`),
  CONSTRAINT `ua_firm_type_typeId_fk` FOREIGN KEY (`typeId`) REFERENCES `ua_firm_type` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- L'ID de ua_contact est le même que celui de core_user qui lui est attaché
DROP TABLE IF EXISTS ua_contact;
CREATE TABLE `ua_contact` (
  `id`           int(11) AUTO_INCREMENT,
  `firstName`    varchar(100) NOT NULL,
  `lastName`     varchar(100) NOT NULL,
  `genderId`     int(1)       NOT NULL,
  `firmId`       int(11)      NOT NULL,
  `email`        varchar(255) NOT NULL UNIQUE,
  `telephone`    varchar(20),
  `cellphone`    varchar(20),
  `position`     varchar(255),
  `notes`        varchar(255),
  `old`          BOOLEAN      NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`),
  CONSTRAINT `ua_contact_genderId_fk` FOREIGN KEY (`genderId`) REFERENCES `core_gender` (`id`),
  CONSTRAINT `ua_contact_firmId_fk` FOREIGN KEY (`firmId`) REFERENCES `ua_firm` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS ua_field;
CREATE TABLE `ua_field` (
  `id`           int(11)      NOT NULL,
  `label`        varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS ua_provenance;
CREATE TABLE `ua_provenance` (
  `id`           int(11)      NOT NULL,
  `label`        varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS ua_status;
CREATE TABLE `ua_status` (
  `id`           int(11)      NOT NULL,
  `label`        varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS ua_study;
CREATE TABLE `ua_study` (
  `id`           int(11) AUTO_INCREMENT,
  `name`         varchar(100) NOT NULL,
  `description`   varchar(255),
  `fieldId`      int(11),
  `provenanceId` int(11),
  `statusId`     int(11),
  `signDate`     date,
  `endDate`      date,
  `managementFee` decimal(12,2),
  `realizationFee`decimal(12,2),
  `rebilledFee`  decimal(12,2),
  `ecoparticipationFee` decimal(12,2),
  `outsourcingFee` decimal(12,2),
  `archivedDate` date,
  `firmId`      int(11),
  `confidential` boolean,
  `mainLeader` int(11),
  `mainQualityManager` int(11),
  `mainConsultant` int(11),

  PRIMARY KEY (`id`),
  CONSTRAINT `ua_study_fieldId_fk` FOREIGN KEY (`fieldId`) REFERENCES `ua_field` (`id`),
  CONSTRAINT `ua_study_provenanceId_fk` FOREIGN KEY (`provenanceId`) REFERENCES `ua_provenance` (`id`),
  CONSTRAINT `ua_study_statusId_fk` FOREIGN KEY (`statusId`) REFERENCES `ua_status` (`id`),
  CONSTRAINT `ua_study_firmId_fk` FOREIGN KEY (`firmId`) REFERENCES `ua_firm` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS ua_study_contact;
CREATE TABLE `ua_study_contact` (
  `contactId`   int(11) NOT NULL,
  `studyId`     int(11) NOT NULL,
  PRIMARY KEY (`contactId`, `studyId`),
  CONSTRAINT `ua_study_contact_studyId_fk` FOREIGN KEY (`studyId`) REFERENCES `ua_study` (`id`),
  CONSTRAINT `ua_study_contact_contactId_fk` FOREIGN KEY (`contactId`) REFERENCES `ua_contact` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS ua_study_leader;
CREATE TABLE ua_study_leader (
  `memberId`   int(11) NOT NULL,
  `studyId`     int(11) NOT NULL,
  PRIMARY KEY (`memberId`, `studyId`),
  CONSTRAINT `ua_study_leader_studyId_fk` FOREIGN KEY (`studyId`) REFERENCES `ua_study` (`id`),
  CONSTRAINT `ua_study_leader_memberId_fk` FOREIGN KEY (`memberId`) REFERENCES `core_member` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS ua_study_consultant;
CREATE TABLE ua_study_consultant (
  `consultantId`   int(11) NOT NULL,
  `studyId`     int(11) NOT NULL,
  PRIMARY KEY (`consultantId`, `studyId`),
  CONSTRAINT `ua_study_consultant_studyId_fk` FOREIGN KEY (`studyId`) REFERENCES `ua_study` (`id`),
  CONSTRAINT `ua_study_consultant_consultantId_fk` FOREIGN KEY (`consultantId`) REFERENCES `core_consultant` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS ua_study_qualityManager;
CREATE TABLE ua_study_qualityManager (
  `memberId`   int(11) NOT NULL,
  `studyId`     int(11) NOT NULL,
  PRIMARY KEY (`memberId`, `studyId`),
  CONSTRAINT `ua_study_qualityManager_studyId_fk` FOREIGN KEY (`studyId`) REFERENCES `ua_study` (`id`),
  CONSTRAINT `ua_study_qualityManager_memberId_fk` FOREIGN KEY (`memberId`) REFERENCES `core_member` (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS ua_study_document_type;
CREATE TABLE ua_study_document_type (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL UNIQUE,
  isTemplatable boolean NOT NULL,
  oneConsultant boolean NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS core_document;
CREATE TABLE core_document (
  id int(11) NOT NULL AUTO_INCREMENT,
  uploadDate datetime NOT NULL,
  `location` varchar(255) NOT NULL UNIQUE,
  discr varchar(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ua_study_document;
CREATE TABLE `ua_study_document` (
  `id` int(11) NOT NULL,
  `studyId` int(11) NOT NULL,
  studyDocumentTypeId int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_study_document_core_document FOREIGN KEY (id) REFERENCES core_document(id),
  CONSTRAINT `fk_ua_study_document_ua_study` FOREIGN KEY (`studyId`) REFERENCES ua_study(`id`),
  CONSTRAINT fk_study_document_study_document_type FOREIGN KEY (studyDocumentTypeId) REFERENCES ua_study_document_type(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS treso_facture_type;
CREATE TABLE `treso_facture_type` (
  `id`    int(1) AUTO_INCREMENT,
  `label` VARCHAR(255) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS treso_facture;
CREATE TABLE treso_facture (
  id int(1) AUTO_INCREMENT,
  numero varchar(32),
  addressId int(11),
  clientName varchar(255),
  contactName varchar(255),
  contactEmail varchar(255),
  studyId int(11) NOT NULL,
  typeId int(11) NOT NULL,
  amountDescription varchar(2048),
  subject varchar(255),
  agreementSignDate date,
  amountHT float,
  taxPercentage float,
  dueDate date,
  additionalInformation varchar(2048),
  createdDate date,
  createdById int(11),
  validatedByUa boolean,
  validatedByUaDate date,
  validatedByUaMemberId int(11),
  validatedByPerf boolean,
  validatedByPerfDate date,
  validatedByPerfMemberId int(11),
  PRIMARY KEY (id),
  CONSTRAINT fk_facture_address FOREIGN KEY (addressId) REFERENCES core_address(id),
  CONSTRAINT fk_facture_study FOREIGN KEY (studyId) REFERENCES ua_study(id),
  CONSTRAINT fk_facture_facture_type FOREIGN KEY (typeId) REFERENCES treso_facture_type(id),
  CONSTRAINT fk_facture_createdBy_member FOREIGN KEY (createdById) REFERENCES core_member(id),
  CONSTRAINT fk_facture_validatedByUa_member FOREIGN KEY (validatedByUaMemberId) REFERENCES core_member(id),
  CONSTRAINT fk_facture_validatedByPerf_member FOREIGN KEY (validatedByPerfMemberId) REFERENCES core_member(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS treso_facture_document_type;
CREATE TABLE treso_facture_document_type (
  id int(11) NOT NULL AUTO_INCREMENT,
  location varchar(255) NOT NULL UNIQUE,
  name varchar(255) NOT NULL,
  isTemplatable boolean NOT NULL,
  factureTypeId int(11) UNIQUE,
  PRIMARY KEY (id),
  CONSTRAINT fk_facture_document_type_facture_type FOREIGN KEY (factureTypeId) REFERENCES treso_facture_type(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS treso_facture_document;
CREATE TABLE treso_facture_document (
  id int(11) AUTO_INCREMENT,
  factureId int(11) NOT NULL,
  factureDocumentTypeId int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_treso_document_core_document FOREIGN KEY (id) REFERENCES core_document(id),
  CONSTRAINT `fk_treso_facture_document_treso_facture` FOREIGN KEY (factureId) REFERENCES treso_facture(`id`),
  CONSTRAINT fk_treso_document_treso_document_type FOREIGN KEY (factureDocumentTypeId) REFERENCES treso_facture_document_type(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS sg_member_inscription;
CREATE TABLE sg_member_inscription (
  id int(11) NOT NULL AUTO_INCREMENT,
  firstName varchar(255) NOT NULL,
  lastName varchar(255) NOT NULL,
  birthday date NOT NULL,
  genderId int(11) NOT NULL,
  departmentId int(11) NOT NULL,
  email varchar(255) NOT NULL,
  phoneNumber varchar(255),
  outYear int,
  nationalityId int(11) NOT NULL,
  wantedPoleId int(11) NOT NULL,
  addressId int(11) NOT NULL,
  hasPaid boolean DEFAULT FALSE,
  droitImage boolean DEFAULT FALSE,
  PRIMARY KEY (id),
  CONSTRAINT fk_sg_member_inscription_department FOREIGN KEY (departmentId) REFERENCES core_department(id),
  CONSTRAINT fk_sg_member_inscription_nationality FOREIGN KEY (nationalityId) REFERENCES core_country(id),
  CONSTRAINT fk_sg_member_inscription_pole FOREIGN KEY (wantedPoleId) REFERENCES core_pole(id),
  CONSTRAINT fk_sg_member_inscription_gender FOREIGN KEY (genderId) REFERENCES core_gender(id),
  CONSTRAINT fk_sg_member_inscription_address FOREIGN KEY (addressId) REFERENCES core_address(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS sg_member_inscription_document_type;
CREATE TABLE sg_member_inscription_document_type (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(255) NOT NULL UNIQUE,
  `name` varchar(255) NOT NULL,
  isTemplatable boolean NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


SET AUTOCOMMIT = 1;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;

INSERT INTO `core_pole` (id, label, name) VALUES
  (1, 'Com', 'Communication'),
  (2, 'Cons', 'Consultant'),
  (3, 'DevCo', 'Developpement Commercial'),
  (4, 'Perf', 'Performance'),
  (5, 'Prez', 'Présidence'),
  (6, 'RH', 'Ressources Humaines'),
  (7, 'SI', 'Systèmes d''Information,'),
  (8, 'Treso', 'Trésorerie'),
  (9, 'UA', 'Unité d''affaires');

INSERT INTO `core_position` (id, label, poleId) VALUES
  (1, 'Auditeur orga', null),
  (2, 'Auditeur treso', null),
  (3, 'Chargé d''affaires', 3),
  (4, 'Chef de projets', 9),
  (5, 'Comptable', 8),
  (6, 'Consultant', 2),
  (7, 'Junior com', 1),
  (8, 'Junior devCo', 3),
  (9, 'Junior qualité', 4),
  (10, 'Junior SI', 7),
  (11, 'Junior UA', 9),
  (12, 'Membre CNJE', null),
  (13, 'Membre d''Honneur', null),
  (14, 'Président', 5),
  (15, 'Responsable BU', 9),
  (16, 'Responsable com', 1),
  (17, 'Responsable devCo', 3),
  (18, 'Responsable qualité', 4),
  (19, 'Responsable RH', 6),
  (20, 'Responsable SI', 7),
  (21, 'Responsable d''UA', 9),
  (22, 'Secrétaire général', 6),
  (23, 'Trésorier', 8),
  (24, 'Vice-Président', 5),
  (25, 'Vice-Trésorier', 8);

INSERT INTO core_department (id, label, name) VALUES
  (1, 'BB', 'Biochimie et Biotechnologies'),
  (2, 'BIM', 'BioInformatique et Modélisation'),
  (3, 'GCU', 'Génie civil et urbanisme'),
  (4, 'GE', 'Génie électrique'),
  (5, 'GEN', 'Génie énergétique et environnement'),
  (6, 'GI', 'Génie Industriel'),
  (7, 'GMC', 'Génie mécanique conception'),
  (8, 'GMD', 'Génie mécanique développement'),
  (9, 'GMPP', 'Génie mécanique procédés plasturgie'),
  (10, 'IF', 'Informatique'),
  (11, 'PC', 'Premier Cycle'),
  (12, 'SGM', 'Science et Génie des Matériaux'),
  (13, 'TC', 'Télécommunications, Services et Usages');

INSERT INTO `core_country` (id, label) VALUES
  (1, 'Afghanistan'), (2, 'Afrique du Sud'), (3, 'Albanie'), (4, 'Algérie'), (5, 'Allemagne'), (6, 'Andorre'),
  (7, 'Angola'), (8, 'Antigua-et-Barbuda'), (9, 'Arabie saoudite'), (10, 'Argentine'), (11, 'Arménie'),
  (12, 'Australie'), (13, 'Autriche'), (14, 'Azerbaïdjan'), (15, 'Bahamas'), (16, 'Bahreïn'), (17, 'Bangladesh'),
  (18, 'Barbade'), (19, 'Belgique'), (20, 'Belize'), (21, 'Bénin'), (22, 'Bhoutan'), (23, 'Biélorussie'),
  (24, 'Birmanie'), (25, 'Bolivie'), (26, 'Bosnie-Herzégovine'), (27, 'Botswana'), (28, 'Brésil'), (29, 'Brunei'),
  (30, 'Bulgarie'), (31, 'Burkina Faso'), (32, 'Burundi'), (33, 'Cambodge'), (34, 'Cameroun'), (35, 'Canada'),
  (36, 'Cap-Vert'), (37, 'Chili'), (38, 'Chine'), (39, 'Chypre'), (40, 'Colombie'), (41, 'Comores'),
  (42, 'Corée du Nord'), (43, 'Corée du Sud'), (44, 'Costa Rica'), (45, 'Côte d''Ivoire'), (46, 'Crete'),
  (47, 'Croatie'), (48, 'Cuba'), (49, 'Danemark'), (50, 'Djibouti'), (51, 'Dominique'), (52, 'Égypte'),
  (53, 'Émirats arabes unis'), (54, 'Équateur'), (55, 'Érythrée'), (56, 'Espagne'), (57, 'Estonie'), (58, 'États-Unis'),
  (59, 'Éthiopie'), (60, 'Fidji'), (61, 'Finlande'), (62, 'France'), (63, 'Gabon'), (64, 'Gambie'), (65, 'Géorgie'),
  (66, 'Ghana'), (67, 'Grèce'), (68, 'Grenade'), (69, 'Guatemala'), (70, 'Guinée'), (71, 'Guinée équatoriale'),
  (72, 'Guinée-Bissau'), (73, 'Guyana'), (74, 'Haïti'), (75, 'Honduras'), (76, 'Hongrie'), (77, 'Inde'),
  (78, 'Indonésie'), (79, 'Irak'), (80, 'Iran'), (81, 'Irlande'), (82, 'Islande'), (83, 'Israël'), (84, 'Italie'),
  (85, 'Jamaïque'), (86, 'Japon'), (87, 'Jordanie'), (88, 'Kazakhstan'), (89, 'Kenya'), (90, 'Kirghizistan'),
  (91, 'Kiribati'), (92, 'Koweït'), (93, 'Laos'), (94, 'Lesotho'), (95, 'Lettonie'), (96, 'Liban'), (97, 'Liberia'),
  (98, 'Libye'), (99, 'Liechtenstein'), (100, 'Lituanie'), (101, 'Luxembourg'), (102, 'Macédoine'), (103, 'Madagascar'),
  (104, 'Malaisie'), (105, 'Malawi'), (106, 'Maldives'), (107, 'Mali'), (108, 'Malte'), (109, 'Maroc'),
  (110, 'Marshall'), (111, 'Maurice'), (112, 'Mauritanie'), (113, 'Mexique'), (114, 'Micronésie'), (115, 'Moldavie'),
  (116, 'Monaco'), (117, 'Mongolie'), (118, 'Monténégro'), (119, 'Mozambique'), (120, 'Namibie'), (121, 'Nauru'),
  (122, 'Népal'), (123, 'Nicaragua'), (124, 'Niger'), (125, 'Nigeria'), (126, 'Norvège'), (127, 'Nouvelle-Zélande'),
  (128, 'Oman'), (129, 'Ouganda'), (130, 'Ouzbékistan'), (131, 'Pakistan'), (132, 'Palaos'), (133, 'Panama'),
  (134, 'Papouasie-Nouvelle-Guinée'), (135, 'Paraguay'), (136, 'Pays-Bas'), (137, 'Pérou'), (138, 'Philippines'),
  (139, 'Pologne'), (140, 'Portugal'), (141, 'Qatar'), (142, 'République centrafricaine'),
  (143, 'République démocratique du Congo'), (144, 'République dominicaine'), (145, 'République du Congo'),
  (146, 'République tchèque'), (147, 'Roumanie'), (148, 'Royaume-Uni'), (149, 'Russie'), (150, 'Rwanda'),
  (151, 'Sahara Occidental'), (152, 'Saint-Christophe-et-Niévès'), (153, 'Saint-Marin'),
  (154, 'Saint-Vincent-et-les-Grenadines'), (155, 'Sainte-Lucie'), (156, 'Salomon'), (157, 'Salvador'), (158, 'Samoa'),
  (159, 'Sao Tomé-et-Principe'), (160, 'Sénégal'), (161, 'Serbie'), (162, 'Seychelles'), (163, 'Sierra Leone'),
  (164, 'Singapour'), (165, 'Slovaquie'), (166, 'Slovénie'), (167, 'Somalie'), (168, 'Soudan'), (169, 'Soudan du Sud'),
  (170, 'Sri Lanka'), (171, 'Suède'), (172, 'Suisse'), (173, 'Suriname'), (174, 'Swaziland'), (175, 'Syrie'),
  (176, 'Tadjikistan'), (177, 'Tanzanie'), (178, 'Tchad'), (179, 'Thaïlande'), (180, 'Timor oriental'), (181, 'Togo'),
  (182, 'Tonga'), (183, 'Trinité-et-Tobago'), (184, 'Tunisie'), (185, 'Turkménistan'), (186, 'Turquie'),
  (187, 'Tuvalu'), (188, 'Ukraine'), (189, 'Uruguay'), (190, 'Vanuatu'), (191, 'Vatican'), (192, 'Venezuela'),
  (193, 'Viêt Nam'), (194, 'Yémen'), (195, 'Zambie'), (196, 'Zimbabwe');

INSERT INTO ua_firm_type (id, label) VALUES
  (1, 'Particulier'), (2, 'TPE/start-up'), (3, 'PME'), (4, 'Grand Groupe'), (5, 'Administration'), (6, 'Association'),
  (7, 'Junior-Entreprise');

INSERT INTO core_gender (id, label) VALUES (1, 'H'), (2, 'F'), (3, 'A'), (4, 'I');

INSERT INTO `ua_status` (`id`, `label`) VALUES
  (1, 'En cours d''exécution'),
  (2, 'En clôture'),
  (3, 'Clôturée'),
  (4, 'En rupture'),
  (5, 'Rompue');

INSERT INTO `ua_provenance` (`id`, `label`) VALUES
  (1, 'Site Web'),
  (2, 'Ancien Client'),
  (3, 'Kiwi'),
  (4, 'Dev''Co'),
  (5, 'Appel'),
  (6, 'Partenariat EM'),
  (7, 'Junior-Entreprise'),
  (8, 'INSA'),
  (9, 'Appel d''offre'),
  (10, 'Phoning'),
  (11, 'Mailing'),
  (12, 'Mail'),
  (13, 'Autre');

INSERT INTO `ua_field` (`id`, `label`) VALUES
  (1, 'Web'),
  (2, 'Appli mobile'),
  (3, 'Dév logiciel'),
  (4, 'Mécanique'),
  (5, 'Électronique'),
  (6, 'SGM'),
  (7, 'Biosciences'),
  (8, 'GCU'),
  (9, 'Energétique'),
  (10, 'Études de marché'),
  (11, 'Benchmark'),
  (12, 'Productique'),
  (13, 'Traduction');

INSERT INTO treso_facture_type (id, label) VALUES
  (1, 'proforma'),
  (2, 'acompte'),
  (3, 'intermediaire'),
  (4, 'solde');

COMMIT;
SET AUTOCOMMIT = 0;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
SET NAMES 'utf8' COLLATE 'utf8_general_ci';

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
  `isEu` BOOLEAN  NOT NULL,
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
  `birthday`     date        NOT NULL,
  `telephone`    varchar(20) NOT NULL,
  `email`        varchar(255) NOT NULL UNIQUE,
  `addressId`    int(11)      NOT NULL UNIQUE,
  `schoolYear`   int(11)     NOT NULL,
  `departmentId` int(11)     NOT NULL,
  `company` varchar(255)     DEFAULT NULL,
  `profilePicture` varchar(255)     DEFAULT NULL,
  `droitImage` boolean DEFAULT TRUE,
  `createdDate` date  NOT NULL,
  `isAlumni` boolean  DEFAULT FALSE,
  `emailETIC` varchar(255) DEFAULT NULL,
  `dateRepayment` date NOT NULL,
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
  `nationalityId` int(11) NOT NULL,
  `email`        varchar(255) NOT NULL UNIQUE,
  `addressId`    int(11)      NOT NULL UNIQUE,
  `socialSecurityNumber`    varchar(255) DEFAULT NULL,  
  `schoolYear`   int(11)     DEFAULT NULL,
  `departmentId` int(11)     DEFAULT NULL,
  `company` varchar(255)     DEFAULT NULL,
  `profilePicture` varchar(255)     DEFAULT NULL,
  `droitImage` boolean DEFAULT TRUE,
  `isApprentice` boolean NOT NULL,
  `createdDate` date  NOT NULL,
  `documentIdentity` varchar(255) DEFAULT NULL,
  `documentScolaryCertificate` varchar(255) DEFAULT NULL,
  `documentRIB` varchar(255) DEFAULT NULL,
  `documentVitaleCard` varchar(255) DEFAULT NULL,
  `documentResidencePermit` varchar(255) DEFAULT NULL,
  `documentCVEC` varchar(255) DEFAULT NULL,
  isGraduate boolean NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`),
  CONSTRAINT `core_consultant_userId_fk` FOREIGN KEY (`id`) REFERENCES `core_user` (`id`),
  CONSTRAINT `core_consultant_nationalityId_fk` FOREIGN KEY (nationalityId) REFERENCES core_country(id),
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
  `studyId` int(11),
  studyDocumentTypeId int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_study_document_core_document FOREIGN KEY (id) REFERENCES core_document(id),
  CONSTRAINT `fk_ua_study_document_ua_study` FOREIGN KEY (`studyId`) REFERENCES ua_study(`id`) ON DELETE SET NULL,
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
  id int(1) AUTO_INCREMENT NOT NULL,
  numero varchar(32),
  addressId int(11),
  clientName varchar(255),
  contactName varchar(255),
  contactEmail varchar(255),
  studyId int(11),
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
  validatedByUa boolean NOT NULL,
  validatedByUaDate date,
  validatedByUaMemberId int(11),
  validatedByPerf boolean NOT NULL,
  validatedByPerfDate date,
  validatedByPerfMemberId int(11),
  PRIMARY KEY (id),
  CONSTRAINT fk_facture_address FOREIGN KEY (addressId) REFERENCES core_address(id),
  CONSTRAINT fk_facture_study FOREIGN KEY (studyId) REFERENCES ua_study(id) ON DELETE SET NULL,
  CONSTRAINT fk_facture_facture_type FOREIGN KEY (typeId) REFERENCES treso_facture_type(id),
  CONSTRAINT fk_facture_createdBy_member FOREIGN KEY (createdById) REFERENCES core_member(id) ON DELETE SET NULL,
  CONSTRAINT fk_facture_validatedByUa_member FOREIGN KEY (validatedByUaMemberId) REFERENCES core_member(id) ON DELETE SET NULL,
  CONSTRAINT fk_facture_validatedByPerf_member FOREIGN KEY (validatedByPerfMemberId) REFERENCES core_member(id) ON DELETE SET NULL
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
  factureId int(11),
  factureDocumentTypeId int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_treso_document_core_document FOREIGN KEY (id) REFERENCES core_document(id),
  CONSTRAINT `fk_treso_facture_document_treso_facture` FOREIGN KEY (factureId) REFERENCES treso_facture(`id`) ON DELETE SET NULL,
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
  phoneNumber varchar(255) NOT NULL,
  outYear int NOT NULL,
  nationalityId int(11) NOT NULL,
  wantedPoleId int(11) NOT NULL,
  addressId int(11) NOT NULL,
  hasPaid boolean DEFAULT FALSE,
  droitImage boolean DEFAULT FALSE,
  createdDate date  NOT NULL,
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

DROP TABLE IF EXISTS sg_consultant_inscription;
CREATE TABLE sg_consultant_inscription (
  id int(11) NOT NULL AUTO_INCREMENT,
  firstName varchar(255) NOT NULL,
  lastName varchar(255) NOT NULL,
  birthday date NOT NULL,
  genderId int(11) NOT NULL,
  departmentId int(11) NOT NULL,
  email varchar(255) NOT NULL,
  phoneNumber varchar(255) DEFAULT NULL,
  outYear int NOT NULL,
  nationalityId int(11) NOT NULL,
  addressId int(11) NOT NULL,
  socialSecurityNumber varchar(255) NOT NULL,  
  droitImage boolean DEFAULT FALSE,
  isApprentice boolean NOT NULL,
  createdDate date  NOT NULL,
  documentIdentity varchar(255) NOT NULL,
  documentScolaryCertificate varchar(255) NOT NULL,
  documentRIB varchar(255) NOT NULL,
  documentVitaleCard varchar(255) NOT NULL,
  documentResidencePermit varchar(255) DEFAULT NULL,
  documentCVEC varchar(255) DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_sg_consultant_inscription_department FOREIGN KEY (departmentId) REFERENCES core_department(id),
  CONSTRAINT fk_sg_consultant_inscription_nationality FOREIGN KEY (nationalityId) REFERENCES core_country(id),
  CONSTRAINT fk_sg_consultant_inscription_gender FOREIGN KEY (genderId) REFERENCES core_gender(id),
  CONSTRAINT fk_sg_consultant_inscription_address FOREIGN KEY (addressId) REFERENCES core_address(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
DROP TABLE IF EXISTS sg_member_inscription_document;
CREATE TABLE sg_member_inscription_document (
    id int(11) NOT NULL,
    memberInscriptionId int(11),
    memberInscriptionDocumentTypeId int(11) NOT NULL,
    memberId int(11),
    PRIMARY KEY (id),
    CONSTRAINT fk_sg_member_insc_document_core_document FOREIGN KEY (id) REFERENCES core_document(id) ON DELETE CASCADE,
    CONSTRAINT fk_sg_member_insc_document_sg_member_inscription FOREIGN KEY (memberInscriptionId) REFERENCES sg_member_inscription(id),
    CONSTRAINT fk_sg_member_insc_document_member_insc_document_type FOREIGN KEY (memberInscriptionDocumentTypeId) REFERENCES sg_member_inscription_document_type(id),
    CONSTRAINT fk_sg_member_insc_document_core_member FOREIGN KEY (memberId) REFERENCES core_member(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS treso_payment_slip;
CREATE TABLE treso_payment_slip (
    id int(11) NOT NULL AUTO_INCREMENT,
    missionRecapNumber varchar(32),
    consultantName varchar(255),
    consultantSocialSecurityNumber varchar(255),
    addressId int(11),
    email varchar(255),
    studyId int(11),
    clientName varchar(255),
    projectLead varchar(255),
    isTotalJeh boolean,
    isStudyPaid boolean,
    amountDescription varchar(2048),
    createdDate date,
    creatorId int(11),
    validatedByUa boolean,
    validatedByUaDate date,
    uaValidatorId int(11),
    validatedByPerf boolean,
    validatedByPerfDate date,
    perfValidatorId int(11),
    PRIMARY KEY (id),
    CONSTRAINT fk_payment_slip_address FOREIGN KEY (addressId) REFERENCES  core_address(id),
    CONSTRAINT fk_payment_slip_study FOREIGN KEY (studyId) REFERENCES  ua_study(id) ON DELETE SET NULL,
    CONSTRAINT fk_payment_slip_creator FOREIGN KEY (creatorId) REFERENCES  core_member(id) ON DELETE SET NULL,
    CONSTRAINT fk_payment_slip_ua_validator FOREIGN KEY (uaValidatorId) REFERENCES  core_member(id) ON DELETE SET NULL,
    CONSTRAINT fk_payment_slip_perf_validator FOREIGN KEY (perfValidatorId) REFERENCES  core_member(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET AUTOCOMMIT = 1;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;

INSERT INTO `core_pole` (id, label, name) VALUES
  (1, 'Com', 'Communication'),
  (4, 'Perf', 'Performance'),
  (5, 'Prez', 'Présidence'),
  (6, 'RH', 'Ressources Humaines'),
  (7, 'SI', 'Systèmes d''Information'),
  (8, 'Treso', 'Trésorerie'),
  (9, 'UA', 'Unité d''Affaires'),
  (10, 'Tech', 'Technique'),
  (11, 'MKT', 'Marketing'),
  (12, 'GWT', 'Growth');

INSERT INTO `core_position` (id, label, poleId) VALUES
  (1, 'Auditeur orga', null),
  (2, 'Auditeur treso', null),
  (3, 'Chargé d''affaires', 9),
  (5, 'Comptable', 8),
  (7, 'Junior com', 1),
  (9, 'Junior qualité', 4),
  (10, 'Junior SI', 7),
  (11, 'Junior UA', 9),
  (12, 'Membre CNJE', null),
  (13, 'Membre d''Honneur', null),
  (14, 'Président', 5),
  (15, 'Responsable BU', 9),
  (16, 'Responsable com', 1),
  (18, 'Responsable qualité', 4),
  (19, 'Responsable RH', 6),
  (20, 'Responsable SI', 7),
  (21, 'Responsable d''UA', 9),
  (22, 'Secrétaire général', 6),
  (23, 'Trésorier', 8),
  (24, 'Vice-Président', 5),
  (25, 'Vice-Trésorier', 8),
  (26, 'Autre', null),
  (27, 'Responsable Technique', 10),
  (28, 'Junior Technique', 10),
  (29, 'Membre Marketing', 11),
  (30, 'Membre Growth', 12)
  (31, 'Responsable Marketing', 11),
  (32, 'Responsable Growth', 12);

INSERT INTO core_department (id, label, name) VALUES
  (1, 'BB', 'Biochimie et Biotechnologies'),
  (2, 'BIM', 'BioInformatique et Modélisation'),
  (3, 'GCU', 'Génie civil et urbanisme'),
  (4, 'GE', 'Génie électrique'),
  (5, 'GEN', 'Génie énergétique et environnement'),
  (6, 'GI', 'Génie Industriel'),
  (7, 'GM', 'Génie mécanique'),
  (10, 'IF', 'Informatique'),
  (11, 'FIMI', 'Formation initiale aux métiers d''ingénieur'),
  (12, 'SGM', 'Science et Génie des Matériaux'),
  (13, 'TC', 'Télécommunications, Services et Usages');

INSERT INTO `core_country` (id, label, isEu) VALUES
  (1, 'Afghanistan', false), (2, 'Afrique du Sud', false), (3, 'Albanie', false), (4, 'Algérie', false), (5, 'Allemagne', true), (6, 'Andorre', false),
  (7, 'Angola', false), (8, 'Antigua-et-Barbuda', false), (9, 'Arabie saoudite', false), (10, 'Argentine', false), (11, 'Arménie', false),
  (12, 'Australie', false), (13, 'Autriche', true), (14, 'Azerbaïdjan', false), (15, 'Bahamas', false), (16, 'Bahreïn', false), (17, 'Bangladesh', false),
  (18, 'Barbade', false), (19, 'Belgique', true), (20, 'Belize', false), (21, 'Bénin', false), (22, 'Bhoutan', false), (23, 'Biélorussie', false),
  (24, 'Birmanie', false), (25, 'Bolivie', false), (26, 'Bosnie-Herzégovine', false), (27, 'Botswana', false), (28, 'Brésil', false), (29, 'Brunei', false),
  (30, 'Bulgarie', true), (31, 'Burkina Faso', false), (32, 'Burundi', false), (33, 'Cambodge', false), (34, 'Cameroun', false), (35, 'Canada', false),
  (36, 'Cap-Vert', false), (37, 'Chili', false), (38, 'Chine', false), (39, 'Chypre', true), (40, 'Colombie', false), (41, 'Comores', false),
  (42, 'Corée du Nord', false), (43, 'Corée du Sud', false), (44, 'Costa Rica', false), (45, 'Côte d''Ivoire', false), (46, 'Crete', false),
  (47, 'Croatie', true), (48, 'Cuba', false), (49, 'Danemark', true), (50, 'Djibouti', false), (51, 'Dominique', false), (52, 'Égypte', false),
  (53, 'Émirats arabes unis', false), (54, 'Équateur', false), (55, 'Érythrée', false), (56, 'Espagne', true), (57, 'Estonie', true), (58, 'États-Unis', false),
  (59, 'Éthiopie', false), (60, 'Fidji', false), (61, 'Finlande', true), (62, 'France', true), (63, 'Gabon', false), (64, 'Gambie', false), (65, 'Géorgie', false),
  (66, 'Ghana', false), (67, 'Grèce', true), (68, 'Grenade', false), (69, 'Guatemala', false), (70, 'Guinée', false), (71, 'Guinée équatoriale', false),
  (72, 'Guinée-Bissau', false), (73, 'Guyana', false), (74, 'Haïti', false), (75, 'Honduras', false), (76, 'Hongrie', true), (77, 'Inde', false),
  (78, 'Indonésie', false), (79, 'Irak', false), (80, 'Iran', false), (81, 'Irlande', true), (82, 'Islande', false), (83, 'Israël', false), (84, 'Italie', true),
  (85, 'Jamaïque', false), (86, 'Japon', false), (87, 'Jordanie', false), (88, 'Kazakhstan', false), (89, 'Kenya', false), (90, 'Kirghizistan', false),
  (91, 'Kiribati', false), (92, 'Koweït', false), (93, 'Laos', false), (94, 'Lesotho', false), (95, 'Lettonie', true), (96, 'Liban', false), (97, 'Liberia', false),
  (98, 'Libye', false), (99, 'Liechtenstein', false), (100, 'Lituanie', true), (101, 'Luxembourg', true), (102, 'Macédoine', false), (103, 'Madagascar', false),
  (104, 'Malaisie', false), (105, 'Malawi', false), (106, 'Maldives', false), (107, 'Mali', false), (108, 'Malte', true), (109, 'Maroc', false),
  (110, 'Marshall', false), (111, 'Maurice', false), (112, 'Mauritanie', false), (113, 'Mexique', false), (114, 'Micronésie', false), (115, 'Moldavie', false),
  (116, 'Monaco', false), (117, 'Mongolie', false), (118, 'Monténégro', false), (119, 'Mozambique', false), (120, 'Namibie', false), (121, 'Nauru', false),
  (122, 'Népal', false), (123, 'Nicaragua', false), (124, 'Niger', false), (125, 'Nigeria', false), (126, 'Norvège', false), (127, 'Nouvelle-Zélande', false),
  (128, 'Oman', false), (129, 'Ouganda', false), (130, 'Ouzbékistan', false), (131, 'Pakistan', false), (132, 'Palaos', false), (133, 'Panama', false),
  (134, 'Papouasie-Nouvelle-Guinée', false), (135, 'Paraguay', false), (136, 'Pays-Bas', true), (137, 'Pérou', false), (138, 'Philippines', false),
  (139, 'Pologne', true), (140, 'Portugal', true), (141, 'Qatar', false), (142, 'République centrafricaine', false),
  (143, 'République démocratique du Congo', false), (144, 'République dominicaine', false), (145, 'République du Congo', false),
  (146, 'République tchèque', true), (147, 'Roumanie', true), (148, 'Royaume-Uni', true), (149, 'Russie', false), (150, 'Rwanda', false),
  (151, 'Sahara Occidental', false), (152, 'Saint-Christophe-et-Niévès', false), (153, 'Saint-Marin', false),
  (154, 'Saint-Vincent-et-les-Grenadines', false), (155, 'Sainte-Lucie', false), (156, 'Salomon', false), (157, 'Salvador', false), (158, 'Samoa', false),
  (159, 'Sao Tomé-et-Principe', false), (160, 'Sénégal', false), (161, 'Serbie', false), (162, 'Seychelles', false), (163, 'Sierra Leone', false),
  (164, 'Singapour', false), (165, 'Slovaquie', true), (166, 'Slovénie', true), (167, 'Somalie', false), (168, 'Soudan', false), (169, 'Soudan du Sud', false),
  (170, 'Sri Lanka', false), (171, 'Suède', true), (172, 'Suisse', false), (173, 'Suriname', false), (174, 'Swaziland', false), (175, 'Syrie', false),
  (176, 'Tadjikistan', false), (177, 'Tanzanie', false), (178, 'Tchad', false), (179, 'Thaïlande', false), (180, 'Timor oriental', false), (181, 'Togo', false),
  (182, 'Tonga', false), (183, 'Trinité-et-Tobago', false), (184, 'Tunisie', false), (185, 'Turkménistan', false), (186, 'Turquie', false),
  (187, 'Tuvalu', false), (188, 'Ukraine', false), (189, 'Uruguay', false), (190, 'Vanuatu', false), (191, 'Vatican', false), (192, 'Venezuela', false),
  (193, 'Viêt Nam', false), (194, 'Yémen', false), (195, 'Zambie', false), (196, 'Zimbabwe', false);

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

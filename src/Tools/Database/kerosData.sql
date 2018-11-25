SET AUTOCOMMIT = 0;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;

/* Passwords are, in order : hunter11 - hunter12 - hunter13 */
TRUNCATE TABLE core_user;
INSERT INTO core_user (id, username, password, expiresAt) VALUES
  (1, 'username', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')),
  (2, 'mcool', '$2y$10$fWnWMRQKKWInygzk.FNIP.BsnTp8e8XvDwj5YdGgVuIFXsz/XvVgm' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')),
  (3, 'lswollo', '$2y$10$9R4lfhp18.iVzsP8amDL5e7eumi48DmPPkoa5YLAm/thAZWIHaOtW' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'));

TRUNCATE TABLE core_address;
INSERT INTO core_address (id, line1, line2, postalCode, city, countryId) VALUES
  (1, '13 Rue du renard', null, '69100', 'lyon', 1), # member 1
  (2, '11 Baker street', 'appt 501', '6930A', 'dublin', 2), # member 2
  (3, '11 ETIC street', 'bat. b', '91002', 'paris', 1), # member 3
  (4, '11 Backbeat street', 'bat. a', '91004', 'djibouti', 3), # firm 1
  (5, '17 Watcha ave', 'porte 5', '674A4', 'Leicester', 40); # firm 2

TRUNCATE TABLE core_member;
INSERT INTO core_member (id, genderId, firstName, lastName, birthday, telephone, email, addressId, schoolYear, departmentId) VALUES
  (1, 1, 'Conor', 'Breeze', STR_TO_DATE('1975-12-25', '%Y-%m-%d'), '+332541254', 'fake.mail@fake.com', 2, 3, 1),
  (2, 1, 'Marah', 'Cool', STR_TO_DATE('1976-10-27', '%Y-%m-%d'), '+332541541', 'fake.mail2@fake.com', 1, 3, 1),
  (3, 1, 'Laurence', 'Teinturière', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.mail3@fake.com', 3, 5, 2);

TRUNCATE TABLE core_member_position;
INSERT INTO core_member_position (memberId, positionId) VALUES
  (1, 3),
  (2, 3),
  (3, 1),
  (3, 2),
  (3, 3);

TRUNCATE TABLE ua_firm;
INSERT INTO ua_firm (id, siret, name, addressId, typeId) VALUES
  (1, '215437645', 'Google', 4, 3),
  (2, '471245896', 'JetBrains', 5, 1);

TRUNCATE TABLE ua_contact;
INSERT INTO `ua_contact` (`id`, `firstName`, `lastName`, `genderId`, `firmId`, `email`, `telephone`, `cellphone`, `position`, `notes`, `old`) VALUES
  (1, 'Alexandre', 'Lang', 1, 2, 'alexandre.lang@etic.com', NULL, '0033175985495', 'Directeur Marketing', 'RAS', 1),
  (2, 'Conor', 'Ryan', 1, 1, 'conor.ryan@etic.com', '0033666666666', '0033666666666', 'Architecte Réseau', null, 1),
  (3, 'Laurent', 'Tainturier', 1, 1, 'laurent.tainturier@etic.com', '0033333333333', '0033222222222', 'Chercheur', 'Part de la boite bientôt', 1),
  (4, 'Marah', 'Galy Adam', 1, 1, 'marah.galy@etic-insa.com', '0033646786532', NULL, NULL, NULL, 0);

TRUNCATE TABLE ua_study;
INSERT INTO `ua_study` (`id`, `projectNumber`, `name`, `description`, `fieldId`, `provenanceId`, `statusId`, `signDate`, `endDate`, `managementFee`, `realizationFee`, `rebilledFee`, `ecoparticipationFee`, `outsourcingFee`, `archivedDate`, `firmId`) VALUES
  (1, 12, 'Développement IDE', 'Développement d\'un IDE pour utilisation interne', 1, 1, 2, '2018-11-10', '2018-11-10', 12000000, 123, 12345, 12, 12324454, '2018-11-10', 1),
  (2, 12, 'Tests d\'acidité dans le Rhône', 'Créateur de IDE', 1, 1, 2, '2018-11-10', '2018-11-10', 12000000, 123, 12345, 12, 12324454, '2018-11-10', 2);

TRUNCATE TABLE ua_study_consultant;
INSERT INTO `ua_study_consultant` (`memberId`, `studyId`) VALUES
  (2, 2),
  (1, 2);

TRUNCATE TABLE ua_study_leader;
INSERT INTO `ua_study_leader` (`memberId`, `studyId`) VALUES
  (3, 2);

TRUNCATE TABLE ua_study_qualityManager;
INSERT INTO `ua_study_qualityManager` (`memberId`, `studyId`) VALUES
  (2, 2);

TRUNCATE TABLE ua_study_contact;
INSERT INTO `ua_study_contact` (`contactId`, `studyId`) VALUES
  (1, 2),
  (2, 2);

COMMIT;
SET AUTOCOMMIT = 0;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;

/* Passwords are on the right */
TRUNCATE TABLE core_user;
INSERT INTO core_user (id, username, password, expiresAt) VALUES
  (1, 'username', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (2, 'mcool', '$2y$10$fWnWMRQKKWInygzk.FNIP.BsnTp8e8XvDwj5YdGgVuIFXsz/XvVgm' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #hunter11
  (3, 'lswollo', '$2y$10$9R4lfhp18.iVzsP8amDL5e7eumi48DmPPkoa5YLAm/thAZWIHaOtW' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #hunter12
  (4, 'qualqual', '$2y$10$9R4lfhp18.iVzsP8amDL5e7eumi48DmPPkoa5YLAm/thAZWIHaOtW' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #hunter13
  (5, 'lung', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (6, 'user6', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (7, 'user7', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (8, 'user8', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (9, 'user9', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (10, 'user10', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (11, 'user11', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (12, 'user12', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (13, 'user13', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (14, 'user14', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (15, 'user15', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (16, 'user16', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (17, 'user17', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (18, 'user18', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (19, 'user19', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (20, 'user20', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (21, 'user21', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (22, 'user22', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (23, 'user23', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (24, 'user24', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (25, 'user25', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (26, 'user26', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (27, 'user27', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')); #password

TRUNCATE TABLE core_address;
INSERT INTO core_address (id, line1, line2, postalCode, city, countryId) VALUES
  (1, '13 Rue du renard', null, '69100', 'lyon', 1), # member inscription
  (2, '11 Baker street', 'appt 501', '6930A', 'dublin', 2), # member 1
  (3, '11 ETIC street', 'bat. b', '91002', 'paris', 1), # member 3
  (4, '11 Backbeat street', 'bat. a', '91004', 'djibouti', 3), # firm 1
  (5, '17 Watcha ave', 'porte 5', '674A4', 'Leicester', 40), # firm 2
  (6, '17 Beat', 'Meat', '674A4', 'Paris', 40), # member 4
  (7, '60 Gold street', 'bat. j', '69100','Villeurbanne',1), #consultant 5
  (8, '20 avenue Albert Einstein', 'bat. g', '50100', 'Cherbourg',1); #consultant 2
  (9, 'rue test 9', 'Meat', '674A4', 'Paris', 40), # member 7
  (10, 'rue test 10', 'Meat', '674A4', 'Paris', 40), # member 8
  (11, 'rue test 11', 'Meat', '674A4', 'Paris', 40), # member 9
  (12, 'rue test 12', 'Meat', '674A4', 'Paris', 40), # member 10
  (13, 'rue test 13', 'Meat', '674A4', 'Paris', 40), # member 11
  (14, 'rue test 14', 'Meat', '674A4', 'Paris', 40), # member 12
  (15, 'rue test 15', 'Meat', '674A4', 'Paris', 40), # member 13
  (16, 'rue test 16', 'Meat', '674A4', 'Paris', 40), # member 14
  (17, 'rue test 17', 'Meat', '674A4', 'Paris', 40), # member 15
  (18, 'rue test 18', 'Meat', '674A4', 'Paris', 40), # member 16
  (19, 'rue test 19', 'Meat', '674A4', 'Paris', 40), # member 17
  (20, 'rue test 20', 'Meat', '674A4', 'Paris', 40), # member 18
  (21, 'rue test 21', 'Meat', '674A4', 'Paris', 40), # member 19
  (22, 'rue test 22', 'Meat', '674A4', 'Paris', 40), # member 20
  (23, 'rue test 23', 'Meat', '674A4', 'Paris', 40), # member 21
  (24, 'rue test 24', 'Meat', '674A4', 'Paris', 40), # member 22
  (25, 'rue test 25', 'Meat', '674A4', 'Paris', 40), # member 23
  (26, 'rue test 26', 'Meat', '674A4', 'Paris', 40), # member 24
  (27, 'rue test 27', 'Meat', '674A4', 'Paris', 40), # member 25
  (28, 'rue test 28', 'Meat', '674A4', 'Paris', 40), # member 26
  (29, 'rue test 29', 'Meat', '674A4', 'Paris', 40); # member 27

TRUNCATE TABLE core_ticket;
INSERT INTO core_ticket (id, userId, title, message, type, status) VALUES
  (1, 1, 'Impossible de changer son mot de passe', 'Bonjour, je narrive pas à changer mon mot de passe', 'Problème de compte', 'En cours'); # ticket 1

TRUNCATE TABLE core_member;
INSERT INTO core_member (id, genderId, firstName, lastName, birthday, telephone, email, addressId, schoolYear, departmentId, company, profilePicture) VALUES
  (1, 1, 'Conor', 'Breeze', STR_TO_DATE('1975-12-25', '%Y-%m-%d'), '+332541254', 'fake.mail@fake.com', 2, 3, 1, 'Google', 'http://picture.png'),
  (3, 1, 'Laurence', 'Tainturière', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.mail3@fake.com', 3, 5, 2, NULL, NULL),
  (4, 3, 'Stéphane4', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly4@fake.com', 6, 3, 4, NULL, NULL),
  (6, 3, 'Stéphane6', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly6@fake.com', 8, 3, 4, NULL, NULL),
  (7, 3, 'Stéphane7', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly7@fake.com', 9, 3, 4, NULL, NULL),
  (8, 3, 'Stéphane8', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly8@fake.com', 10, 3, 4, NULL, NULL),
  (9, 3, 'Stéphane9', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly9@fake.com', 11, 3, 4, NULL, NULL),
  (10, 3, 'Stéphane10', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly10@fake.com', 12, 3, 4, NULL, NULL),
  (11, 3, 'Stéphane11', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly11@fake.com', 13, 3, 4, NULL, NULL),
  (12, 3, 'Stéphane12', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly12@fake.com', 14, 3, 4, NULL, NULL),
  (13, 3, 'Stéphane13', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly13@fake.com', 15, 3, 4, NULL, NULL),
  (14, 3, 'Stéphane14', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly14@fake.com', 16, 3, 4, NULL, NULL),
  (15, 3, 'Stéphane15', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly15@fake.com', 17, 3, 4, NULL, NULL),
  (16, 3, 'Stéphane16', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly16@fake.com', 18, 3, 4, NULL, NULL),
  (17, 3, 'Stéphane17', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly17@fake.com', 19, 3, 4, NULL, NULL),
  (18, 3, 'Stéphane18', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly18@fake.com', 20, 3, 4, NULL, NULL),
  (19, 3, 'Stéphane19', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly19@fake.com', 21, 3, 4, NULL, NULL),
  (20, 3, 'Stéphane20', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly20@fake.com', 22, 3, 4, NULL, NULL),
  (21, 3, 'Stéphane21', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly21@fake.com', 23, 3, 4, NULL, NULL),
  (22, 3, 'Stéphane22', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly22@fake.com', 24, 3, 4, NULL, NULL),
  (23, 3, 'Stéphane23', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly23@fake.com', 25, 3, 4, NULL, NULL),
  (24, 3, 'Stéphane24', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly24@fake.com', 26, 3, 4, NULL, NULL),
  (25, 3, 'Stéphane25', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly25@fake.com', 27, 3, 4, NULL, NULL),
  (26, 3, 'Stéphane26', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly26@fake.com', 28, 3, 4, NULL, NULL),
  (27, 3, 'Stéphane27', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly27@fake.com', 28, 3, 4, NULL, NULL);


TRUNCATE TABLE core_consultant;
INSERT INTO core_consultant (id, genderId, firstName, lastName, birthday, telephone, email, addressId, schoolYear, departmentId, company, profilePicture) VALUES
  (2, 1, 'Marah', 'Cool', STR_TO_DATE('1976-10-27', '%Y-%m-%d'), '+332541541', 'fake.mail2@fake.com', 8, 3, 1, 'Amazon', NULL),
  (5, 3, 'Louis', 'Ung', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.mail3@fake.com', 7, 3, 4, NULL, NULL);

TRUNCATE TABLE core_member_position;
INSERT INTO core_member_position (id, memberId, positionId, isBoard, year) VALUES
  (1, 1, 3, TRUE, 2018),
  (3, 3, 1, TRUE, 2018),
  (4, 3, 2, TRUE, 1990),
  (5, 3, 3, FALSE, 2015),
  (6, 1, 6, FALSE, 2016),
  (8, 4, 9, FALSE, 2015);

TRUNCATE TABLE ua_firm;
INSERT INTO ua_firm (id, siret, name, addressId, typeId, mainContact) VALUES
  (1, '215437645', 'Google', 4, 3, 4),
  (2, '471245896', 'JetBrains', 5, 1, null);

TRUNCATE TABLE ua_contact;
INSERT INTO `ua_contact` (`id`, `firstName`, `lastName`, `genderId`, `firmId`, `email`, `telephone`, `cellphone`, `position`, `notes`, `old`) VALUES
  (1, 'Alexandre', 'Lang', 1, 2, 'alexandre.lang@etic.com', NULL, '0033175985495', 'Directeur Marketing', 'RAS', 1),
  (2, 'Conor', 'Ryan', 1, 1, 'conor.ryan@etic.com', '0033666666666', '0033666666666', 'Architecte Réseau', null, 1),
  (3, 'Laurent', 'Tainturier', 1, 1, 'laurent.tainturier@etic.com', '0033333333333', '0033222222222', 'Chercheur', 'Part de la boite bientôt', 1),
  (4, 'Marah', 'Galy Adam', 1, 1, 'marah.galy@etic-insa.com', '0033646786532', NULL, NULL, NULL, 0);

TRUNCATE TABLE ua_study;
INSERT INTO `ua_study` (`id`, `name`, `description`, `fieldId`, `provenanceId`, `statusId`, `signDate`, `endDate`, `managementFee`, `realizationFee`, `rebilledFee`, `ecoparticipationFee`, `outsourcingFee`, `archivedDate`, `firmId`,`mainLeader`, `mainQualityManager`, `mainConsultant`) VALUES
  (1, 'Développement IDE', 'Développement d''un IDE pour utilisation interne', 1, 1, 2, '2018-11-10', '2018-11-10', 12000000, 123, 12345, 12, 12324454, '2018-11-10', 1, null, null, null),
  (2, 'Tests d''acidité dans le Rhône', 'Créateur de IDE', 1, 1, 2, '2018-11-10', '2018-11-10', 12000000, 123, 12345, 12, 12324454, '2018-11-10', 2, 4, 4, 2),
  (3, 'Développement app mobile', 'Développement d''une app pour scanner des images', 1, 1, 2, '2018-11-10', '2018-11-10', 12000000, 123, 12345, 12, 12324454, '2018-11-10', 1, null, null, null);


TRUNCATE TABLE ua_study_consultant;
INSERT INTO `ua_study_consultant` (`consultantId`, `studyId`) VALUES
  (2, 2),
  (5, 1);

TRUNCATE TABLE ua_study_leader;
INSERT INTO `ua_study_leader` (`memberId`, `studyId`) VALUES
  (3, 2),
  (1, 2),
  (4, 2);

TRUNCATE TABLE ua_study_qualityManager;
INSERT INTO `ua_study_qualityManager` (`memberId`, `studyId`) VALUES
  (3, 2),
  (4, 2);

TRUNCATE TABLE ua_study_contact;
INSERT INTO `ua_study_contact` (`contactId`, `studyId`) VALUES
  (1, 2),
  (2, 2),
  (2, 1);

TRUNCATE TABLE ua_study_document_type;
INSERT INTO ua_study_document_type(id, `location`, istemplatable, oneConsultant) VALUES
  (1, 'document.docx', 1, 0),
  (2, 'acompte.docx', 1, 1),
  (3, 'undoc.docx', 0, 0);

TRUNCATE TABLE core_document;
INSERT INTO core_document(id, uploadDate, location, discr) VALUES
  (1, STR_TO_DATE('2019/2/14 10:40:10', '%Y/%m/%d %h:%i:%s'), 'study_1/document_2/acompte.docx', 'ua_study_document'),
  (2, STR_TO_DATE('2018/12/16 10:40:10', '%Y/%m/%d %h:%i:%s'), 'study_1/document_3/FE.docx', 'ua_study_document'),
  (3, STR_TO_DATE('2018/12/16 10:40:10', '%Y/%m/%d %h:%i:%s'), 'facture_1/document_3/proformat.docx', 'treso_facture_document'),
  (4, STR_TO_DATE('2019/04/19 10:40:10', '%Y/%m/%d %h:%i:%s'), 'facture_1/document_4/solde.docx', 'treso_facture_document');

TRUNCATE TABLE ua_study_document;
INSERT INTO `ua_study_document`(id, studyId, studyDocumentTypeId) VALUES
  (1, 1, 2),
  (2, 1, 3);

TRUNCATE TABLE treso_facture;
INSERT INTO treso_facture (id, numero, addressId, clientName , contactName, contactEmail, studyId, typeId, amountDescription,
                            subject, agreementSignDate, amountHT, taxPercentage, dueDate , additionalInformation, createdDate, createdById,
                            validatedByUa, validatedByUaDate, validatedByUaMemberId, validatedByPerf, validatedByPerfDate,validatedByPerfMemberId) VALUES
(1,'23023234', 6, 'Google', 'James Bond', 'mail@exemple.fr', 1, 1, 'Trois Euros', 'Sujet du projet', '2018-11-10', 234.34, 345.45, '2018-1-10',
  'info supp', '2018-11-4', 3, true, '2017-11-10', 3, true, '2019-11-10', 3),
(2,'23023235', 6, 'Milka', 'Alexandre Lang', 'fauxmail@exemple.fr', 1, 2, 'deux cents trente quatre euros et trente quatre centimes', 'Sujet du projet', '2018-11-10', 234.34, 20.0, '2018-1-10',
  'info supp', '2018-11-23', 3, false, null, null, false, null, null),
(3,'23023235', 6, 'Milka', 'Alexandre Lang', 'fauxmail@exemple.fr', 1, 3, 'deux cents trente quatre euros et trente quatre centimes', 'Sujet du projet', '2018-11-10', 234.34, 20.0, '2018-1-10',
 'info supp', '2018-11-3', 3, false, null, null, false, null, null),
(4,'23023235', 6, 'Milka', 'Alexandre Lang', 'fauxmail@exemple.fr', 1, 4, 'deux cents trente quatre euros et trente quatre centimes', 'Sujet du projet', '2018-11-10', 234.34, 20.0, '2018-1-10',
 'info supp', '2018-11-1', 3, false, null, null, false, null, null);

TRUNCATE TABLE treso_facture_document_type;
INSERT INTO treso_facture_document_type(id, `name`, location, istemplatable, factureTypeId) VALUES
  (4, 'Template Solde', 'Template FE de solde.docx', 1, 4),
  (3, 'Tamplte facture intermédiaire', 'Template FE intermédiaire.docx', 1, 3),
  (2, 'Template facture acompte', 'Template FE acompte.docx', 1, 2),
  (1, 'Template pro-forma','Template FE pro-forma.docx', 1, 1);

TRUNCATE TABLE treso_facture_document;
INSERT INTO treso_facture_document(id, factureId, factureDocumentTypeId) VALUES
  (3, 1, 2),
  (4, 1, 1);

TRUNCATE TABLE sg_member_inscription;
INSERT INTO sg_member_inscription (id, firstName, lastName, genderId, birthday, departmentId, email, phoneNumber, outYear, nationalityId, wantedPoleId, addressId, hasPaid) VALUES
(1, 'Bruce', 'Wayne', 1, STR_TO_DATE('2000/2/14', '%Y/%m/%d'), 3, 'bruce.wayne@batman.com', '0033123456789', 2021, 42, 2, 1, false),
(2, 'Clark', 'Kent', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 2, 'clark.kent@dailyplanete.com', '0033123456789', 2024, 69, 4, 1, true);

COMMIT;
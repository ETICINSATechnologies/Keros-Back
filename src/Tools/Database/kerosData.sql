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
  (6, 'superuser', '$2y$10$fVPB3SLO54Ng5DjqUEr8/OSwOtOMy0gH4DiEqjvxXqRVdcM8hc7Du' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #superuser
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
  (27, 'user27', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')), #password
  (28, 'user28', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')); #password

TRUNCATE TABLE core_address;
INSERT INTO core_address (id, line1, line2, postalCode, city, countryId) VALUES
  (1, '13 Rue du renard', null, '69100', 'lyon', 1), # member inscription
  (2, '11 Baker street', 'appt 501', '6930A', 'dublin', 2), # member 1
  (3, '11 ETIC street', 'bat. b', '91002', 'paris', 1), # member 3
  (4, '11 Backbeat street', 'bat. a', '91004', 'djibouti', 3), # firm 1
  (5, '17 Watcha ave', 'porte 5', '674A4', 'Leicester', 40), # firm 2
  (6, '17 Beat', 'Meat', '674A4', 'Paris', 40), # member 4
  (7, '60 Gold street', 'bat. j', '69100','Villeurbanne',1), #consultant 5
  (8, '20 avenue Albert Einstein', 'bat. g', '50100', 'Cherbourg',1), #consultant 2
  (9, '11 supeRU', 'bis', '27277', 'SuperVille', 40), # member 6 - superuser
  (10, 'rue test 10', 'Meat', '674A4', 'Paris', 40), # member 7
  (11, 'rue test 11', 'Meat', '674A4', 'Paris', 40), # member 8
  (12, 'rue test 12', 'Meat', '674A4', 'Paris', 40), # member 9
  (13, 'rue test 13', 'Meat', '674A4', 'Paris', 40), # member 10
  (14, 'rue test 14', 'Meat', '674A4', 'Paris', 40), # member 11
  (15, 'rue test 15', 'Meat', '674A4', 'Paris', 40), # member 12
  (16, 'rue test 16', 'Meat', '674A4', 'Paris', 40), # member 13
  (17, 'rue test 17', 'Meat', '674A4', 'Paris', 40), # member 14
  (18, 'rue test 18', 'Meat', '674A4', 'Paris', 40), # member 15
  (19, 'rue test 19', 'Meat', '674A4', 'Paris', 40), # member 16
  (20, 'rue test 20', 'Meat', '674A4', 'Paris', 40), # member 17
  (21, 'rue test 21', 'Meat', '674A4', 'Paris', 40), # member 18
  (22, 'rue test 22', 'Meat', '674A4', 'Paris', 40), # member 19
  (23, 'rue test 23', 'Meat', '674A4', 'Paris', 40), # member 20
  (24, 'rue test 24', 'Meat', '674A4', 'Paris', 40), # member 21
  (25, 'rue test 25', 'Meat', '674A4', 'Paris', 40), # member 22
  (26, 'rue test 26', 'Meat', '674A4', 'Paris', 40), # member 23
  (27, 'rue test 27', 'Meat', '674A4', 'Paris', 40), # member 24
  (28, 'rue test 28', 'Meat', '674A4', 'Paris', 40), # member 25
  (29, 'rue test 29', 'Meat', '674A4', 'Paris', 40), # member 26
  (30, 'rue test 30', 'Meat', '674A4', 'Paris', 40), # member 27
  (31, 'rue test 31', 'Meat', '674A4', 'Paris', 40), # member 28
  (32, 'rue firm 3', 'Meat', '674A4', 'Paris', 40), # firm 3
  (33, 'rue firm 4', 'Meat', '674A4', 'Paris', 40), # firm 4
  (34, 'rue firm 5', 'Meat', '674A4', 'Paris', 40), # firm 5
  (35, 'rue firm 6', 'Meat', '674A4', 'Paris', 40), # firm 6
  (36, 'rue firm 7', 'Meat', '674A4', 'Paris', 40), # firm 7
  (37, 'rue firm 8', 'Meat', '674A4', 'Paris', 40), # firm 8
  (38, 'rue firm 9', 'Meat', '674A4', 'Paris', 40), # firm 9
  (39, 'rue firm 10', 'Meat', '674A4', 'Paris', 40), # firm 10
  (40, 'rue firm 11', 'Meat', '674A4', 'Paris', 40), # firm 11
  (41, 'rue firm 12', 'Meat', '674A4', 'Paris', 40), # firm 12
  (42, 'rue firm 13', 'Meat', '674A4', 'Paris', 40), # firm 13
  (43, 'rue firm 14', 'Meat', '674A4', 'Paris', 40), # firm 14
  (44, 'rue firm 15', 'Meat', '674A4', 'Paris', 40), # firm 15
  (45, 'rue firm 16', 'Meat', '674A4', 'Paris', 40), # firm 16
  (46, 'rue firm 17', 'Meat', '674A4', 'Paris', 40), # firm 17
  (47, 'rue firm 18', 'Meat', '674A4', 'Paris', 40), # firm 18
  (48, 'rue firm 19', 'Meat', '674A4', 'Paris', 40), # firm 19
  (49, 'rue firm 20', 'Meat', '674A4', 'Paris', 40), # firm 20
  (50, 'rue firm 21', 'Meat', '674A4', 'Paris', 40), # firm 21
  (51, 'rue firm 22', 'Meat', '674A4', 'Paris', 40), # firm 22
  (52, 'rue firm 23', 'Meat', '674A4', 'Paris', 40), # firm 23
  (53, 'rue firm 24', 'Meat', '674A4', 'Paris', 40), # firm 24
  (54, 'rue firm 25', 'Meat', '674A4', 'Paris', 40), # firm 25
  (55, 'rue firm 26', 'Meat', '674A4', 'Paris', 40); # firm 26

TRUNCATE TABLE core_ticket;
INSERT INTO core_ticket (id, userId, title, message, type, status) VALUES
  (1, 1, 'Impossible de changer son mot de passe', 'Bonjour, je narrive pas à changer mon mot de passe', 'Problème de compte', 'En cours'); # ticket 1

TRUNCATE TABLE core_member;
INSERT INTO core_member (id, genderId, firstName, lastName, birthday, telephone, email, addressId, schoolYear, departmentId, company, profilePicture, droitImage) VALUES
  (1, 1, 'Conor', 'Breeze', STR_TO_DATE('1975-12-25', '%Y-%m-%d'), '+332541254', 'fake.mail@fake.com', 2, 3, 1, 'Google', '1c518c591e1be2f2703dd8c9bb77dbb5.jpg', true),
  (3, 1, 'Laurence', 'Tainturière', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.mail3@fake.com', 3, 5, 2, NULL, NULL, true),
  (4, 3, 'Stéphane4', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly4@fake.com', 6, 3, 4, NULL, NULL, false),
  (6, 3, 'SuperPrenom', 'SuperNom', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'super@vraimentsuper.com', 9, 3, 4, NULL, NULL, true),
  (7, 3, 'Stéphane7', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly7@fake.com', 10, 3, 4, NULL, NULL, true),
  (8, 3, 'Stéphane8', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly8@fake.com', 11, 3, 4, NULL, NULL, true),
  (9, 3, 'Stéphane9', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly9@fake.com', 12, 3, 4, NULL, NULL, true),
  (10, 3, 'Stéphane10', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly10@fake.com', 13, 3, 4, NULL, NULL, true),
  (11, 3, 'Stéphane11', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly11@fake.com', 14, 3, 4, NULL, NULL, true),
  (12, 3, 'Stéphane12', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly12@fake.com', 15, 3, 4, NULL, NULL, true),
  (13, 3, 'Stéphane13', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly13@fake.com', 16, 3, 4, NULL, NULL, true),
  (14, 3, 'Stéphane14', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly14@fake.com', 17, 3, 4, NULL, NULL, true),
  (15, 3, 'Stéphane15', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly15@fake.com', 18, 3, 4, NULL, NULL, true),
  (16, 3, 'Stéphane16', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly16@fake.com', 19, 3, 4, NULL, NULL, true),
  (17, 3, 'Stéphane17', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly17@fake.com', 20, 3, 4, NULL, NULL, true),
  (18, 3, 'Stéphane18', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly18@fake.com', 21, 3, 4, NULL, NULL, true),
  (19, 3, 'Stéphane19', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly19@fake.com', 22, 3, 4, NULL, NULL, true),
  (20, 3, 'Stéphane20', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly20@fake.com', 23, 3, 4, NULL, NULL, true),
  (21, 3, 'Stéphane21', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly21@fake.com', 24, 3, 4, NULL, NULL, true),
  (22, 3, 'Stéphane22', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly22@fake.com', 25, 3, 4, NULL, NULL, true),
  (23, 3, 'Stéphane23', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly23@fake.com', 26, 3, 4, NULL, NULL, true),
  (24, 3, 'Stéphane24', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly24@fake.com', 27, 3, 4, NULL, NULL, true),
  (25, 3, 'Stéphane25', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly25@fake.com', 28, 3, 4, NULL, NULL, true),
  (26, 3, 'Stéphane26', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly26@fake.com', 29, 3, 4, NULL, NULL, true),
  (27, 3, 'Stéphane27', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly27@fake.com', 30, 3, 4, NULL, NULL, true),
  (28, 3, 'Stéphane28', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly28@fake.com', 31, 3, 4, NULL, NULL, true);


TRUNCATE TABLE core_consultant;
INSERT INTO core_consultant (id, genderId, firstName, lastName, birthday, telephone, email, addressId, schoolYear, departmentId, company, profilePicture, droitImage) VALUES
  (2, 1, 'Marah', 'Cool', STR_TO_DATE('1976-10-27', '%Y-%m-%d'), '+332541541', 'fake.mail2@fake.com', 8, 3, 1, 'Amazon', NULL, true),
  (5, 3, 'Louis', 'Ung', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.mail3@fake.com', 7, 3, 4, NULL, NULL, false);

TRUNCATE TABLE core_member_position;
INSERT INTO core_member_position (id, memberId, positionId, isBoard, year) VALUES
  (1, 1, 3, TRUE, 2018),
  (3, 3, 1, TRUE, 2018),
  (4, 3, 2, TRUE, 1990),
  (5, 3, 3, FALSE, 2015),
  (6, 1, 6, FALSE, 2016),
  (7, 2, 6, FALSE, 2002),
  (8, 4, 9, FALSE, 2015),
  (9, 6, 19, TRUE, 2018),
  (10, 6, 17, TRUE, 2018),
  (11, 6, 21, TRUE, 2018),
  (12, 6, 22, TRUE, 2018),
  (13, 6, 18, TRUE, 2018);

TRUNCATE TABLE ua_firm;
INSERT INTO ua_firm (id, siret, name, addressId, typeId, mainContact) VALUES
  (1, '215437645', 'Google', 4, 3, 4),
  (2, '471245896', 'JetBrains', 5, 1, null),
  (3, '471245236', 'Firm 3', 32, 2, null),
  (4, '471245346', 'Firm 4', 33, 3, null),
  (5, '471345236', 'Firm 5', 34, 4, null),
  (6, '471223896', 'Firm 6', 35, 5, null),
  (7, '471254896', 'Firm 7', 36, 6, null),
  (8, '451245896', 'Firm 8', 37, 7, null),
  (9, '471267896', 'Firm 9', 38, 3, null),
  (10, '471247896', 'Firm 10', 39, 2, null),
  (11, '423245896', 'Firm 11', 40, 4, null),
  (12, '471455896', 'Firm 12', 41, 5, null),
  (13, '473455896', 'Firm 13', 42, 6, null),
  (14, '471262896', 'Firm 14', 43, 7, null),
  (15, '471237896', 'Firm 15', 44, 2, null),
  (16, '471246896', 'Firm 16', 45, 4, null),
  (17, '471278896', 'Firm 17', 46, 3, null),
  (18, '371234896', 'Firm 18', 47, 1, null),
  (19, '471289896', 'Firm 19', 48, 1, null),
  (20, '471202896', 'Firm 20', 49, 2, null),
  (21, '471245456', 'Firm 21', 50, 4, null),
  (22, '471245845', 'Firm 22', 51, 3, null),
  (23, '473445896', 'Firm 23', 52, 6, null),
  (24, '471244596', 'Firm 24', 53, 5, null),
  (25, '471243496', 'Firm 25', 54, 7, null),
  (26, '471346796', 'Firm 26', 55, 1, null);

TRUNCATE TABLE ua_contact;
INSERT INTO `ua_contact` (`id`, `firstName`, `lastName`, `genderId`, `firmId`, `email`, `telephone`, `cellphone`, `position`, `notes`, `old`) VALUES
  (1, 'Alexandre', 'Lang', 1, 2, 'alexandre.lang@etic.com', NULL, '0033175985495', 'Directeur Marketing', 'RAS', true),
  (2, 'Conor', 'Ryan', 1, 1, 'conor.ryan@etic.com', '0033666666666', '0033666666666', 'Architecte Réseau', null, true),
  (3, 'Laurent', 'Tainturier', 1, 1, 'laurent.tainturier@etic.com', '0033333333333', '0033222222222', 'Chercheur', 'Part de la boite bientôt', true),
  (4, 'Marah', 'Galy Adam', 1, 1, 'marah.galy@etic-insa.com', '0033646786532', NULL, NULL, NULL, false),
  (5, 'Contact 5', 'Contact Name 5', 3, 17, 'Contact 5@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true),
  (6, 'Contact 6', 'Contact Name 6', 3, 8, 'Contact 6@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (7, 'Contact 7', 'Contact Name 7', 1, 14, 'Contact 7@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (8, 'Contact 8', 'Contact Name 8', 4, 10, 'Contact 8@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (9, 'Contact 9', 'Contact Name 9', 1, 1, 'Contact 9@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (10, 'Contact 10', 'Contact Name 10', 3, 12, 'Contact 10@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true),
  (11, 'Contact 11', 'Contact Name 11', 4, 9, 'Contact 11@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true),
  (12, 'Contact 12', 'Contact Name 12', 3, 1, 'Contact 12@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true),
  (13, 'Contact 13', 'Contact Name 13', 3, 23, 'Contact 13@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (14, 'Contact 14', 'Contact Name 14', 2, 16, 'Contact 14@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (15, 'Contact 15', 'Contact Name 15', 3, 15, 'Contact 15@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true),
  (16, 'Contact 16', 'Contact Name 16', 2, 25, 'Contact 16@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (17, 'Contact 17', 'Contact Name 17', 1, 8, 'Contact 17@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (18, 'Contact 18', 'Contact Name 18', 3, 5, 'Contact 18@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (19, 'Contact 19', 'Contact Name 19', 2, 4, 'Contact 19@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true),
  (20, 'Contact 20', 'Contact Name 20', 2, 1, 'Contact 20@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true),
  (21, 'Contact 21', 'Contact Name 21', 3, 18, 'Contact 21@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (22, 'Contact 22', 'Contact Name 22', 1, 18, 'Contact 22@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true),
  (23, 'Contact 23', 'Contact Name 23', 2, 5, 'Contact 23@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true),
  (24, 'Contact 24', 'Contact Name 24', 4, 16, 'Contact 24@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (25, 'Contact 25', 'Contact Name 25', 4, 25, 'Contact 25@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true),
  (26, 'Contact 26', 'Contact Name 26', 4, 3, 'Contact 26@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (27, 'Contact 27', 'Contact Name 27', 4, 6, 'Contact 27@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true),
  (28, 'Contact 28', 'Contact Name 28', 4, 24, 'Contact 28@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true),
  (29, 'Contact 29', 'Contact Name 29', 1, 3, 'Contact 29@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', false),
  (30, 'Contact 30', 'Contact Name 30', 1, 7, 'Contact 30@fake.com', '0033646786532', '0033222222222', 'CEO', 'Sample data', true);

TRUNCATE TABLE ua_study;
INSERT INTO `ua_study` (`id`, `name`, `description`, `fieldId`, `provenanceId`, `statusId`, `signDate`, `endDate`, `managementFee`, `realizationFee`, `rebilledFee`, `ecoparticipationFee`, `outsourcingFee`, `archivedDate`, `firmId`,`confidential`,`mainLeader`, `mainQualityManager`, `mainConsultant`) VALUES
  (1, 'Développement IDE', 'Développement d''un IDE pour utilisation interne', 1, 1, 2, '2018-11-10', '2018-11-10', 12000000, 123, 12345, 12, 12324454, '2018-11-10', 1,FALSE, null, null, null),
  (2, 'Tests d''acidité dans le Rhône', 'Créateur de IDE', 1, 1, 2, '2018-11-10', '2018-11-10', 12000000, 123, 12345, 12, 12324454, '2018-11-10', 2, FALSE,4, 4, 2),
  (3, 'Développement app mobile', 'Développement d''une app pour scanner des images', 1, 1, 2, '2018-11-10', '2018-11-10', 12000000, 123, 12345, 12, 12324454, '2018-11-10', 1, TRUE,null, null, null);

TRUNCATE TABLE ua_study_consultant;
INSERT INTO `ua_study_consultant` (`consultantId`, `studyId`) VALUES
  (2, 2),
  (5, 1);

TRUNCATE TABLE ua_study_leader;
INSERT INTO `ua_study_leader` (`memberId`, `studyId`) VALUES
  (3, 2),
  (3, 3),
  (1, 2),
  (4, 2),
  (6, 1),
  (4, 1),
  (2, 3);

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
INSERT INTO sg_member_inscription (id, firstName, lastName, genderId, birthday, departmentId, email, phoneNumber, outYear, nationalityId, wantedPoleId, addressId, hasPaid, droitImage) VALUES
(1, 'Bruce', 'Wayne', 1, STR_TO_DATE('2000/2/14', '%Y/%m/%d'), 3, 'bruce.wayne@batman.com', '0033123456789', 2021, 42, 8, 1, false, false),
(2, 'Clark', 'Kent', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 2, 'clark.kent@dailyplanete.com', '0033123456789', 2023, 69, 4, 1, true, true);

TRUNCATE TABLE sg_member_inscription_document_type;
INSERT INTO sg_member_inscription_document_type(id, location, `name`, isTemplatable) VALUES
    (1, 'Fiche_inscription_membre_actif.pdf', 'Fiche inscription membre', true);

COMMIT;

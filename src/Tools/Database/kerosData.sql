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
    (55, 'rue firm 26', 'Meat', '674A4', 'Paris', 40), # firm 26
    (56, 'rue member inscription 3', 'Meat', '14785', 'Villeurbanne City', 129), # memberInscription 3
    (57, 'rue member inscription 4', 'Meat', '14785', 'Villeurbanne City', 156), # memberInscription 4
    (58, 'rue member inscription 5', 'Meat', '14785', 'Villeurbanne City', 186), # memberInscription 5
    (59, 'rue member inscription 6', 'Meat', '14785', 'Villeurbanne City', 195), # memberInscription 6
    (60, 'rue member inscription 7', 'Meat', '14785', 'Villeurbanne City', 17), # memberInscription 7
    (61, 'rue member inscription 8', 'Meat', '14785', 'Villeurbanne City', 174), # memberInscription 8
    (62, 'rue member inscription 9', 'Meat', '14785', 'Villeurbanne City', 125), # memberInscription 9
    (63, 'rue member inscription 10', 'Meat', '14785', 'Villeurbanne City', 52), # memberInscription 10
    (64, 'rue member inscription 11', 'Meat', '14785', 'Villeurbanne City', 69), # memberInscription 11
    (65, 'rue member inscription 12', 'Meat', '14785', 'Villeurbanne City', 189), # memberInscription 12
    (66, 'rue member inscription 13', 'Meat', '14785', 'Villeurbanne City', 64), # memberInscription 13
    (67, 'rue member inscription 14', 'Meat', '14785', 'Villeurbanne City', 150), # memberInscription 14
    (68, 'rue member inscription 15', 'Meat', '14785', 'Villeurbanne City', 165), # memberInscription 15
    (69, 'rue member inscription 16', 'Meat', '14785', 'Villeurbanne City', 116), # memberInscription 16
    (70, 'rue member inscription 17', 'Meat', '14785', 'Villeurbanne City', 18), # memberInscription 17
    (71, 'rue member inscription 18', 'Meat', '14785', 'Villeurbanne City', 4), # memberInscription 18
    (72, 'rue member inscription 19', 'Meat', '14785', 'Villeurbanne City', 4), # memberInscription 19
    (73, 'rue member inscription 20', 'Meat', '14785', 'Villeurbanne City', 118), # memberInscription 20
    (74, 'rue member inscription 21', 'Meat', '14785', 'Villeurbanne City', 109), # memberInscription 21
    (75, 'rue member inscription 22', 'Meat', '14785', 'Villeurbanne City', 116), # memberInscription 22
    (76, 'rue member inscription 23', 'Meat', '14785', 'Villeurbanne City', 93), # memberInscription 23
    (77, 'rue member inscription 24', 'Meat', '14785', 'Villeurbanne City', 47), # memberInscription 24
    (78, 'rue member inscription 25', 'Meat', '14785', 'Villeurbanne City', 29), # memberInscription 25
    (79, 'rue member inscription 26', 'Meat', '14785', 'Villeurbanne City', 160), # memberInscription 26
    (80, 'rue member inscription 27', 'Meat', '14785', 'Villeurbanne City', 88), # memberInscription 27
    (81, 'rue member inscription 28', 'Meat', '14785', 'Villeurbanne City', 77), # memberInscription 28
    (82, 'rue member inscription 29', 'Meat', '14785', 'Villeurbanne City', 146), # memberInscription 29
    (83, 'rue member inscription 30', 'Meat', '14785', 'Villeurbanne City', 130), # memberInscription 30
    (84, '14 PaymentSlip 1', 'Quoi ?', '32456', 'Lyon', 22), #Payment Slip 1
    (85, '14 PaymentSlip 2', 'Ter', '32456', 'Lyon', 22); #Payment Slip 2

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
  (1, 'Alexandre', 'Lang', 1, 2, 'alexandre.lang@etic.com', NULL, '0033175985495', 'Directeur Marketing', 'RAS', 1),
  (2, 'Conor', 'Ryan', 1, 1, 'conor.ryan@etic.com', '0033666666666', '0033666666666', 'Architecte Réseau', null, 1),
  (3, 'Laurent', 'Tainturier', 1, 1, 'laurent.tainturier@etic.com', '0033333333333', '0033222222222', 'Chercheur', 'Part de la boite bientôt', 1),
  (4, 'Marah', 'Galy Adam', 1, 1, 'marah.galy@etic-insa.com', '0033646786532', NULL, NULL, NULL, 0);

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
INSERT INTO ua_study_document_type(id, name, `location`, isTemplatable, oneConsultant) VALUES
  (1, 'un document random', 'document.docx', 1, 0),
  (2, 'acompte', 'acompte.docx', 1, 1),
  (3, 'un doc', 'undoc.docx', 0, 0);

TRUNCATE TABLE core_document;
INSERT INTO core_document(id, uploadDate, location, discr) VALUES
  (1, STR_TO_DATE('2019/2/14 10:40:10', '%Y/%m/%d %h:%i:%s'), 'study_1/document_2/acompte.docx', 'ua_study_document'),
  (2, STR_TO_DATE('2018/12/16 10:40:10', '%Y/%m/%d %h:%i:%s'), 'study_1/document_3/FE.docx', 'ua_study_document'),
  (3, STR_TO_DATE('2018/12/16 10:40:10', '%Y/%m/%d %h:%i:%s'), 'facture_1/document_3/proformat.docx', 'treso_facture_document'),
  (4, STR_TO_DATE('2019/04/19 10:40:10', '%Y/%m/%d %h:%i:%s'), 'facture_1/document_4/solde.docx', 'treso_facture_document'),
  (5, STR_TO_DATE('2019/04/19 10:40:10', '%Y/%m/%d %h:%i:%s'), 'member_inscription_1/document_1/Fiche_membre_1.pdf', 'sg_member_inscription_document');

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
    (2, 'Clark', 'Kent', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 2, 'clark.kent@dailyplanete.com', '0033123456789', 2023, 69, 4, 1, true, true),
    (3, 'member inscription 3', 'member inscription 3 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 9, 'memberInscription3@fake.com', '+33514785269', 2025, 115, 7, 56, true, false),
    (4, 'member inscription 4', 'member inscription 4 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 13, 'memberInscription4@fake.com', '+33514785269', 2020, 1, 4, 57, false, false),
    (5, 'member inscription 5', 'member inscription 5 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 2, 'memberInscription5@fake.com', '+33514785269', 2024, 60, 6, 58, false, true),
    (6, 'member inscription 6', 'member inscription 6 name', 2, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 5, 'memberInscription6@fake.com', '+33514785269', 2022, 154, 2, 59, false, true),
    (7, 'member inscription 7', 'member inscription 7 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 2, 'memberInscription7@fake.com', '+33514785269', 2020, 182, 8, 60, false, false),
    (8, 'member inscription 8', 'member inscription 8 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 1, 'memberInscription8@fake.com', '+33514785269', 2021, 84, 6, 61, true, false),
    (9, 'member inscription 9', 'member inscription 9 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 1, 'memberInscription9@fake.com', '+33514785269', 2025, 87, 4, 62, true, true),
    (10, 'member inscription 10', 'member inscription 10 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 1, 'memberInscription10@fake.com', '+33514785269', 2025, 93, 4, 63, true, false),
    (11, 'member inscription 11', 'member inscription 11 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 7, 'memberInscription11@fake.com', '+33514785269', 2024, 131, 6, 64, false, true),
    (12, 'member inscription 12', 'member inscription 12 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 12, 'memberInscription12@fake.com', '+33514785269', 2025, 88, 8, 65, true, true),
    (13, 'member inscription 13', 'member inscription 13 name', 2, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 8, 'memberInscription13@fake.com', '+33514785269', 2024, 22, 5, 66, true, true),
    (14, 'member inscription 14', 'member inscription 14 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 1, 'memberInscription14@fake.com', '+33514785269', 2023, 12, 6, 67, true, true),
    (15, 'member inscription 15', 'member inscription 15 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 12, 'memberInscription15@fake.com', '+33514785269', 2023, 76, 4, 68, false, false),
    (16, 'member inscription 16', 'member inscription 16 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 7, 'memberInscription16@fake.com', '+33514785269', 2023, 126, 3, 69, true, false),
    (17, 'member inscription 17', 'member inscription 17 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 9, 'memberInscription17@fake.com', '+33514785269', 2021, 183, 7, 70, false, true),
    (18, 'member inscription 18', 'member inscription 18 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 1, 'memberInscription18@fake.com', '+33514785269', 2022, 71, 5, 71, false, true),
    (19, 'member inscription 19', 'member inscription 19 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 6, 'memberInscription19@fake.com', '+33514785269', 2021, 70, 7, 72, true, false),
    (20, 'member inscription 20', 'member inscription 20 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 7, 'memberInscription20@fake.com', '+33514785269', 2023, 70, 3, 73, false, true),
    (21, 'member inscription 21', 'member inscription 21 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 6, 'memberInscription21@fake.com', '+33514785269', 2020, 195, 9, 74, false, true),
    (22, 'member inscription 22', 'member inscription 22 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 2, 'memberInscription22@fake.com', '+33514785269', 2020, 57, 6, 75, true, true),
    (23, 'member inscription 23', 'member inscription 23 name', 2, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 4, 'memberInscription23@fake.com', '+33514785269', 2025, 14, 7, 76, true, false),
    (24, 'member inscription 24', 'member inscription 24 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 7, 'memberInscription24@fake.com', '+33514785269', 2021, 44, 4, 77, true, true),
    (25, 'member inscription 25', 'member inscription 25 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 3, 'memberInscription25@fake.com', '+33514785269', 2024, 131, 4, 78, true, false),
    (26, 'member inscription 26', 'member inscription 26 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 13, 'memberInscription26@fake.com', '+33514785269', 2023, 184, 4, 79, true, true),
    (27, 'member inscription 27', 'member inscription 27 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 1, 'memberInscription27@fake.com', '+33514785269', 2023, 2, 3, 80, false, false),
    (28, 'member inscription 28', 'member inscription 28 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 6, 'memberInscription28@fake.com', '+33514785269', 2021, 89, 6, 81, true, false),
    (29, 'member inscription 29', 'member inscription 29 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 3, 'memberInscription29@fake.com', '+33514785269', 2022, 141, 7, 82, true, true),
    (30, 'member inscription 30', 'member inscription 30 name', 2, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 10, 'memberInscription30@fake.com', '+33514785269', 2025, 135, 4, 83, true, false);

TRUNCATE TABLE sg_member_inscription_document_type;
INSERT INTO sg_member_inscription_document_type(id, location, `name`, isTemplatable) VALUES
    (1, 'Fiche_inscription_membre_actif.pdf', 'Fiche inscription membre', true);

TRUNCATE TABLE sg_member_inscription_document;
INSERT INTO sg_member_inscription_document(id, memberInscriptionId, memberInscriptionDocumentTypeId) VALUES
(5, 1, 1);

TRUNCATE TABLE treso_payment_slip;
INSERT INTO treso_payment_slip (id, missionRecapNumber, consultantName, consultantSocialSecurityNumber, addressId, email, studyId, clientName, projectLead, isTotalJeh, isStudyPaid, amountDescription, createdDate, creatorId, validatedByUa, validatedByUaDate, uaValidatorId, validatedByPerf, validatedByPerfDate, perfValidatorId) VALUES
  (1, '102383203', 'Shrek', '12320183', 84, 'shrek@fortfortlointain.fr', 1, 'L''âne', 'Le chat Potté', false, false, 'Facture payée', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), 1, false, null, null, false, null, null),
  (2, '102383204', 'Shrek', '12320183', 85, 'shrek@fortfortlointain.fr', 1, 'L''âne', 'Le chat Potté', false, false, 'Facture payée', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), 1, true, STR_TO_DATE('5/16/2022', '%c/%e/%Y'), 4, true, STR_TO_DATE('5/17/2022', '%c/%e/%Y'), 8);

COMMIT;
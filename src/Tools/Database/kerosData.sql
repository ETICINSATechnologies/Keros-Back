SET AUTOCOMMIT = 0;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;

/* Passwords are on the right */
TRUNCATE TABLE core_user;
INSERT INTO core_user (id, username, password, expiresAt, disabled) VALUES
    (1, 'username', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (2, 'mcool', '$2y$10$fWnWMRQKKWInygzk.FNIP.BsnTp8e8XvDwj5YdGgVuIFXsz/XvVgm' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #hunter11
    (3, 'lswollo', '$2y$10$9R4lfhp18.iVzsP8amDL5e7eumi48DmPPkoa5YLAm/thAZWIHaOtW' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #hunter12
    (4, 'qualqual', '$2y$10$9R4lfhp18.iVzsP8amDL5e7eumi48DmPPkoa5YLAm/thAZWIHaOtW' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #hunter13
    (5, 'lung', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (6, 'superuser', '$2y$10$fVPB3SLO54Ng5DjqUEr8/OSwOtOMy0gH4DiEqjvxXqRVdcM8hc7Du' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #superuser
    (7, 'disabled', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), true), #password
    (8, 'user8', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (9, 'user9', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (10, 'user10', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (11, 'user11', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (12, 'user12', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (13, 'user13', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (14, 'user14', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (15, 'user15', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (16, 'user16', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (17, 'user17', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (18, 'user18', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (19, 'user19', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (20, 'user20', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (21, 'user21', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (22, 'user22', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (23, 'user23', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (24, 'user24', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (25, 'user25', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (26, 'user26', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (27, 'user27', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (28, 'user28', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (29, 'consultant 29', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (30, 'consultant 30', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (31, 'consultant 31', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (32, 'consultant 32', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (33, 'consultant 33', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (34, 'consultant 34', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (35, 'consultant 35', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (36, 'consultant 36', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (37, 'consultant 37', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (38, 'consultant 38', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (39, 'consultant 39', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (40, 'consultant 40', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (41, 'consultant 41', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (42, 'consultant 42', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (43, 'consultant 43', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (44, 'consultant 44', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (45, 'consultant 45', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (46, 'consultant 46', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (47, 'consultant 47', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (48, 'consultant 48', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (49, 'consultant 49', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (50, 'consultant 50', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (51, 'consultant 51', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (52, 'consultant 52', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (53, 'consultant 53', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (54, 'consultant 54', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false), #password
    (55, 'consultant 55', '$2y$10$CMdJgBHbdymIM5/WUuz8guvjvSA2dxgDQKAQkaiOD8aMF0sKc4GhG', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), false); #password

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
    (85, '14 PaymentSlip 2', 'Ter', '32456', 'Lyon', 22), #Payment Slip 2
    (86, 'rue facture 1', 'Meat', '674A4', 'Paris', 40), # facture 1
    (87, 'rue facture 2', 'Meat', '674A4', 'Paris', 40), # facture 2
    (88, 'rue facture 3', 'Meat', '674A4', 'Paris', 40), # facture 3
    (89, 'rue facture 4', 'Meat', '674A4', 'Paris', 40), # facture 4
    (90, 'Rue consultant 29', 'Ter', '79413', 'Lyon City', 35), #consultant 29
    (91, 'Rue consultant 30', 'Ter', '79413', 'Lyon City', 33), #consultant 30
    (92, 'Rue consultant 31', 'Ter', '79413', 'Lyon City', 52), #consultant 31
    (93, 'Rue consultant 32', 'Ter', '79413', 'Lyon City', 90), #consultant 32
    (94, 'Rue consultant 33', 'Ter', '79413', 'Lyon City', 67), #consultant 33
    (95, 'Rue consultant 34', 'Ter', '79413', 'Lyon City', 38), #consultant 34
    (96, 'Rue consultant 35', 'Ter', '79413', 'Lyon City', 77), #consultant 35
    (97, 'Rue consultant 36', 'Ter', '79413', 'Lyon City', 56), #consultant 36
    (98, 'Rue consultant 37', 'Ter', '79413', 'Lyon City', 32), #consultant 37
    (99, 'Rue consultant 38', 'Ter', '79413', 'Lyon City', 57), #consultant 38
    (100, 'Rue consultant 39', 'Ter', '79413', 'Lyon City', 59), #consultant 39
    (101, 'Rue consultant 40', 'Ter', '79413', 'Lyon City', 44), #consultant 40
    (102, 'Rue consultant 41', 'Ter', '79413', 'Lyon City', 40), #consultant 41
    (103, 'Rue consultant 42', 'Ter', '79413', 'Lyon City', 53), #consultant 42
    (104, 'Rue consultant 43', 'Ter', '79413', 'Lyon City', 23), #consultant 43
    (105, 'Rue consultant 44', 'Ter', '79413', 'Lyon City', 12), #consultant 44
    (106, 'Rue consultant 45', 'Ter', '79413', 'Lyon City', 84), #consultant 45
    (107, 'Rue consultant 46', 'Ter', '79413', 'Lyon City', 86), #consultant 46
    (108, 'Rue consultant 47', 'Ter', '79413', 'Lyon City', 99), #consultant 47
    (109, 'Rue consultant 48', 'Ter', '79413', 'Lyon City', 22), #consultant 48
    (110, 'Rue consultant 49', 'Ter', '79413', 'Lyon City', 85), #consultant 49
    (111, 'Rue consultant 50', 'Ter', '79413', 'Lyon City', 58), #consultant 50
    (112, 'Rue consultant 51', 'Ter', '79413', 'Lyon City', 92), #consultant 51
    (113, 'Rue consultant 52', 'Ter', '79413', 'Lyon City', 61), #consultant 52
    (114, 'Rue consultant 53', 'Ter', '79413', 'Lyon City', 85), #consultant 53
    (115, 'Rue consultant 54', 'Ter', '79413', 'Lyon City', 71), #consultant 54
    (116, 'Rue consultant 55', 'Ter', '79413', 'Lyon City', 97); #consultant 55

TRUNCATE TABLE core_ticket;
INSERT INTO core_ticket (id, userId, title, message, type, status) VALUES
  (1, 1, 'Impossible de changer son mot de passe', 'Bonjour, je narrive pas à changer mon mot de passe', 'Problème de compte', 'En cours'); # ticket 1

TRUNCATE TABLE core_member;
INSERT INTO core_member (id, genderId, firstName, lastName, birthday, telephone, email, addressId, schoolYear, departmentId, company, profilePicture, droitImage, createdDate, isAlumni) VALUES
  (1, 1, 'Conor', 'Breeze', STR_TO_DATE('1975-12-25', '%Y-%m-%d'), '+332541254', 'fake.mail@fake.com', 2, 3, 1, 'Google', '1c518c591e1be2f2703dd8c9bb77dbb5.jpg', true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (3, 1, 'Laurence', 'Tainturière', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.mail3@fake.com', 3, 5, 2, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (4, 3, 'Stéphane4', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly4@fake.com', 6, 3, 4, NULL, NULL, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (6, 3, 'SuperPrenom', 'SuperNom', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'super@vraimentsuper.com', 9, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (7, 3, 'Disabled', 'DisabledName', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'disabled@fake.com', 10, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false), -- Disabled member
  (8, 3, 'Stéphane8', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly8@fake.com', 11, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (9, 3, 'Stéphane9', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly9@fake.com', 12, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (10, 3, 'Stéphane10', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly10@fake.com', 13, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (11, 3, 'Stéphane11', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly11@fake.com', 14, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (12, 3, 'Stéphane12', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly12@fake.com', 15, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (13, 3, 'Stéphane13', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly13@fake.com', 16, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (14, 3, 'Stéphane14', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly14@fake.com', 17, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (15, 3, 'Stéphane15', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly15@fake.com', 18, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (16, 3, 'Stéphane16', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly16@fake.com', 19, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (17, 3, 'Stéphane17', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly17@fake.com', 20, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (18, 3, 'Stéphane18', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly18@fake.com', 21, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (19, 3, 'Stéphane19', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly19@fake.com', 22, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (20, 3, 'Stéphane20', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly20@fake.com', 23, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (21, 3, 'Stéphane21', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly21@fake.com', 24, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (22, 3, 'Stéphane22', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly22@fake.com', 25, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (23, 3, 'Stéphane23', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly23@fake.com', 26, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (24, 3, 'Stéphane24', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly24@fake.com', 27, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (25, 3, 'Stéphane25', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly25@fake.com', 28, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (26, 3, 'Stéphane26', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly26@fake.com', 29, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), false),
  (27, 3, 'Stéphane27', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly27@fake.com', 30, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), true),
  (28, 3, 'Stéphane28', 'McMahon', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.maly28@fake.com', 31, 3, 4, NULL, NULL, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d'), true);

TRUNCATE TABLE core_consultant;
INSERT INTO core_consultant (id, genderId, firstName, lastName, birthday, telephone, email, addressId, socialSecurityNumber, schoolYear, departmentId, company, profilePicture, droitImage, isApprentice, createdDate, documentIdentity, documentScolaryCertificate, documentRIB, documentVitaleCard, documentResidencePermit, documentCVEC) VALUES
  (2, 1, 'Marah', 'Cool', STR_TO_DATE('1976-10-27', '%Y-%m-%d'), '+332541541', 'fake.mail2@fake.com', 8, 123456789012345, 3, 1, 'Amazon', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), 'test.pdf', 'test.pdf', 'test.pdf', 'test.pdf', 'test.pdf', 'test.pdf'),
  (5, 3, 'Louis', 'Ung', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.mail3@fake.com', 7, 123456789012345, 3, 4, NULL, NULL, false, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (29, 1, 'Consultant 29', 'Consultant Name29', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant29@fake.com', 90, null, 1, 1, 'Fake Enterprise Consultant 29', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (30, 4, 'Consultant 30', 'Consultant Name30', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant30@fake.com', 91, null, 3, 11, 'Fake Enterprise Consultant 30', NULL, false, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (31, 4, 'Consultant 31', 'Consultant Name31', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant31@fake.com', 92, null, 5, 2, 'Fake Enterprise Consultant 31', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (32, 3, 'Consultant 32', 'Consultant Name32', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant32@fake.com', 93, null, 4, 7, 'Fake Enterprise Consultant 32', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (33, 4, 'Consultant 33', 'Consultant Name33', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant33@fake.com', 94, null, 8, 4, 'Fake Enterprise Consultant 33', NULL, false, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (34, 1, 'Consultant 34', 'Consultant Name34', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant34@fake.com', 95, null, 5, 7, 'Fake Enterprise Consultant 34', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (35, 1, 'Consultant 35', 'Consultant Name35', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant35@fake.com', 96, null, 5, 7, 'Fake Enterprise Consultant 35', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (36, 4, 'Consultant 36', 'Consultant Name36', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant36@fake.com', 97, null, 2, 12, 'Fake Enterprise Consultant 36', NULL, false, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (37, 1, 'Consultant 37', 'Consultant Name37', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant37@fake.com', 98, null, 7, 2, 'Fake Enterprise Consultant 37', NULL, false, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (38, 2, 'Consultant 38', 'Consultant Name38', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant38@fake.com', 99, null, 2, 7, 'Fake Enterprise Consultant 38', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (39, 3, 'Consultant 39', 'Consultant Name39', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant39@fake.com', 100, null, 2, 11, 'Fake Enterprise Consultant 39', NULL, false, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (40, 3, 'Consultant 40', 'Consultant Name40', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant40@fake.com', 101, null, 1, 11, 'Fake Enterprise Consultant 40', NULL, false, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (41, 2, 'Consultant 41', 'Consultant Name41', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant41@fake.com', 102, null, 1, 3, 'Fake Enterprise Consultant 41', NULL, false, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (42, 3, 'Consultant 42', 'Consultant Name42', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant42@fake.com', 103, null, 7, 6, 'Fake Enterprise Consultant 42', NULL, false, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (43, 3, 'Consultant 43', 'Consultant Name43', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant43@fake.com', 104, null, 6, 11, 'Fake Enterprise Consultant 43', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (44, 3, 'Consultant 44', 'Consultant Name44', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant44@fake.com', 105, null, 7, 2, 'Fake Enterprise Consultant 44', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (45, 1, 'Consultant 45', 'Consultant Name45', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant45@fake.com', 106, null, 7, 11, 'Fake Enterprise Consultant 45', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (46, 1, 'Consultant 46', 'Consultant Name46', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant46@fake.com', 107, null, 6, 5, 'Fake Enterprise Consultant 46', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (47, 2, 'Consultant 47', 'Consultant Name47', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant47@fake.com', 108, null, 5, 4, 'Fake Enterprise Consultant 47', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (48, 1, 'Consultant 48', 'Consultant Name48', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant48@fake.com', 109, null, 2, 7, 'Fake Enterprise Consultant 48', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (49, 4, 'Consultant 49', 'Consultant Name49', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant49@fake.com', 110, null, 2, 7, 'Fake Enterprise Consultant 49', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (50, 1, 'Consultant 50', 'Consultant Name50', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant50@fake.com', 111, null, 7, 5, 'Fake Enterprise Consultant 50', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (51, 1, 'Consultant 51', 'Consultant Name51', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant51@fake.com', 112, null, 8, 10, 'Fake Enterprise Consultant 51', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (52, 3, 'Consultant 52', 'Consultant Name52', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant52@fake.com', 113, null, 5, 10, 'Fake Enterprise Consultant 52', NULL, false, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (53, 3, 'Consultant 53', 'Consultant Name53', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant53@fake.com', 114, null, 3, 2, 'Fake Enterprise Consultant 53', NULL, false, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (54, 4, 'Consultant 54', 'Consultant Name54', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant54@fake.com', 115, null, 4, 4, 'Fake Enterprise Consultant 54', NULL, true, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL),
  (55, 3, 'Consultant 55', 'Consultant Name55', STR_TO_DATE('1987-11-2', '%Y-%m-%d'), '+337425254', 'fake.consultant55@fake.com', 116, null, 7, 3, 'Fake Enterprise Consultant 55', NULL, false, true, STR_TO_DATE('2019-09-01', '%Y-%m-%d'), NULL, NULL, NULL, NULL, NULL, NULL);

TRUNCATE TABLE core_member_position;
INSERT INTO core_member_position (id, memberId, positionId, isBoard, year) VALUES
  (1, 1, 3, TRUE, 2018),
  (3, 3, 1, TRUE, 2018),
  (4, 3, 2, TRUE, 1990),
  (5, 3, 3, FALSE, 2015),
  (6, 1, 7, FALSE, 2016),
  (7, 2, 7, FALSE, 2002),
  (8, 4, 9, FALSE, 2015),
  (9, 6, 19, TRUE, 2018),
  (10, 6, 21, TRUE, 2018),
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

#Penser à update StudyIntegrationTest.testDeleteAllStudyShouldReturn204
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

#Penser à mettre à jour le nombre de facture dans FactureIntegrationTest::testDeleteAllExistingFacturesShouldReturn204
TRUNCATE TABLE treso_facture;
INSERT INTO treso_facture (id, numero, addressId, clientName , contactName, contactEmail, studyId, typeId, amountDescription,
                            subject, agreementSignDate, amountHT, taxPercentage, dueDate , additionalInformation, createdDate, createdById,
                            validatedByUa, validatedByUaDate, validatedByUaMemberId, validatedByPerf, validatedByPerfDate, validatedByPerfMemberId) VALUES
(1,'23023234', 86, 'Google', 'James Bond', 'mail@exemple.fr', 1, 1, 'Trois Euros', 'Sujet du projet', '2018-11-10', 234.34, 345.45, '2018-1-10',
  'info supp', '2018-11-4', 3, true, '2017-11-10', 3, true, '2019-11-10', 3),
(2,'23023235', 87, 'Milka', 'Alexandre Lang', 'fauxmail@exemple.fr', 1, 2, 'deux cents trente quatre euros et trente quatre centimes', 'Sujet du projet', '2018-11-10', 234.34, 20.0, '2018-1-10',
  'info supp', '2018-11-23', 3, false, null, null, false, null, null),
(3,'23023235', 88, 'Milka', 'Alexandre Lang', 'fauxmail@exemple.fr', 1, 3, 'deux cents trente quatre euros et trente quatre centimes', 'Sujet du projet', '2018-11-10', 234.34, 20.0, '2018-1-10',
 'info supp', '2018-11-3', 3, false, null, null, false, null, null),
(4,'23023235', 89, 'Milka', 'Alexandre Lang', 'fauxmail@exemple.fr', 1, 4, 'deux cents trente quatre euros et trente quatre centimes', 'Sujet du projet', '2018-11-10', 234.34, 20.0, '2018-1-10',
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
INSERT INTO sg_member_inscription (id, firstName, lastName, genderId, birthday, departmentId, email, phoneNumber, outYear, nationalityId, wantedPoleId, addressId, hasPaid, droitImage, createdDate) VALUES
    (1, 'Bruce', 'Wayne', 1, STR_TO_DATE('2000/2/14', '%Y/%m/%d'), 3, 'bruce.wayne@batman.com', '0033123456789', 2022, 42, 8, 1, false, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (2, 'Clark', 'Kent', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 2, 'clark.kent@dailyplanete.com', '0033123456789', 2024, 69, 4, 1, true, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (3, 'member inscription 3', 'member inscription 3 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 7, 'memberInscription3@fake.com', '+33514785269', 2026, 115, 7, 56, true, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (4, 'member inscription 4', 'member inscription 4 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 13, 'memberInscription4@fake.com', '+33514785269', 2021, 1, 4, 57, false, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (5, 'member inscription 5', 'member inscription 5 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 2, 'memberInscription5@fake.com', '+33514785269', 2025, 60, 6, 58, false, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (6, 'member inscription 6', 'member inscription 6 name', 2, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 5, 'memberInscription6@fake.com', '+33514785269', 2023, 154, 4, 59, false, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (7, 'member inscription 7', 'member inscription 7 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 2, 'memberInscription7@fake.com', '+33514785269', 2021, 182, 8, 60, false, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (8, 'member inscription 8', 'member inscription 8 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 1, 'memberInscription8@fake.com', '+33514785269', 2022, 84, 6, 61, true, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (9, 'member inscription 9', 'member inscription 9 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 1, 'memberInscription9@fake.com', '+33514785269', 2026, 87, 4, 62, true, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (10, 'member inscription 10', 'member inscription 10 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 1, 'memberInscription10@fake.com', '+33514785269', 2026, 93, 4, 63, true, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (11, 'member inscription 11', 'member inscription 11 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 7, 'memberInscription11@fake.com', '+33514785269', 2025, 131, 6, 64, false, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (12, 'member inscription 12', 'member inscription 12 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 12, 'memberInscription12@fake.com', '+33514785269', 2026, 88, 8, 65, true, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (13, 'member inscription 13', 'member inscription 13 name', 2, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 7, 'memberInscription13@fake.com', '+33514785269', 2025, 22, 5, 66, true, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (14, 'member inscription 14', 'member inscription 14 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 1, 'memberInscription14@fake.com', '+33514785269', 2024, 12, 6, 67, true, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (15, 'member inscription 15', 'member inscription 15 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 12, 'memberInscription15@fake.com', '+33514785269', 2024, 76, 4, 68, false, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (16, 'member inscription 16', 'member inscription 16 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 7, 'memberInscription16@fake.com', '+33514785269', 2024, 126, 9, 69, true, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (17, 'member inscription 17', 'member inscription 17 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 7, 'memberInscription17@fake.com', '+33514785269', 2022, 183, 7, 70, false, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (18, 'member inscription 18', 'member inscription 18 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 1, 'memberInscription18@fake.com', '+33514785269', 2023, 71, 5, 71, false, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (19, 'member inscription 19', 'member inscription 19 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 6, 'memberInscription19@fake.com', '+33514785269', 2022, 70, 7, 72, true, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (20, 'member inscription 20', 'member inscription 20 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 7, 'memberInscription20@fake.com', '+33514785269', 2024, 70, 9, 73, false, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (21, 'member inscription 21', 'member inscription 21 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 6, 'memberInscription21@fake.com', '+33514785269', 2021, 195, 9, 74, false, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (22, 'member inscription 22', 'member inscription 22 name', 3, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 2, 'memberInscription22@fake.com', '+33514785269', 2021, 57, 6, 75, true, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (23, 'member inscription 23', 'member inscription 23 name', 2, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 4, 'memberInscription23@fake.com', '+33514785269', 2026, 14, 7, 76, true, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (24, 'member inscription 24', 'member inscription 24 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 7, 'memberInscription24@fake.com', '+33514785269', 2022, 44, 4, 77, true, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (25, 'member inscription 25', 'member inscription 25 name', 1, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 3, 'memberInscription25@fake.com', '+33514785269', 2025, 131, 4, 78, true, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (26, 'member inscription 26', 'member inscription 26 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 13, 'memberInscription26@fake.com', '+33514785269', 2024, 184, 4, 79, true, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (27, 'member inscription 27', 'member inscription 27 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 1, 'memberInscription27@fake.com', '+33514785269', 2024, 2, 9, 80, false, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (28, 'member inscription 28', 'member inscription 28 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 6, 'memberInscription28@fake.com', '+33514785269', 2022, 89, 6, 81, true, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (29, 'member inscription 29', 'member inscription 29 name', 4, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 3, 'memberInscription29@fake.com', '+33514785269', 2023, 141, 7, 82, true, true, STR_TO_DATE('2019/9/1', '%Y/%m/%d')),
    (30, 'member inscription 30', 'member inscription 30 name', 2, STR_TO_DATE('1998/1/15', '%Y/%m/%d'), 10, 'memberInscription30@fake.com', '+33514785269', 2026, 135, 4, 83, true, false, STR_TO_DATE('2019/9/1', '%Y/%m/%d'));

TRUNCATE TABLE sg_member_inscription_document_type;
INSERT INTO sg_member_inscription_document_type(id, location, `name`, isTemplatable) VALUES
    (1, 'Fiche_inscription_membre_actif.pdf', 'Fiche inscription membre', true);

TRUNCATE TABLE sg_consultant_inscription;
INSERT INTO `sg_consultant_inscription` (`id`, `firstName`, `lastName`, `birthday`, `genderId`, `departmentId`, `email`, `phoneNumber`, `outYear`, `nationalityId`, `addressId`, `socialSecurityNumber`, `droitImage`, `isApprentice`, `createdDate`, `documentIdentity`, `documentScolaryCertificate`, `documentRIB`, `documentVitaleCard`, `documentResidencePermit`, `documentCVEC`) VALUES
(1, 'Bruce', 'Wayne', '2000-02-14', 1, 3, 'bruce.wayne@batman.com', '0033123456789', 2021, 42, 1, '12345678901234567', 0, 1, '2019-09-01', 'test.pdf', 'test.pdf', 'test.pdf', 'test.pdf', 'test.pdf', 'test.pdf'),
(2, 'Clark', 'Kent', '1998-01-15', 1, 2, 'clark.kent@dailyplanete.com', '0033123456789', 2023, 69, 1, '12345678901234567', 1, 1, '2019-09-01', 'test.pdf', 'test.pdf', 'test.pdf', 'test.pdf', NULL, 'test.pdf'),
(3, 'Clark', 'Bent', '1998-01-15', 1, 2, 'clark.bent@dailyplanete.com', '0033123456789', 2023, 69, 1, '12345678901234567', 1, 1, '2019-09-01', 'test.pdf', 'test.pdf', 'test.pdf', 'test.pdf', NULL, 'test.pdf');

TRUNCATE TABLE sg_member_inscription_document;
INSERT INTO sg_member_inscription_document(id, memberInscriptionId, memberInscriptionDocumentTypeId) VALUES
(5, 1, 1);

TRUNCATE TABLE treso_payment_slip;
INSERT INTO treso_payment_slip (id, missionRecapNumber, consultantName, consultantSocialSecurityNumber, addressId, email, studyId, clientName, projectLead, isTotalJeh, isStudyPaid, amountDescription, createdDate, creatorId, validatedByUa, validatedByUaDate, uaValidatorId, validatedByPerf, validatedByPerfDate, perfValidatorId) VALUES
  (1, '102383203', 'Shrek', '12320183', 84, 'shrek@fortfortlointain.fr', 1, 'L''âne', 'Le chat Potté', false, false, 'Facture payée', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), 1, false, null, null, false, null, null),
  (2, '102383204', 'Shrek', '12320183', 85, 'shrek@fortfortlointain.fr', 1, 'L''âne', 'Le chat Potté', false, false, 'Facture payée', STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'), 1, true, STR_TO_DATE('5/16/2022', '%c/%e/%Y'), 4, true, STR_TO_DATE('5/17/2022', '%c/%e/%Y'), 8);

COMMIT;

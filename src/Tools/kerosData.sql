SET AUTOCOMMIT = 0;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;


INSERT INTO cat (id, name, height) VALUES
  (1, 'Tom', 7.14),
  (2, 'Kevin', 7.14),
  (3, 'Patch', 7.14);


INSERT INTO core_user (id, username, password, expiresAt) VALUES
  (1, 'cbreeze', 'hunter11' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')),
  (2, 'mcool', 'hunter12' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')),
  (3, 'lswollo', 'hunter13' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'));


INSERT INTO core_address (id, line1, line2, postalCode, city, countryId) VALUES
  (1, '13 rue renard', null, '69100', 'lyon', 1), # member 1
  (2, '11 baker street', 'appt 501', '6930A', 'dublin', 2), # member 2
  (3, '11 fish street', 'bat. b', '91002', 'paris', 1), # member 3
  (4, '11 backbeat street', 'bat. a', '91004', 'djibouti', 3), # firm 1
  (5, '17 watcha ave', 'porte 5', '674A4', 'Leicester', 40); # firm 2


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


INSERT INTO ua_firm (id, siret, name, addressId, typeId) VALUES
  (1, '215437645', 'Cool Inc.', 4, 3),
  (2, '471245896', 'Swagger', 5, 1);

COMMIT;
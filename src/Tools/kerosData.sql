SET AUTOCOMMIT = 0;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;

TRUNCATE TABLE cat;
INSERT INTO cat (id, name, height) VALUES
  (1, 'Tom', 7.14),
  (2, 'Kevin', 7.14),
  (3, 'Patch', 7.14);

TRUNCATE TABLE core_pole;
INSERT INTO core_pole (id, label, name) VALUES
  (1, 'DSI', 'Direction des Systèmes d''Information'),
  (2, 'Bu', 'Bureau'),
  (3, 'UA', 'Unités d''affaires');

TRUNCATE TABLE core_position;
INSERT INTO core_position (label, poleId) VALUES
  ('Président(e)', 2),
  ('Chadaff', 3),
  ('Junior DSI', 1);

TRUNCATE TABLE core_department;
INSERT INTO core_department (id, label, name) VALUES
  (1, 'IF', 'Informatique'),
  (2, 'TC', 'Télécommunications, Services & Usages');

TRUNCATE TABLE core_country;
INSERT INTO core_country (label) VALUES
  ('France'),
  ('Irlande'),
  ('Sénégal');

TRUNCATE TABLE core_user;
INSERT INTO core_user (id, username, password, expiresAt) VALUES
  (1, 'cbreeze', 'hunter11' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')),
  (2, 'mcool', 'hunter12' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r')),
  (3, 'lswollo', 'hunter13' , STR_TO_DATE('5/15/2022 8:06:26 AM', '%c/%e/%Y %r'));

TRUNCATE TABLE core_gender;
INSERT INTO core_gender (label) VALUES ('M'), ('F'), ('A'), ('I');

TRUNCATE TABLE core_address;
INSERT INTO core_address (id, line1, line2, postalCode, city, countryId) VALUES
  (1, '13 rue renard', null, '69100', 'lyon', 1),
  (2, '11 baker street', 'appt 501', '6930A', 'dublin', 2),
  (3, '11 fish street', 'bat. b', '91002', 'paris', 1);

TRUNCATE TABLE core_member;
INSERT INTO core_member (id, genderId, firstName, lastName, birthdate, telephone, email, addressId, schoolYear, departmentId) VALUES
  (1, 1, 'Conor', 'Breeze', STR_TO_DATE('1975-12-25', '%Y-%m-%d'), '+332541254', 'fake.mail@fake.com', 2, 3, 1),
  (2, 1, 'Marah', 'Cool', STR_TO_DATE('1976-10-27', '%Y-%m-%d'), '+332541541', 'fake.mail2@fake.com', 1, 3, 1),
  (3, 1, 'Lolo', 'Swollo', STR_TO_DATE('1987-12-2', '%Y-%m-%d'), '+337425254', 'fake.mail3@fake.com', 3, 5, 2);

TRUNCATE TABLE core_member_position;
INSERT INTO core_member_position (memberId, positionId) VALUES
  (1, 3),
  (2, 3),
  (3, 1),
  (3, 2),
  (3, 3);
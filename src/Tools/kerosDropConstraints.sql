ALTER TABLE core_position
  DROP FOREIGN KEY `core_position_poleId_fk`;

ALTER TABLE core_address
  DROP FOREIGN KEY `core_address_countryId_fk`;

ALTER TABLE core_member
  DROP FOREIGN KEY `core_user_userId_fk`,
  DROP FOREIGN KEY `core_user_genderId_fk`,
  DROP FOREIGN KEY `core_user_addressId_fk`,
  DROP FOREIGN KEY `core_user_departmentId_fk`;

ALTER TABLE core_member_position
  DROP FOREIGN KEY `core_member_position_memberId_fk`,
  DROP FOREIGN KEY `core_position_position_positionId_fk`;

ALTER TABLE ua_firm
  DROP FOREIGN KEY `ua_firm_address_addressId_fk`,
  DROP FOREIGN KEY `ua_firm_type_typeId_fk`;

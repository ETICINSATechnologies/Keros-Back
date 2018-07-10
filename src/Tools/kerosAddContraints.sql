ALTER TABLE core_position
  ADD CONSTRAINT `core_position_poleId_fk`
FOREIGN KEY (`poleId`)
REFERENCES `core_pole` (`id`)
  ON DELETE CASCADE;

ALTER TABLE core_address
  ADD CONSTRAINT `core_address_countryId_fk`
FOREIGN KEY (`countryId`)
REFERENCES `core_country` (`id`)
  ON DELETE CASCADE;

ALTER TABLE core_member
  ADD CONSTRAINT `core_user_userId_fk`
FOREIGN KEY (`id`)
REFERENCES `core_user` (`id`)
  ON DELETE CASCADE,

  ADD CONSTRAINT `core_user_genderId_fk`
FOREIGN KEY (`genderId`)
REFERENCES `core_gender` (`id`)
  ON DELETE CASCADE,

  ADD CONSTRAINT `core_user_addressId_fk`
FOREIGN KEY (`addressId`)
REFERENCES `core_address` (`id`)
  ON DELETE CASCADE,

  ADD CONSTRAINT `core_user_departmentId_fk`
FOREIGN KEY (`departmentId`)
REFERENCES `core_department` (`id`)
  ON DELETE CASCADE;

ALTER TABLE core_member_position
  ADD CONSTRAINT `core_member_position_memberId_fk`
FOREIGN KEY (`memberId`)
REFERENCES `core_member` (`id`)
  ON DELETE CASCADE,

  ADD CONSTRAINT `core_position_position_positionId_fk`
FOREIGN KEY (`positionId`)
REFERENCES `core_position` (`id`)
  ON DELETE CASCADE;

ALTER TABLE ua_firm
  ADD CONSTRAINT `ua_firm_address_addressId_fk`
FOREIGN KEY (`addressId`)
REFERENCES `core_address` (`id`)
  ON DELETE CASCADE,

  ADD CONSTRAINT `ua_firm_type_typeId_fk`
FOREIGN KEY (`typeId`)
REFERENCES `ua_firm_type` (`id`)
  ON DELETE CASCADE;

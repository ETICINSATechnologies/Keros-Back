CREATE DATABASE IF NOT EXISTS keros;
USE keros;

CREATE TABLE cat (
  id     int NOT NULL AUTO_INCREMENT,
  name   varchar(255),
  height float(5, 2),
  PRIMARY KEY (id)
);

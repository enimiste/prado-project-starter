CREATE TABLE site_infos
(
  name VARCHAR(255) PRIMARY KEY,
  value VARCHAR(1000) DEFAULT NULL ,
  deletable TINYINT DEFAULT 0,
  editable TINYINT DEFAULT 1,
  readable TINYINT DEFAULT 0,
  created_at TIMESTAMP,
  created_by VARCHAR(255),
  updated_at TIMESTAMP,
  updated_by VARCHAR(255)
);
CREATE UNIQUE INDEX site_infos_name_uindex ON site_infos (name);
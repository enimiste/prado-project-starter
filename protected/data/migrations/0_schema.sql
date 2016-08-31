/* create users table */
CREATE TABLE users (
  username      VARCHAR(128) NOT NULL PRIMARY KEY,
  email         VARCHAR(128) NOT NULL,
  password      VARCHAR(128) NOT NULL,  /* in plain text */
  role          INTEGER NOT NULL,       /* 0: normal user, 1: administrator */
  first_name    VARCHAR(128),
  last_name     VARCHAR(128)
);
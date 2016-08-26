/* create users table */
CREATE TABLE users (
  username      VARCHAR(128) NOT NULL PRIMARY KEY,
  email         VARCHAR(128) NOT NULL,
  password      VARCHAR(128) NOT NULL,  /* in plain text */
  role          INTEGER NOT NULL,       /* 0: normal user, 1: administrator */
  first_name    VARCHAR(128),
  last_name     VARCHAR(128)
);

/* insert some initial data records for testing */
INSERT INTO users VALUES ('sadmin', 'sadmin@example.com', 'sadmin', 2, 'Super', 'Administrator');
INSERT INTO users VALUES ('admin', 'admin@example.com', 'admin', 1, 'Administrator', 'User');
INSERT INTO users VALUES ('user', 'demo@example.com', 'user', 0, 'Normal', 'User');
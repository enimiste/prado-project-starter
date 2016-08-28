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
INSERT INTO users VALUES ('sadmin', 'sadmin@example.com', '$2y$10$WRrU4uGk8FoVl7Jnu0DaB.Oa7LxszeuF8X9hcXaNtLhaUaFNBlkzO', 2, 'Super', 'Administrator');-- pwd : sadmin
INSERT INTO users VALUES ('admin', 'admin@example.com', '$2y$10$0uKw93VElULCIakmqnSQqe.yZaFO/A7DSKJFxw99Crs3LN4n/kvMq', 1, 'Administrator', 'User');-- pwd : admin
INSERT INTO users VALUES ('user', 'demo@example.com', '$2y$10$T7kyuZnecqx2wLs5SFlv8.wJGGoBc8wYKqhG7R71Se/A.OdRZ5SLq', 0, 'Normal', 'User');-- pwd : user
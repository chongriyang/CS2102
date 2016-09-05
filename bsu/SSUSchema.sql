CREATE TABLE person (
user_id SERIAL PRIMARY KEY,
name VARCHAR(256) NOT NULL,
password VARCHAR(256) NOT NULL,
email VARCHAR(256) NOT NULL UNIQUE,
birthday DATE NOT NULL,
join_date DATE NOT NULL,
gender CHAR(6) NOT NULL,
is_admin BOOLEAN NOT NULL,
is_activated BOOLEAN NOT NULL,
bookmark INT NOT NULL DEFAULT 0 CHECK(bookmark >= 0)
);

CREATE TABLE project (
project_id SERIAL PRIMARY KEY,
name VARCHAR(256) NOT NULL UNIQUE,
description VARCHAR(256),
start_date DATE,
end_date DATE,
amount NUMERIC NOT NULL DEFAULT 0.0,
raised NUMERIC NOT NULL DEFAULT 0.0);
)

CREATE TABLE bookmark (
user_id INT REFERENCES user(id),
project_id INT REFERENCES project(id);
)

CREATE TABLE category (
category_id SERIAL PRIMARY KEY,
type VARCHAR(256)
)

CREATE TABLE transaction(
transaction_id SERIAL PRIMARY KEY,
user_id INT REFERENCES user(userID),
project_id INT REFERENCES project(projectID),
amount NUMERIC NOT NULL DEFAULT 0 0
)



INSERT into project (name,description,start_date,end_date,amount,raised) 
VALUES
('books11','funny',NULL,NULL,211,11),
('books12','funny',NULL,NULL,211,11),
('crate23','not funny','2015-1-2','2016-1-2',300,0)
('books113','funny',NULL,NULL,211,11),
('books123','funny',NULL,NULL,211,11),
('crate223','not funny','2015-1-2','2016-1-2',300,0)
('books1213','funny',NULL,NULL,211,11);

INSERT INTO person (name, email, password, birthday, join_date, gender, is_admin, is_activated)
VALUES 
('admin', 'admin@hotmail.com', 'Password123', '02-10-2000', '2000-01-01', 'male', 'TRUE', 'TRUE'),
('user', 'user@hotmail.com', 'Password123', '02-10-2000', '2000-01-1', 'female', 'FALSE', 'TRUE');
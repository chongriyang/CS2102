CREATE TABLE person (
user_id SERIAL PRIMARY KEY,
name VARCHAR(256) NOT NULL,
password CHAR(64) NOT NULL,
email VARCHAR(256) NOT NULL UNIQUE,
birthday DATE NOT NULL,
join_date DATE NOT NULL,
gender CHAR(6) NOT NULL,
is_admin BOOLEAN NOT NULL,
is_activated BOOLEAN NOT NULL,
bookmark INT NOT NULL DEFAULT 0 CHECK(bookmark >= 0)
);

CREATE TABLE cookie (
user_id INT PRIMARY KEY REFERENCES person(user_id),
identifier CHAR(64) NOT NULL,
key CHAR(32) NOT NULL,
timeout TIMESTAMP NOT NULL
);

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
project_owner VARCHAR(256),
description VARCHAR(256),
video_url VARCHAR(256),
start_date DATE,
end_date DATE,
amount NUMERIC NOT NULL DEFAULT 0.0,
raised NUMERIC NOT NULL DEFAULT 0.0);

CREATE TABLE bookmark (
user_id INT REFERENCES person(user_id),
project_id INT REFERENCES project(project_id));

CREATE TABLE category (
category_id SERIAL PRIMARY KEY,
type VARCHAR(256));

CREATE TABLE transaction(
transaction_id SERIAL PRIMARY KEY,
user_id INT REFERENCES user(userID),
project_id INT REFERENCES project(projectID),
amount NUMERIC NOT NULL DEFAULT 0 0;
)



INSERT into project (project_id,name,project_owner,description,video_url,start_date,end_date,amount,raised) 
VALUES
(1,'The First Desktop Waterjet Cutter','WAZER','Cut any material with digital precision using high pressure water. A compact waterjet for every workshop.',
	'https://ksr-video.imgix.net/projects/2567739/video-704119-h264_high.mp4','2016-05-01','2016-11-01',500000,156000),
(2,'The Arc Board: Worlds Smallest Lightest Electric Skateboard','The Arc Boards Team','The worlds most portable last mile vehicle. Only 22" in length and weighing just 3.5kg, an 18km range and speeds up to 25km/h.',
	'https://ksr-video.imgix.net/projects/2620515/video-703937-h264_high.mp4','2016-9-1','2016-9-30',100000,55365),
(3,'Slow Dance – A Frame that Slows Down Time','Jeff Lieberman','Slow Dance makes things move in ways you never thought possible. For all those who love mystery, beauty, and wonder. Get one for $249.',
	'https://ksr-video.imgix.net/projects/2543846/video-692291-h264_high.mp4','2015-1-2','2016-1-2',70000,55665),
('books12','funny',NULL,NULL,211,11),
('crate23','not funny','2015-1-2','2016-1-2',300,0)
('books113','funny',NULL,NULL,211,11),
('books123','funny',NULL,NULL,211,11),
('crate223','not funny','2015-1-2','2016-1-2',300,0)
('books1213','funny',NULL,NULL,211,11);

INSERT INTO person (name, email, password, birthday, join_date, gender, is_admin, is_activated)
VALUES 
('admin', 'admin@hotmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-01-01', 'male', 'TRUE', 'TRUE'),
('user', 'user@hotmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-01-1', 'female', 'FALSE', 'TRUE');
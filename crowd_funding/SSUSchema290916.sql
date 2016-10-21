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

CREATE TABLE category (
category_id SERIAL PRIMARY KEY,
type VARCHAR(256)
);

CREATE TABLE project (
project_id SERIAL PRIMARY KEY,
user_id INT REFERENCES person(user_id), 
category_id	INT REFERENCES category(category_id),
name VARCHAR(256) NOT NULL UNIQUE,
description VARCHAR(256),
start_date DATE,
end_date DATE,
amount NUMERIC NOT NULL DEFAULT 0.0,
raised NUMERIC NOT NULL DEFAULT 0.0
);

CREATE TABLE bookmark (
user_id INT REFERENCES person(user_id),
project_id INT REFERENCES project(project_id)
);

CREATE TABLE transaction(
transaction_id SERIAL PRIMARY KEY,
user_id INT REFERENCES person(user_id), 
project_id INT REFERENCES project(project_id), 
amount NUMERIC NOT NULL DEFAULT 0.0,
date_time TIMESTAMP NOT NULL
);

INSERT INTO category (type) 
VALUES
('Art'),
('Comics'),
('Crafts'),
('Dance'),
('Design'),
('Fashion'),
('Film & Video'),
('Food'),
('Games'),
('Journalism'),
('Music'),
('Photography'),
('Publishing'),
('Technology'),
('Theater');

INSERT INTO person (name, email, password, birthday, join_date, gender, is_admin, is_activated)
VALUES 
('admin', 'admin@hotmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'TRUE', 'TRUE'),
('user', 'user@hotmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'female', 'FALSE', 'TRUE'),
('richman', 'richman@hotmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE'),
('john', 'john@hotmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE'),
('david', 'david@hotmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE'),
('WAZER', 'wazer@hotmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE'),
('The Arc Boards Team','thearcboardsteam@hotmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE'),
('Jeff Lieberman','jefflieberman@hotmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE'),
('Satsuki Shibuya','satsukishibuya@hotmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'female', 'FALSE', 'TRUE'),
('sek','sek@hotmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE'),
('David Gerrolds', 'davidgerrolds@hotmail.com','46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE'),
('Kammok', 'kammok@hotmail.com','46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'female', 'FALSE', 'TRUE'),
('Ross Connelly', 'rossconnelly@hotmail.com','46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE'),
('Meyer Optik USA', 'meyeroptikusa@hotmail.com','46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE'),
('finkle', 'finkle@hotmail.com','46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE');

INSERT into project (user_id,category_id,name,description,start_date,end_date,amount) 
VALUES
(6,14,'The First Desktop Waterjet Cutter','Cut any material with digital precision using high pressure water. A compact waterjet for every workshop.',
	'2016-05-01','2016-11-01',500000),
(7,14,'The Most Portable Electric Skateboard in the World','The worlds most portable last mile vehicle. Only 22" in length and weighing just 3.5kg, an 18km range and speeds up to 25km/h.',
	'2016-9-1','2016-9-30',100000),
(8,4,'Slow Dance – A Frame that Slows Down Time','Slow Dance makes things move in ways you never thought possible. For all those who love mystery, beauty, and wonder. Get one for $249.',
	'2015-1-2','2016-1-2',70000),
(9,10,'MOMENT: A Book by Satsuki Shibuya','A book of poetry and paintings for anyone seeking a moment of clarity in the everyday',
	'2016-2-16','2017-2-16',15000),
(10,14,'Altered:Nozzle - Same tap. 98% less water.','Experience mist with the worlds most extreme water saving nozzle. ',
	'2016-1-16','2016-9-17',250000),
(11,14,'Oh, The Places You will Boldly Go!','Support David Gerrolds new enterprise!',
	'2016-2-10','2016-8-16',27364),
(12,14,'Sunda: The Next Level 2+ Person Tent & All-in-One Hammock','Clean design meets complete versatility. The Sunda is a game-changing tent hammock, designed for any occasion, any terrain, any time.',
	'2016-1-16','2016-9-17',232120),
(13,14,'Save The Hardwick Gazette','The Hardwick Gazette was founded in 1889. Help ensure its legacy and save a foundation block of democracy.',
	'2016-1-16','2016-9-17',322820),
(14,14,'Bring back the Fabulous Wonder Bokeh Lens: Primoplan','From melting, swirling bubbles to soft and creamy bokeh, yet astonishing sharpness: Discover all those facets in one true art lens',
	'2016-1-16','2016-9-17',132820),
(15,14, 'U R - A musical graphic-novel theatre experience for 5','Help me take this show for small audiences (5 people) on the road by supporting the creation of an updated theatrical touring trunk.',
	'2016-1-16','2016-9-17',6000);


###################################################################
## for REFERENCES attributes, to enable cascade delete and update
## ON UPDATE CASCADE ON DELETE CASCADE
###################################################################

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
video_url VARCHAR(256),
name VARCHAR(256) NOT NULL UNIQUE,
description VARCHAR(256),
start_date DATE,
end_date DATE,
amount NUMERIC NOT NULL DEFAULT 0.0,
raised NUMERIC NOT NULL DEFAULT 0.0);

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
('Theater'),
('Education');

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
('finkle', 'finkle@hotmail.com','46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE'),
('Russel', 'joker@gmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE' ),
('Johnny', 'bubba@gmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE' ),
('Lucy Lee', 'mslucy@gmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'female', 'FALSE', 'TRUE' ),
('Wendy Lai', 'ohlala@gmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'female', 'FALSE', 'TRUE' ),
('Linda Wang', 'lindawang@gmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'female', 'FALSE', 'TRUE' ),
('Peter Lam', 'runner99@gmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE' ),
('Chan Meng Fai', 'Fafafa@gmail.com', '46b935e238f532cf1732a9061606f8cdb9a027d099476ca0f3d036f7490c7ef0', '02-10-2000', '2000-12-01', 'male', 'FALSE', 'TRUE' );


INSERT into project (user_id,category_id,name,description,video_url,start_date,end_date,amount,raised) 
VALUES
(6,14,'The First Desktop Waterjet Cutter','Cut any material with digital precision using high pressure water. A compact waterjet for every workshop.',
	'https://ksr-video.imgix.net/projects/2567739/video-704119-h264_high.mp4','2016-05-01','2016-11-01',500000,156000),
(7,14,'The Most Portable Electric Skateboard in the World','The worlds most portable last mile vehicle. Only 22" in length and weighing just 3.5kg, an 18km range and speeds up to 25km/h.',
	'https://ksr-video.imgix.net/projects/2620515/video-703937-h264_high.mp4','2016-9-1','2016-9-30',100000,55365),
(8,4,'Slow Dance – A Frame that Slows Down Time','Slow Dance makes things move in ways you never thought possible. For all those who love mystery, beauty, and wonder. Get one for $249.',
	'https://ksr-video.imgix.net/projects/2543846/video-692291-h264_high.mp4','2015-1-2','2016-1-2',70000,55665),
(9,10,'MOMENT: A Book by Satsuki Shibuya','A book of poetry and paintings for anyone seeking a moment of clarity in the everyday',
	'https://ksr-video.imgix.net/projects/2558463/video-698737-h264_high.mp4','2016-2-16','2017-2-16',15000,18175),
(10,14,'Altered:Nozzle - Same tap. 98% less water.','Experience mist with the worlds most extreme water saving nozzle. ',
	'https://ksr-video.imgix.net/projects/2295061/video-702307-h264_high.mp4','2016-1-16','2016-9-17',250000,40887),
(11,14,'Oh, The Places You will Boldly Go!','Support David Gerrolds new enterprise!',
	'https://ksr-video.imgix.net/projects/2557802/video-699945-h264_high.mp4','2016-2-10','2016-8-16',27364,27000),
(12,14,'Sunda: The Next Level 2+ Person Tent & All-in-One Hammock','Clean design meets complete versatility. The Sunda is a game-changing tent hammock, designed for any occasion, any terrain, any time.',
	'https://ksr-video.imgix.net/projects/2316265/video-709169-h264_high.mp4','2016-1-16','2016-9-17',232120,170887),
(13,14,'Save The Hardwick Gazette','The Hardwick Gazette was founded in 1889. Help ensure its legacy and save a foundation block of democracy.',
	'http://www.hardwickgazette.com','2016-1-16','2016-9-17',322820,270887),
(14,14,'Bring back the Fabulous Wonder Bokeh Lens: Primoplan','From melting, swirling bubbles to soft and creamy bokeh, yet astonishing sharpness: Discover all those facets in one true art lens',
	'https://ksr-video.imgix.net/projects/2617678/video-702382-h264_high.mp4','2016-1-16','2016-9-17',132820,8887),
(15,14, 'U R - A musical graphic-novel theatre experience for 5','Help me take this show for small audiences (5 people) on the road by supporting the creation of an updated theatrical touring trunk.',
	'https://ksr-video.imgix.net/projects/2603197/video-703158-h264_high.mp4','2016-1-16','2016-9-17',6000,1220);


INSERT INTO transaction (user_id,project_id,amount,date_time)
VALUES
(6,1,100.0, TIMESTAMP'2016-10-25 07:15:31.123456789'),
(7,2, 250.0, TIMESTAMP'2016-10-21 07:15:31.123456789'),
(8,3, 500.0, TIMESTAMP'2016-10-22 07:15:31.123456789'),
(9,4, 55.0, TIMESTAMP'2016-10-27 07:15:31.123456789'),
(10,5, 11.0, TIMESTAMP'2016-10-22 07:15:31.123456789'),
(11,6,55.0, TIMESTAMP'2016-10-18 07:15:31.123456789'),
(12,7, 220.0, TIMESTAMP'2016-10-14 07:15:31.123456789'),
(13,8, 555.0, TIMESTAMP'2016-10-19 07:15:31.123456789'),
(14,9, 132.0, TIMESTAMP'2016-10-18 07:15:31.123456789'),
(15,10, 238.0, TIMESTAMP'2016-10-19 07:15:31.123456789');

INSERT INTO bookmark (user_id, project_id)
VALUES
(6,1),
(7,2),
(8,3),
(9,4),
(10,5),
(11,6),
(12,7),
(13,8),
(14,9),
(15,10);

###################################################################
## for REFERENCES attributes, to enable cascade delete and update
## ON UPDATE CASCADE ON DELETE CASCADE
###################################################################

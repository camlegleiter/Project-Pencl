DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS classmates;
DROP TABLE IF EXISTS classbooks
DROP TABLE IF EXISTS classes;
DROP TABLE IF EXISTS notebooks;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS coming_soon_emails;

CREATE TABLE users (             
	userid INT NOT NULL AUTO_INCREMENT,             
	username VARCHAR(30) NOT NULL UNIQUE,             
	password VARCHAR(64) NOT NULL,           
	salt VARCHAR(40) NOT NULL,      
	email VARCHAR(128) NOT NULL,      
	created DATETIME NOT NULL,      
	ip VARCHAR(40) NOT NULL,      
	token VARCHAR(256),      
	PRIMARY KEY(userid) );

CREATE TABLE admins (             
	userid  INT NOT NULL,             
	level INT NOT NULL,      
	created DATETIME NOT NULL,      
	PRIMARY KEY(userid),      
	FOREIGN KEY(userid) references users(userid));

CREATE TABLE notebooks (
	id INT NOT NULL AUTO_INCREMENT,
	userid INT NOT NULL,      
	name VARCHAR(30) NOT NULL,
	description VARCHAR(256),  
	created DATETIME NOT NULL,
	modified DATETIME,
	PRIMARY KEY(id),
	FOREIGN KEY(userid) references users(userid));

CREATE TABLE settings (
	userid  INT NOT NULL,          
	PRIMARY KEY(userid),
	FOREIGN KEY(userid) references users(userid));

CREATE TABLE coming_soon_emails (
	email VARCHAR(64) collate utf8_unicode_ci NOT NULL,
	ts TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(email) );
	
CREATE TABLE IF NOT EXISTS classes (
	id int NOT NULL AUTO_INCREMENT,
	name varchar(31) NOT NULL,
	description varchar(256) NOT NULL,
	owner int NOT NULL,
	password varchar(256),
	PRIMARY KEY (id),
	FOREIGN KEY(owner) references users( userid )
)

CREATE TABLE IF NOT EXISTS classmates (
	userid int NOT NULL,
	classid int NOT NULL,
	FOREIGN KEY(classid) references classes( id ),
	FOREIGN KEY(userid) references users( userid )
)

CREATE TABLE IF NOT EXISTS classbooks (
	notebookid int NOT NULL,
	classid int NOT NULL,
	FOREIGN KEY(classid) references classes( id ),
	FOREIGN KEY(notebookid) references notebooks( id )
)

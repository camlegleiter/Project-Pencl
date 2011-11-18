DROP TABLE IF EXISTS admins;
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

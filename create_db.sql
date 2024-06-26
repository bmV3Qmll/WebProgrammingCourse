CREATE DATABASE assignment;
use assignment;
CREATE TABLE users (uid INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL);
CREATE TABLE courses (cid INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) UNIQUE, image_path VARCHAR(255) DEFAULT "assets/blank.jpg", uid INT, FOREIGN KEY (uid) REFERENCES users(uid));
CREATE TABLE tests (tid INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255) NOT NULL, description TEXT, cid INT, FOREIGN KEY (cid) REFERENCES courses(cid), no_easy INT, no_medium INT, no_hard INT, uid INT, FOREIGN KEY (uid) REFERENCES users(uid));
CREATE TABLE questions (qid INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255) NOT NULL, description TEXT, cid INT, FOREIGN KEY (cid) REFERENCES courses(cid), options JSON NOT NULL, difficulty VARCHAR(255) NOT NULL, image_path VARCHAR(255) DEFAULT NULL, multiple BOOLEAN NOT NULL, uid INT, FOREIGN KEY (uid) REFERENCES users(uid));
CREATE TABLE test_question (tid INT, qid INT, FOREIGN KEY (tid) REFERENCES tests(tid), FOREIGN KEY (qid) REFERENCES questions(qid));
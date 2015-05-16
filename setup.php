<!DOCTYPE html>
<html>
  <head>
  </head>
  <body>

<?php
  require_once 'functions.php';

  createTable('user',
              'userid INTEGER(6) NOT NULL AUTO_INCREMENT,
               name VARCHAR(25) NOT NULL,
	             email VARCHAR(30) NOT NULL,
               netid VARCHAR(6) NOT NULL,
	             password VARCHAR(18) NOT NULL,
               isTA BIT(1),
	             isProfessor BIT(1),
               PRIMARY KEY(userid)');

  createTable('class',
 	            'courseid int(6) NOT NULL AUTO_INCREMENT,
 	             coursename varchar(20) NOT NULL, 
 	             semester varchar(11) NOT NULL, 
 	             school varchar(20) NOT NULL, 
 	             profid int(6) NOT NULL, 
 	             PRIMARY KEY (courseid, semester, profid), 
 	             FOREIGN KEY(profid) REFERENCES user(userid) ON DELETE CASCADE');

  createTable('roster',
 	            'courseid int(6) NOT NULL AUTO_INCREMENT,
 	             studentid int(6) NOT NULL, 
 	             PRIMARY KEY (courseid, studentid), 
 	             FOREIGN KEY(courseid) REFERENCES class(courseid) ON DELETE CASCADE, 
 	             FOREIGN KEY(studentiD) REFERENCES user(useriD) ON DELETE CASCADE');

  createTable('picture', 
  	          'imageid INTEGER(6) NOT NULL AUTO_INCREMENT,
  	           imagename VARCHAR(50) NOT NULL,
               image MEDIUMBLOB,
               imagetype VARCHAR(10),
               imagesize INTEGER(8),
               timestamp TIMESTAMP,
  	           PRIMARY KEY(imageid, imagename, timestamp)');

  createTable('post', 
              'artid INTEGER(6) AUTO_INCREMENT NOT NULL,
               userid INTEGER(6) NOT NULL,
               courseid INTEGER(6) NOT NULL,
               imagetimestamp TIMESTAMP NOT NULL,
               timestamp TIMESTAMP,
               title VARCHAR(20),
               likes INT(4), 
               text VARCHAR(200),
               INDEX(userid,courseid),
	             FOREIGN KEY(courseid) REFERENCES class(courseid) ON DELETE CASCADE, 
 	             FOREIGN KEY(userid) REFERENCES user(userid) ON DELETE CASCADE,
	             PRIMARY KEY(artid)');

  createTable('comment', 
              'artid int(6) NOT NULL, 
	             userid int(6) NOT NULL, 
	             timestamp TIMESTAMP, 
	             text varchar (200), 
	             likes int(4), 
	             PRIMARY KEY (artid, userid, timestamp), 
	             FOREIGN KEY(artid) REFERENCES post(artid) ON DELETE CASCADE, 
	             FOREIGN KEY(userid) REFERENCES user(userid) ON DELETE CASCADE');

  createTable('profiles',
              'user VARCHAR(16),
               text VARCHAR(4096),
               INDEX(user(6))');
?>

    <br>...done.
  </body>
</html>

CREATE TABLE tags (
       name TEXT NOT NULL,
       tags TEXT NOT NULL,
       glat DECIMAL(10, 8) NOT NULL,
       glon DECIMAL(11, 8) NOT NULL,
       link TEXT,
       grad FLOAT
) Engine="MyISAM" DEFAULT CHARSET=utf8;

CREATE TABLE votement (
       name TEXT NOT NULL,
       glat DECIMAL(10, 8) NOT NULL,
       glon DECIMAL(11, 8) NOT NULL,
       distance FLOAT,
       vote BIGINT
) Engine="MyISAM" DEFAULT CHARSET=utf8;

CREATE TABLE location (
       id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
       name TEXT NOT NULL,
       KEY name_idx (name(2048)),
       glat DECIMAL(10, 8) NOT NULL,
       glon DECIMAL(11, 8) NOT NULL,
       ggeo GEOMETRY NOT NULL,
       SPATIAL INDEX(ggeo),
       link TEXT,
       vote BIGINT REFERENCES votement(vote),
       distance DECIMAL(10, 8),
       time TIMESTAMP
) Engine="MyISAM" DEFAULT CHARSET=utf8;

DELIMITER $$

CREATE TRIGGER on_vote
       AFTER INSERT ON location
       FOR EACH ROW
       	   BEGIN
	      INSERT INTO votement (name, glat, glon, distance, vote) VALUES (NEW.name, NEW.glat, NEW.glon, 0, 0);
              UPDATE votement SET distance = (6371.3929 * acos (cos ( radians(NEW.glat) ) * cos( radians( glat ) ) * cos( radians( glon ) - radians(NEW.glon) ) + sin ( radians(NEW.glat)) * sin( radians( glat )))) WHERE name = NEW.name;
	      UPDATE votement SET vote = vote + 1 WHERE name = NEW.name;
	   END$$


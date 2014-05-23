DROP TABLE votement;
DROP TABLE location;

CREATE TABLE votement (
       name TEXT NOT NULL,
       glat DECIMAL(10, 8) NOT NULL,
       glon DECIMAL(11, 8) NOT NULL,
       distance FLOAT,
       vote BIGINT
);

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
       distance DECIMAL(10, 8)
) Engine="MyISAM" DEFAULT CHARSET=utf8;

DELIMITER $$

CREATE TRIGGER on_vote
       AFTER INSERT ON location
       FOR EACH ROW
       	   BEGIN
	      INSERT INTO votement (name, glat, glon) VALUES (NEW.name, NEW.glat, NEW.glon);
              UPDATE votement SET distance = (6371.3929 * acos (cos ( radians(NEW.glat) ) * cos( radians( glat ) ) * cos( radians( glon ) - radians(NEW.glon) ) + sin ( radians(NEW.glat)) * sin( radians( glat )))) WHERE name = NEW.name;
	   END$$

INSERT INTO votement (name, glat, glon, vote) VALUES ('California', 30, -120, 1);
INSERT INTO location (name, glat, glon, ggeo, link, vote) VALUES ('California', 32.35201083799544, -12.9352903165217, POINT(37.35201083799544,-121.9352903165217), 'http://www.tesla.com/', 1);


SELECT * from votement;
SELECT * from location;

INSERT INTO location (name, glat, glon, ggeo, link, vote) VALUES ('California', 35.35201083799544, -179.9352903165217, POINT(37.35201083799544,-121.9352903165217), 'http://www.tesla.com/', 1);

SELECT * from votement;
SELECT * from location;

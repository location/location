DROP TABLE location;
CREATE TABLE location (
       id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
       name TEXT NOT NULL,
       KEY name_idx (name(2048)),
       glat DECIMAL(10, 8) NOT NULL,
       glon DECIMAL(11, 8) NOT NULL,
       ggeo GEOMETRY NOT NULL,
       SPATIAL INDEX(ggeo)
) Engine="MyISAM";

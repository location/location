-- 10 miles away Honda

INSERT INTO location (name, glat, glon, ggeo, link) VALUES ('Honda', 62.4625998, 6.3676068, POINT(62.4625998,6.3676068), 'http://www.honda.no/');

SELECT * FROM location WHERE MBRContains(GeomFromText('LineString(62.546541934481 6.4576158009001,62.378657665519 6.2775977990999)'), ggeo);

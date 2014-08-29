INSERT INTO location (name, glat, glon, ggeo, link) VALUES ('California', 37.35201083799544, -121.9352903165217, POINT(37.35201083799544,-121.9352903165217), 'http://www.tesla.com/');

/* Distance: 5000 miles */
SELECT * FROM location WHERE MBRContains(GeomFromText('LineString(79.672569934946 -76.930789866477, -4.9685482589552 -166.93979076657)'), ggeo);

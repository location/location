INSERT INTO location (name, glat, glon, ggeo, link) VALUES ('Taco', 35.0818394, -80.915246, POINT(35.0818394,-80.915246), 'http://tacobell.com/');

/* Distance: 5000 miles */
SELECT * FROM location WHERE MBRContains(GeomFromText('LineString(79.672569934946 -76.930789866477, -4.9685482589552 -166.93979076657)'), ggeo);


package gl.location;

import java.net.*;
import java.io.*;

public class Location {

	private static String name = "Tre";
	private static String link = "http://location.gl/Tre.list";
	private static String glat = "0.0";
	private static String glon = "0.0";
	private static String grad = "0";
	private static String vote = "01";

	public static void main (String[] args) throws Exception {
		try {
			list(name, link, glat, glon, grad, vote);
		} catch (MalformedURLException e) {
			System.out.println("Invalid URL");
		}
		
	}

	public static void list (String name,
				 String link, 
				 String glat,
				 String glon,
				 String grad,
				 String vote) throws Exception {
		
		System.out.println("OK");

		URL loc = new URL (link);
		
		try {
			InputStream in = loc.openStream();
			BufferedInputStream bufIn = new BufferedInputStream(in);
			
			for (;;) {
				int data = bufIn.read();
				if (data == -1) {
					break;
				} else {
					System.out.print ((char) data);
				}
			}
			
		} catch (MalformedURLException e) {
			System.out.println("Invalid URL");
		}
		
	}
}

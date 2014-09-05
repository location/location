<?php
require_once("lib/config.php");

$airport = $_GET['airport'];

$artists = file($airport . ".pls");

$start = microtime();

$spotify = MetaTune::getInstance();
try
{
	$length = count($artists);
	$random = rand(0,$length-1);
	$tracks = $spotify->searchTrack($artists[$random]);
		
	// Check for hits. MetaTune#searchTrack returns empty array in case of no result. 
	if (count($tracks) < 1)
	{
		echo "<p>No results.</p>\n";
	}
	else
	{
		
		$firstTrack = $tracks[0];
		
		// Might have more than one artist. Save the poissible array
		$artist = $firstTrack->getArtist();
		
		header("Location: " . $firstTrack->getURL());
		$out = "<ul>\n";
		$out .= '<li><strong>Song:</strong> <a href="' . $firstTrack->getURL() . '">' . $firstTrack->getTitle() . '</a></li>' . "\n";
		$out .= '<li><strong>Artist:</strong> <a href="' . ((is_array($artist)) ? $artist[0]->getURL() : $artist->getURL()) . '">' . $firstTrack->getArtistAsString() . '</a></li>' . "\n";
		$out .= '<li><strong>Album:</strong> <a href="' . $firstTrack->getAlbum()->getURL() . '">' . $firstTrack->getAlbum() . '</a></li>' . "\n";
		$out .= '<li><strong>Duration:</strong> ' . $firstTrack->getLengthInMinutesAsString() . '</li>' . "\n";
		$out .= '<li><strong>Popularity:</strong> ' . $firstTrack->getPopularityAsPercent() . '%</li>' . "\n";
		$out .= "</ul>\n";
		// echo $out;
	}
}
catch (MetaTuneException $ex)
{
	die("<pre>Error\n" . $ex . "</pre>");
}
?>

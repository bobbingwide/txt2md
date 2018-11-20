<?php // (C) Copyright Bobbing Wide 2017

/**
 * Syntax: j2p.php screenshot
 *
 * @param string $jpg - file name of jpeg
 * @param string $png - file name of png
 */
function jpg2png( $jpg, $png, $compression=9 ) {
	$image = imagecreatefromjpeg( $jpg );
	//if ( $compression )	{
	//	$png = "screenshot-$compression.png";
	//}
	imagepng( $image, $png, $compression );
}


// Command line version
if ( $argc > 1 ) {
	$source = $argv[1];
	echo $source;
	jpg2png( $source, "screenshot.png" );
	//for ( $compression = 1; $compression <= 9; $compression++ ) {
	//	jpg2png( $source, "screenshot.png", $compression );
	//} 
}




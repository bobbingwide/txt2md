<?php // (C) Copyright Bobbing Wide 2017

/**
 * Syntax: p2j.php banner file
 *
 * @param string $png - file name of png
 * @param string $jpg - file name of jpeg
 */
function png2jpg( $png, $jpg ) {
	$image = imagecreatefrompng( $png );
	if ( $image ) {
	//if ( $compression )	{
	//	$png = "screenshot-$compression.png";
	//}
		imagejpeg( $image, $jpg );
	} else {
		gob();
	}
}


// Command line version
if ( $argc > 1 ) {
	$source = $argv[1];
	
	echo $source;
	$jpg = str_replace( ".png", ".jpg", $source );
	png2jpg( $source, $jpg  );
	//for ( $compression = 1; $compression <= 9; $compression++ ) {
	//	jpg2png( $source, "screenshot.png", $compression );
	//} 
}




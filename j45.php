<?php

// (C) Copyright Bobbing Wide 2021
//
// Syntax: j45.php sourcedirectory targetdirectory
// If target directory is not specified then we'll update the original file
//
//

// Command line version
if ( $argc > 1 ) {
	$source=$argv[1];
}	 else {
	$source = 'C:/apache/htdocs/oik-plugins/banners/';
	//$target = 'C:/apache/htdocs/oik-plugins/banners-quality/';
}
$quality = 45;

echo $source;


$files = list_files( $source );
$qualities = [ 75, 62, 50, 25 ];
echo "File," . implode( ',', $qualities);
foreach ( $files as $file ) {
	j45( $file, $qualities);

}







function list_files( $source_directory ) {
	$files = glob( $source_directory. '*-772x250.jpg' );
	//print_r( $files );
	return $files;

}


/**
 * Syntax: p2j.php banner file
 *
 * @param string $png - file name of png
 * @param string $jpg - file name of jpeg
 */
function j45( $source_file, $qualities ) {
	$source_size = filesize( $source_file );
	$target_size = null;
	$image=imagecreatefromjpeg( $source_file );
	if ($image ) {
		echo basename( $source_file, '.jpg' );
		echo ',';
		echo $source_size;
		foreach ( $qualities as $quality ) {
			$target_file=str_replace( '.jpg', "-$quality.jpg", $source_file );
			$target_file=str_replace( '/banners/', '/banners-quality/', $target_file );
			//echo $target_file;
			//gob();
			imagejpeg( $image, $target_file, $quality );
			$target_size=filesize( $target_file );
			echo ',';
			echo $target_size;
		}
 	} else {

	}
	echo PHP_EOL;
}




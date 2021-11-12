<?php

// (C) Copyright Bobbing Wide 2021
//
// Syntax: w45.php sourcedirectory targetdirectory
// If target directory is not specified then we'll update the original file
//
//

// Command line version
if ( $argc > 1 ) {
	$source=$argv[1];
}	 else {
	$source = 'C:/apache/htdocs/oik-plugins/banners/';
	//$source = 'C:/backups-SB/herbmiller.me/banners/';
	//$target = 'C:/apache/htdocs/oik-plugins/banners-quality/';
}
//$quality = 45;

echo $source;


$files = list_files( $source );
$qualities = [ 100,95,90,85,80,75,70,65,60,55, 50,45,10,1 ];
$qualities = [ 25, 50, 62, 75  ];
echo "File," . implode( ',', $qualities);
echo PHP_EOL;
foreach ( $files as $file ) {
	w45( $file, $qualities);
}

function list_files( $source_directory ) {
	$files = glob( $source_directory. '*-772x250.jpg' );
	//print_r( $files );
	return $files;

}


/**
 *
 *
 * @param string $png - file name of png
 * @param string $jpg - file name of jpeg
 */
function w45( $source_file, $qualities ) {
	$source_size = filesize( $source_file );
	$target_size = null;
	$image=imagecreatefromjpeg( $source_file );
	if ($image ) {
		$basename = basename( $source_file, '.jpg' );
		$basename = str_replace( 'banner-772x250', '', $basename );
		echo $basename;
		echo ',';
		echo $source_size;
		foreach ( $qualities as $quality ) {
			$target_file=str_replace( '.jpg', "-$quality.webp", $source_file );
			$target_file=str_replace( '/banners/', '/banners-webp/', $target_file );
			//echo $target_file;
			//gob();
			imagewebp( $image, $target_file, $quality );
			$target_size=filesize( $target_file );
			echo ',';
			echo $target_size;
		}
 	} else {

	}
	echo PHP_EOL;
}




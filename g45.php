<?php
/**
 * @copyright (C) Copyright Bobbing Wide 2021
 * @package txt2md
 *
 * Syntax: g45.php sourcedirectory
 *
 * Converts all .jpg and .png files in the source folder ( /jpg/ )
 * to .webp files in the target folder ( /webp/ )
 */

// Command line version
if ( $argc > 1 ) {
    $source=$argv[1];
}	 else {
    $source = 'C:/apache/htdocs/oik-plugins/banners/';
    $source = 'C:/backups-SB/herbmiller.me/banners/';
    // For Garden Vista Group

    //$target = 'C:/apache/htdocs/oik-plugins/banners-quality/';
}
//$quality = 45;

echo $source;

$source = 'C:/backups-SB/gardenvista.co.uk/jpg/';
$files = list_files( $source, '*.jpg' );
$qualities = [ 100,95,90,85,80,75,70,65,60,55, 50,45,10,1 ];
$qualities = [ 65, 60, 55 ];
echo "File," . implode( ',', $qualities);
echo PHP_EOL;
foreach ( $files as $file ) {
    w45( $file, $qualities, 'jpg');
}

$source = 'C:/backups-SB/gardenvista.co.uk/png/';
$files = list_files( $source, '*.png' );
$qualities = [ 100,95,90,85,80,75,70,65,60,55, 50,45,10,1 ];
$qualities = [ 65, 60, 55 ];
echo "File," . implode( ',', $qualities);
echo PHP_EOL;
foreach ( $files as $file ) {
    w45( $file, $qualities, 'png');
}

function list_files( $source_directory, $mask ) {
    //$files = glob( $source_directory. '*-772x250.jpg' );
    $files = glob( $source_directory. $mask );
    //print_r( $files );
    return $files;

}


/**
 * Convert .jpg to .webp
 *
 */
function w45( $source_file, $qualities, $ext ) {
    $source_size = filesize( $source_file );
    $target_size = null;
    if ( 'jpg' === $ext) {
        $image = imagecreatefromjpeg($source_file);
    } else {
        $image = imagecreatefrompng( $source_file);
    }
    if ($image ) {
        //image2webp( $image, $basename, );
        $basename = basename( $source_file, ".$ext" );
        //$basename = str_replace( 'banner-772x250', '', $basename );
        echo $basename;
        echo ',';
        echo $source_size;
        foreach ( $qualities as $quality ) {
            $target_file=str_replace( ".$ext", "-$quality.webp", $source_file );
            //$target_file=str_replace( '/banners/', '/banners-webp/', $target_file );
            // For GardenVista Group
            $target_file=str_replace( "/$ext/", '/webp/', $target_file );
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
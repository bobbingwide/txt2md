<?php

/**
 * Gets the files listed in the apache access log
 * into the appropriate folder in \backups-SB\gardenvista
 */

$access_log = 'C:/apache/htdocs/herb/cts/2021/Oct17.txt';
$lines = file( $access_log );
print_r( $lines );

foreach ( $lines as $line ) {
    $bits = explode( " ", $line );
    $start = strpos( $line, "GET /");
    $start += 4;
    $end = strpos( $line, " HTTP/2.0" );
    $file = substr( $line, $start, $end-$start );
    //echo $file;


    $basename = basename( $file );
    $ext = pathinfo( $file, PATHINFO_EXTENSION );

    $source = 'C:/apache/htdocs/gardenvista' .  $file;
    $target = null;
    switch ( $ext ) {
        case 'jpg':
        case 'png':
            $target = "C:/backups-SB/gardenvista.co.uk/$ext/" . $basename ;
            //copy( $source, $target);
                break;
    }
    if ( $target ) {
        echo "copy source: " .  $source;
        echo " to target: " . $target;
        echo PHP_EOL;
        copy( $source, $target );
    }

}

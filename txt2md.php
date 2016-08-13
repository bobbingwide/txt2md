<?php // (C) Copyright Bobbing Wide 2014-2016

/*
Plugin Name: txt2md
Plugin URI: http://www.oik-plugins.com/oik-plugins/txt2md
Description: Convert a WordPress readme.txt file to Github README.md 
Version: 0.0.1
Author: bobbingwide
Author URI: http://www.oik-plugins.com/author/bobbingwide
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

    Copyright 2014-2016 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/
/**
 * Simple readme.txt to readme.md convertor for WordPress plugins also hosted on GitHub
 * 
 * It assumes that the readme.txt file is the master.
 * 
 * - Converts first line to a heading level 1
 * - then adds the banner image, if present in assets
 * - Converts === heading === to ### heading
 * - Converts foo: bar to * foo: bar if line does not start with a * already
 * - Doesn't do anything else
 * 
 * Echos the output to stdout 
 * but you can redirect it to readme.md
 *
 * @link https://help.github.com/articles/github-flavored-markdown
 * 
 */
  $rmtxt = file( "readme.txt" );
  $count = 0;
  foreach ( $rmtxt as $line ) {
    $count++;
    $line = rtrim( $line );
    if ( 1 == $count ) {
      $line = trim( $line, '=' );
			$repository = trim( $line );
      $line = '#'. $line;
    }
    if ( substr( $line, 0, 1 ) == '=' )  {
      $line = rtrim( $line, '=' ); 
      //echo $line;
      $line = str_replace( "=", "#", $line );
    }
    
    if ( substr( $line,0,1 ) != "*"  && false != strpos( $line, ": " ) ) {
      $line = "* $line";
    }
    echo $line; 
    echo PHP_EOL; 
		if ( 1 == $count ) {
			display_banner( $repository );
		}
  }
	
/**
 * Display the banner image, if present in the assets folder
 * 
 * e.g.  
 * ![banner](https://raw.githubusercontent.com/bobbingwide/oik-ajax/master/assets/oik-ajax-banner-772x250.png)
 *
 * Format: `![Alt Text](url)`
 *
 * @param string $repository - the repository name
 * @param string $owner - the repository owner
 */
function display_banner( $repository="txt2md", $owner="bobbingwide" ) {
	$dir = getcwd();
	$files = scandir( "$dir/assets" );
	foreach ( $files as $file ) {
		if ( false !== strpos( $file, "-banner-772x250.jpg" ) ) {
			$line = "![banner](https://raw.githubusercontent.com/$owner/$repository/master/assets/$file)";
			echo $line;
			echo PHP_EOL;
		}
	}
}
  

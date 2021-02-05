<?php // (C) Copyright Bobbing Wide 2014-2017

/*
Plugin Name: txt2md
Plugin URI: http://www.oik-plugins.com/oik-plugins/txt2md
Description: Convert a WordPress readme.txt file to Github README.md 
Version: 0.0.2
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
 * - then adds the screenshot image, if present - we don't expect both
 * - Converts === heading === to ### heading
 * - Converts foo: bar to * foo: bar if line does not start with a * already
 * - Converts single back ticks to triple back ticks 
 * - Translates [github] shortcodes
 * - Doesn't do anything else
 * 
 * Echos the output to stdout 
 * but you can redirect it to readme.md
 *
 * @link https://help.github.com/articles/github-flavored-markdown
 * @link https://help.github.com/categories/writing-on-github/
 * 
 */
 
function txt2md_cli( $branch ) {
	$rmtxt=file( "readme.txt" );
	$count=0;
	foreach ( $rmtxt as $line ) {
		$count ++;
		$line=rtrim( $line );
		if ( 1 == $count ) {
			$line      =trim( $line, '=' );
			$repository=trim( $line );
			$line      ='#' . $line;
		}
		if ( substr( $line, 0, 1 ) == '=' ) {
			$line=rtrim( $line, '=' );
			//echo $line;
			$line=str_replace( "=", "#", $line );
		}

		if ( substr( $line, 0, 1 ) != "*" && false != strpos( $line, ": " ) ) {
			$line="* $line";
		}

		if ( $line == '`' ) {
			$line='```';
		}

		$line=convert_github( $line );
		echo $line;
		echo PHP_EOL;
		if ( 1 == $count ) {
			display_banner( $repository, 'bobbingwide', $branch );
			display_screenshot( $repository, 'bobbingwide', $branch );
		}
	}
}
 
 if ( PHP_SAPI === 'cli' ) {
	if ( $argc >= 2) {
		$branch=$argv[1];
	} else {
		$branch = 'master';
	}
	 txt2md_cli( $branch );
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
 * @param string $branch - the branch name
 */
function display_banner( $repository="txt2md", $owner="bobbingwide", $branch='master' ) {
	$dir = getcwd();
	if ( is_dir( "$dir/assets" ) ) {
		$files = scandir( "$dir/assets" );
		foreach ( $files as $file ) {
			$pos = strpos( $file, "-banner-772x250.jpg" );
			if ( $pos !== false ) {
				$repository = substr( $file, 0, $pos );
				$line = "![banner](https://raw.githubusercontent.com/$owner/$repository/$branch/assets/$file)";
				echo $line;
				echo PHP_EOL;
			}
		}
	}	
}

/**
 * Display the screenshot image, if it's a theme
 * 
 * e.g.
 * ![screenshot](https://raw.githubusercontent.com/bobbingwide/genesis-oik/master/screenshot.png)
 *
 * Format: `![Alt Text](url)`
 *
 * @TODO oik_require() may not be available when run directly from oik-tip or oik-zip
 *
 * @param string $repository - the repository name
 * @param string $owner - the repository owner
 */
function display_screenshot( $repository="txt2md", $owner="bobbingwide", $branch="master" ) {
	$file = null;
	if ( file_exists( "screenshot.png" ) ) {
		$file = "screenshot.png"; 
	} elseif ( file_exists( "screenshot.jpg" ) ) {
		oik_require( "j2p.php", "txt2md" );
		jpg2png( "screenshot.jpg", "screenshot.png" );
		$file = "screenshot.png";
	}
	if ( $file ) {
		$line = "![screenshot](https://raw.githubusercontent.com/$owner/$repository/$branch/$file)";
		echo $line;
		echo PHP_EOL;
	}	
}

/**
 * Convert github shortcode to an URL
 * 
 * We extract the shortcode bit to the middle and
 * convert that - similar to the github shortcode itself
 * then merge it back with the lhs and rhs
 *
 * @param string $line
 * @return string possibly modified
 */
function convert_github( $line ) {

	$ssb = strpos( $line, "[github" );
	if ( false !== $ssb ) {
		$lhs = substr( $line, 0, $ssb );
		$rhs = substr( $line, $ssb );
    $esb = strpos( $rhs, "]" );
		if ( false !== $esb ) {
			$esb++;
			$middle = substr( $rhs, 0, $esb );
			$rhs = substr( $rhs, $esb );
			
			//echo $ssb;
			//echo $esb;
			//echo $lhs . PHP_EOL;
			//echo $middle . PHP_EOL;
			//echo $rhs . PHP_EOL;
			$middle = github2url( $middle ); 
			$line = $lhs . $middle . $rhs;
		}
	}
	return( $line );
}

/**
 * Convert the shortcode to a GitHub URL
 * 
 * @param string $middle the github shortcode and parameters
 * @return string the generated URL
 */
function github2url( $middle ) {
	$middle = strtolower( $middle );
	$middle = str_replace( " issue ", " issues ", $middle );
	$shortcode = substr( $middle, 1, -1 );
	$parts = explode( ' ', $shortcode );
	$parts[0] = "https://github.com";
	$muddle = implode( '/', $parts );
	//echo $muddle;
	return( $muddle );
}

/**
 * Creates the README.md file without redirection
 * Uses output buffering then write?
 *
 * @param $branch
 * @param $readmemd
 */
function txt2md( $branch, $readmemd ) {

}
	
		
		
  

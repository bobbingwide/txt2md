<?php

/**
 * @package txt2md
 * @copyright (C) Copyright Bobbing Wide 2021
 */

$svg2php = new svg2php();
$svg2php->process();


class svg2php {

    /** @var string[]
	 * Source folders for the icons keyed by set's suffix
     */
    private $sources = [ 'dash' => 'C:/github/wordpress/dashicons/svg-min/*.svg'
, 'icons' => 'C:/github/wordpress/gutenberg/packages/icons/src/library/*.js'
, 'links' => 'C:/github/wordpress/gutenberg/packages/block-library/src/social-link/icons/*.js'
]  ;

    private $icons = [];
    private $counted = 0;

    function __construct() {
        $this->icons = [];


    }

    function process() {
    	$tried = 0;
        foreach ( $this->sources as $key => $dir ) {
            $files = glob( $dir );
            echo $dir . ' ' .  count( $files ) , PHP_EOL;
            echo PHP_EOL;
            foreach ( $files as $file ) {
            	$tried++;
                $this->process_file( $file, $key );
            }


            //print_r( $this->icons );
        }
        echo "Total" . count( $this->icons );
        echo PHP_EOL;
        echo "Counted:" . $this->counted;
        echo PHP_EOL;
        echo "Tried:" . $tried;

        echo PHP_EOL;
        $this->write_file();
    }

function process_file( $file, $key ) {

      $iconkey = pathinfo( $file, PATHINFO_FILENAME );
      echo "Processing: $iconkey", PHP_EOL;
      if ( 'index' === $iconkey ) {
	      echo "Skipping: $iconkey $key" . PHP_EOL;
	      return;
      }
      // In the icons library edit is the same as pencil!
	  // So when we get to pencil create edit as well!
     $contents = file_get_contents( $file );
	$svg = $this->get_svg( $contents );
		if ( !$svg ) {
			echo "Skipping - no SVG: $iconkey $key" . PHP_EOL;
			return;
		}

      //$dpath = $this->get_dpath( $file );
      //if ( $dpath ) {
          if (isset($this->icons[$iconkey])) {
              echo "Duplicate: $iconkey";
              $iconkey .= '-' . $key;
              echo " saved as: " . $iconkey;
              echo PHP_EOL;
          }
          $this->icons[$iconkey] = $svg;
          $this->counted++;
      //}
      echo PHP_EOL;
}
function get_dpath( $file ) {
        $contents = file_get_contents( $file );
        $extension = pathinfo( $file, PATHINFO_EXTENSION );
        if (  'js' === $extension ) {
            $dpath = $this->extractdfromjs( $contents );
        } else {
            $dpath = $this->extractdfromsvg( $contents );
        }
        return $dpath;
}

    /**
     * Extracts value for `Path d=` from .js
     *
     * @param $contents
     * @return false|string
     */
    function extractdfromjs( $contents) {
        //$contents = $this->remove_unexpected_attrs( $contents);
        $d = $contents;
        $pos = stripos( $contents, 'd="');
        if ( false === $pos ) {
            echo $contents;
            echo "bugger";
            return null;
        }
        $d = substr( $contents, $pos+3);
        $pos = strpos( $d, '"');
        $d = substr( $d, 0, $pos);
        return $d;
    }

	/**
	 * Extracts the complete SVG tag from the contents
	 *
	 * This can probably be done using regex.
	 * @param $contents
	 * @return string
	 */
    function get_svg( $contents ) {
		$svg = null;
    	$start =  stripos( $contents, '<SVG');
    	if ( false === $start) {
    		return $svg;
	    }
    	$end = stripos( $contents, '</SVG>');
    	$end += 6;
    	$length = $end - $start;
    	$svg = substr( $contents, $start, $length );
    	echo $svg;
    	return $svg;


    }

    function extractdfromsvg( $contents ) {
        $d = $this->extractdfromjs( $contents );
        return $d;
    }

    function remove_unexpected_attrs( $contents ) {
       // $contents = str_replace( 'class="st0" ', '', $contents);
        return $contents;
    }

    /**
     * Writes the new file.
     *
     * Write this to oik, oik-bob-bing-wide and oik-libs as it's easier for testing
     * until the version information is also generated.
     */
function write_file() {
    $content = $this->file_start();
    foreach ( $this->icons as $key => $value ) {
        $content .= $this->write_array( $key, $value );
        $content .= PHP_EOL;
    }
    $content .= $this->file_end();
    file_put_contents( 'C:/apache/htdocs/wordpress/wp-content/plugins/oik/libs/oik-dash-svg-list.php', $content);
    file_put_contents( 'C:/apache/htdocs/wordpress/wp-content/plugins/oik-bob-bing-wide/libs/oik-dash-svg-list.php', $content);
    file_put_contents( 'C:/apache/htdocs/wordpress/wp-content/plugins/oik-libs/libs/oik-dash-svg-list.php', $content);
}

function file_start() {
        $content = '<?php' . PHP_EOL;
        $content .= $this->docblock_comments();
        $content .= 'function bw_dash_list_svg_icons() {' . PHP_EOL;
        $content .= '$icons = [];' . PHP_EOL;
        return $content;
}

function docblock_comments() {
	$comments = '';
	$comments .= "if ( !defined( 'OIK_DASH_SVG_LIST_INCLUDED' ) ) {" . PHP_EOL;
	$comments .= "define( 'OIK_DASH_SVG_LIST_INCLUDED', '0.0.1');" . PHP_EOL;

	$comments .= '/**' . PHP_EOL;
	$comments.= ' * Generated at: ' . date('Y-m-d H:i:s' ) . PHP_EOL;
	$comments.= ' * By: ' . __FILE__ . PHP_EOL;
	$comments.= ' * Processing:' . PHP_EOL;
	foreach ( $this->sources as $key => $file ) {
		$comments .= " * $key - $file " . PHP_EOL;
	}
	$comments .= ' */' . PHP_EOL;
	return $comments;
}

function file_end() {
        $content = 'return $icons;' . PHP_EOL;
        $content .= '}' . PHP_EOL;
		$content .= '}' . PHP_EOL;
        return $content;
}

function write_array( $key, $value ) {
    $string = '$icons[' . "'" . $key . "'] = '" . $value . "';";
    return $string;

}

}
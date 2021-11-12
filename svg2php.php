<?php

/**
 * @package txt2md
 * @copyright (C) Copyright Bobbing Wide 2021
 */

$svg2php = new svg2php();
$svg2php->process();


class svg2php {

    /** @var string[]
     * WordPress icons trump dashicons
     * social link icons trump WordPress icons
     *
     * The only duplicate so far is the WordPress icon!
     */
    private $sources = [ 'C:/github/wordpress/dashicons/svg-min/*.svg'
, 'C:/github/wordpress/gutenberg/packages/icons/src/library/*.js'
, 'C:/github/wordpress/gutenberg/packages/block-library/src/social-link/icons/*.js'
]  ;

    private $icons = [];

    function __construct() {
        $this->icons = [];


    }

    function process() {
        foreach ( $this->sources as $dir ) {
            $files = glob( $dir );
            echo $dir . ' ' .  count( $files ) , PHP_EOL;
            echo PHP_EOL;
            foreach ( $files as $file ) {
                $this->process_file( $file );
            }


            //print_r( $this->icons );
        }
        echo "Total" . count( $this->icons );
        echo PHP_EOL;
        $this->write_file();
    }

function process_file( $file ) {

      $iconkey = pathinfo( $file, PATHINFO_FILENAME );
      echo "Processing $iconkey";
      // This is the same as pencil!
      if ( 'edit' === $iconkey ) return;
      $dpath = $this->get_dpath( $file );
      if ( $dpath ) {
          if (isset($this->icons[$iconkey])) {
              echo "duplicate $iconkey";
          }
          $this->icons[$iconkey] = $dpath;
      }
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
        $contents = $this->remove_unexpected_attrs( $contents);
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

    function extractdfromsvg( $contents ) {
        $d = $this->extractdfromjs( $contents );
        return $d;
    }

    function remove_unexpected_attrs( $contents ) {
        $contents = str_replace( 'class="st0" ', '', $contents);
        return $contents;
    }

function write_file() {
    $content = $this->file_start();
    foreach ( $this->icons as $key => $value ) {
        $content .= $this->write_array( $key, $value );
        $content .= PHP_EOL;
    }
    $content .= $this->file_end();
    file_put_contents( 'C:/apache/htdocs/wordpress/wp-content/plugins/oik-libs/libs/oik-dash-svg-list.php', $content);
}

function file_start() {
        $content = '<?php' . PHP_EOL;
        $content .= '// This file automatically generated by svg2php.php' . PHP_EOL;
        $content .= 'function bw_dash_list_svg_icons() {' . PHP_EOL;
        $content .= '$icons = [];' . PHP_EOL;
        return $content;
}

function file_end() {
        $content = 'return $icons;' . PHP_EOL;
        $content .= '}' . PHP_EOL;
        return $content;
}

function write_array( $key, $value ) {
    $string = '$icons[' . $key . "] = '" . $value . "';";
    return $string;

}

}
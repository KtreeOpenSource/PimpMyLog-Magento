<?php

define( 'TESTBASE' , realpath( dirname( __FILE__ ) . '/..' ) );
define( 'PHPMOCKUP' , realpath( TESTBASE . '/phpmockup' ) );

echo '--> Working in ' . PSKBASE . " <--\n";

include_once( 'TestCase.php' );

include_once( PSKBASE . '/inc/global.inc.php');
load_default_constants();
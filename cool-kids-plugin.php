<?php
/*
Plugin Name: Cool-Kids Plugin
Description: A plugin to manage Cool Kids Network Members
Version: 1.0
Author: Kadeem
*/

define( 'COOLKIDS_PLUGIN_BASEDIR', plugin_dir_path( __FILE__ ) );
define( 'COOLKIDS_PLUGIN_BASEDIR_URL', plugin_dir_url( __FILE__ ) );

require_once COOLKIDS_PLUGIN_BASEDIR . 'autoloader.php';
require_once COOLKIDS_PLUGIN_BASEDIR . 'plugin-function.php';
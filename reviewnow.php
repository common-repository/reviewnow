<?php

defined('ABSPATH') or die('This page may not be accessed directly.');

/**
 * Plugin Name: ReviewNow
 * Description: Get more positive reviews on Plattforms like Google, Facebook, Tripadvisor a.s.o
 * Version: 1.0.12
 * Author: EEOOM Plugins
 * Text Domain: reviewnow
 * Domain Path: /languages
 * License: MIT
 */

/**
 * Returns the file used to load the reviewnow plugin
 *
 * @package reviewnow
 * @return string The path and file of the reviewnow plugin entry point
 */
function reviewnow_GetInitFile() {
	return __FILE__;
}

require_once( plugin_dir_path(__FILE__) . 'reviewnow-loader.php' );



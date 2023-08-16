<?php
/*
Plugin Name: WP Movies
Plugin URI: https://sputznik.com/
Description: A plugin to add custom post type 'movies' with custom fields and display as widgets.
Version: 1.0
Author: Sputznik
Author URI: https://sputznik.com/
License: GPL2
*/

// Include the custom post type feature
require_once plugin_dir_path(__FILE__) . 'post-types/movies-post-type.php';

// Include the widget feature
require_once plugin_dir_path(__FILE__) . 'widgets/movies-widget.php';
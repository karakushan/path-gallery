<?php
/*
Plugin Name: Path Gallery
Plugin URI: https://github.com/karakushan/path-gallery
Description:  The plugin will help in the creation of any online store without changing your template.
Version: 1.0.0
Author: Vitaliy Karakushan
Author URI: https://github.com/karakushan
License: GPL2
Domain: path-gallery
Domain Path: languages
*/
/*
Copyright 2016 Vitaliy Karakushan  (email : karakushan@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// устанавливаем константы
@define('BFITHUMB_UPLOAD_DIR','shop');
@define('PH_PLUGIN_DIR', plugin_dir_path(__FILE__));
@define('PH_PLUGIN_VERSION', '1.0');

// подключаем файлы
require_once 'functions/functions.php';
// подключаем библиотеки
require_once 'lib/BFI_Thumb.php';
// подключаем классы
require_once 'classes/Path_Gallery_Settings.php';
require_once 'classes/Path_Gallery_Posts.php';

add_action( 'plugins_loaded', array( 'Path_Gallery_Init', 'get_instance' ) );
/*register_activation_hook( __FILE__, array( 'Ihoster_Init', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Ihoster_Init', 'deactivate' ) );*/

if (!class_exists('Path_Gallery_Init')) {
   /**
   * Path_Gallery_Init
   */
   class Path_Gallery_Init
   {
    private static $instance = null;
    function __construct()
    {
       new Path_Gallery_Settings;
       new Path_Gallery_Posts;
       add_action( 'plugins_loaded', array( $this, 'get_instance' ) );
   }
   public static function get_instance() {
    if ( ! isset( self::$instance ) )
        self::$instance = new self;
    load_plugin_textdomain('path-gallery',false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
    return self::$instance;
}
}
}
new Path_Gallery_Init;


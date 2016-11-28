<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Клас отвечающий за настройки плагина
*/
class Path_Gallery_Settings
{
	public $settings=array(
		'galleries_path'=>'/images/'
		);

	function __construct()
	{
		add_action('admin_menu',array($this,'admin_menu') );
		add_action('admin_init',array($this,'save_settings'),11);

	}

	public function get_settings($setting_name='')
	{
		//	объединяем настройки по умолчанию и настройки заданные пользователем
		$path_gallery_settings=get_option('path_gallery');
		$path_gallery_settings=array_diff($path_gallery_settings, array(''));
		$settings=array_merge($this->settings,$path_gallery_settings);
		if (empty($setting_name)) {
			return $settings;
		}else{
			return $settings[$setting_name];
		}
	}

	public function admin_menu()
	{
		add_submenu_page( 'edit.php?post_type=path-gallery',__( 'Settings', 'path-gallery' ),__( 'Settings', 'path-gallery' ), 'manage_options', 'pg-settings', array($this,'path_gallery_submenu' )); 
	}
	public function path_gallery_submenu()
	{
		$template=PG_PLUGIN_DIR.'templates/pg-settings.php';
		if (file_exists($template)){
			$settings=$this->get_settings();
			include $template;
		} 
		return; 
	}
	public function save_settings()
	{
		if (isset($_POST['pg_settings'])){
			$upd=update_option('path_gallery',$_POST['pg_settings']);
			if ($upd) {
				add_action('admin_notices', function(){
					echo '<div class="updated"><p>'.__('Settings updated','path-gallery').'</p></div>';
				});
			}
		}
		
	}
}
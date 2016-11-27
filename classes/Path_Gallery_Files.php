<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Клас отвечающий за оработку файлов
*/
class Path_Gallery_Files
{
	function __construct()
	{
		add_action('wp_ajax_pg_files_list',array($this,'pg_files_list'));
		add_action('wp_ajax_nopriv_pg_files_list',array($this,'pg_files_list'));
	}

	public function pg_files_list()
	{
		$files_list=pg_get_files_list($_POST['site_path']);
		echo json_encode(array(
			'files_list'=>$files_list
			));
		exit;
	}

	
}
<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Клас для создания шоткодов
*/
class Path_Gallery_Shortcodes
{
	function __construct()
	{
		add_shortcode( 'path-gallery',array($this,'path_gallery_shortcode'));
	}
	function path_gallery_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'id' =>0,
			'col'=>3,
			'width'=>'278',
			'height'=>'193',
			'margin_bottom'=>'4px',
			), $atts );
		$out='';
		$data=get_post_meta( $atts['id'],'image_data',0);
		$images=$data[0];
		$col_bootstrap=12/$atts['col'];
		if ($images) {
			ob_start();
			echo '<style>';
			echo '.pg-gallery-front .pg-col{';
			echo '-webkit-box-flex: 0;
			-webkit-flex: 0 0 '.$atts['width'].'px;
			-ms-flex: 0 0 '.$atts['width'].'px;
			flex: 0 0 '.$atts['width'].'px;
			margin-bottom: '.$atts['margin_bottom'].'
			';
			echo '}';
			echo '</style>';
			echo '<div class="pg-gallery-front">';
			foreach ($images as $key => $image) {
				$thumbnail=pg_thumbnail($image['url'],array('width'=>$atts['width'],'height'=>$atts['height']));
				$signature=!empty($image['signature']) ? $image['signature']: $image['title'];
				echo '<div class="pg-col">';
				echo '<div class="wrap">
				<a href="'.$image['url'].'" data-lightbox="roadtrip">
				<img src="'.$thumbnail.'" alt="'.$image['title'].'">
				</a>
				<div class="signature">'.$signature.'</div>
				</div>';
				echo '</div>';
			}
			echo '</div>';
			$out=ob_get_clean();

		}
		return $out;

	}
	
	

	
}
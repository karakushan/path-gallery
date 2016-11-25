<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Клас отвечающий за настройки плагина
*/
class Path_Gallery_Posts
{
	function __construct()
	{
		add_action('init', array($this,'register_post_types'));
		add_action( 'save_post', array( $this, 'save' ) );
	}

	function register_post_types(){
		register_post_type('path-gallery',array(
			'label'=>__( 'Галереи', 'path-gallery' ),
			'public'=>true, 
			'supports'=>array('title'),
			'rewrite'=>array('with_front'=>false),
			'has_archive'=>false,
			'menu_icon'=>'dashicons-format-image',
			'register_meta_box_cb'=>array($this,'path_gallery_meta_box')
			));
	}

	public function path_gallery_meta_box()
	{
		add_meta_box( 'path_gallery_meta_box', __('Gallery settings','path-gallery'), array($this,'path_gallery_meta_box_callback'), 'path-gallery','advanced','high');
	}
	public function path_gallery_meta_box_callback($post)
	{
		// Используем nonce для верификации
		wp_nonce_field( plugin_basename(__FILE__), 'path_gallery_box_nonce' );
		$gallery_path=get_post_meta($post->ID,'gallery_path',1);
		$images=path_gallery_files($gallery_path);

		?>
		<style type="text/css">
			.path-gallery-metabox label{
				width: 257px;
				float: left;
				line-height: 25px;
				padding: 0;
			}
			ul.path-gallery-images li {
				display: inline-block;
				padding: 12px;
				border: 1px solid #e5e5e5;
				width: 315px;
				height: 208px;
				position: relative;
				background-repeat: no-repeat;
				-webkit-background-size: cover;
				background-size: cover;
			}
			
		</style>
		<ul class="path-gallery-metabox">
			<li>
				<label for="">Шоткод</label>
				<code>[path-gallery id="<?php echo $post->ID ?>"]</code></td>
			</li>
			<li>
				<label for="gallery_path">Путь к папке с изображениями</label>
				<input type="text" name="path_gallery[gallery_path]" value="<?php echo $gallery_path ?>" id="gallery_path" placeholder="пример: /images/"> 
				<button type="button" class="btn btn-primary">выбрать на сервере</button>
			</li>
			<?php if ($images): ?>
				<li><h3>Список изображений</h3>
					<ul class="path-gallery-images">

						<?php foreach ($images as $key => $image): ?>
							<li style="background-image:url(<?php echo $image ?>)"></li>
						<?php endforeach ?>



					</ul>
				</li>
			<?php endif ?>

		</ul>
		<?php

	}

	public function save( $post_id ) {

		/*
		 * Нам нужно сделать проверку, чтобы убедится что запрос пришел с нашей страницы, 
				 * потому что save_post может быть вызван еще где угодно.
		 */

		// Проверяем установлен ли nonce.
		if ( ! isset( $_POST['path_gallery_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['path_gallery_box_nonce'];

		// Проверяем корректен ли nonce.
		if ( ! wp_verify_nonce( $nonce, plugin_basename(__FILE__) ) )
			return $post_id;

		// Если это автосохранение ничего не делаем.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Проверяем права пользователя.
		if ( 'path-gallery' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, все чисто, можно сохранять данные. */

		// Очищаем поле input.
		$pg_post_data=array_map('sanitize_text_field',$_POST['path_gallery']);

		if ($pg_post_data) {
			foreach ($pg_post_data as $key => $pg_data) {
				update_post_meta($post_id, $key, $pg_data);
			}
		}
	}

}
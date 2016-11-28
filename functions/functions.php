<?php 
/**
 * @param $path - относительный путь к папке с файлами
 * @return array
 */
function path_gallery_files($path)
{

    // результат выполнения функции is_dir кэшируется,
    // поэтому сбрасываем кэш.
    clearstatcache();
    $full_path=$_SERVER['DOCUMENT_ROOT'].$path;
    if (empty($path) || !file_exists($full_path)) return array();
    $files = scandir($full_path);
    for($i = 0, $length = count($files); $i < $length; $i++)
    {
        // Исключаем из списка директории и не изображения:
        if( is_dir( $full_path.$files[$i]) || !checkValidFormat($files[$i], array('jpg','jpeg','png','gif','bmp','svg')) || strpos($files[$i],'thumb')){
            unset($files[$i]);
        }else{
            $files[$i]=$path.$files[$i];
        }
    }
    return $files; //array
}

/**
* Проверка корректности формата файла
* 
* @param string $file - имя файла или путь до файла
* @param array $validFormat - массив с корректными форматами
*
* @return boolean - результат проверки
*/
function checkValidFormat($file, $validFormat){
    $file_el=explode(".",$file);
    $format = end($file_el);
    if(in_array(strtolower($format), $validFormat)){
        return true;
    }
    return false;
}

//получем список файлов и директорий по переданному пути
function pg_get_files_list($site_path='/wp-content/uploads/')
{
    $dir  = $_SERVER['DOCUMENT_ROOT'].$site_path;
    $files = scandir($dir,1);
    $file_listing='';
    if ($files) {
        $file_listing.='<ul>';
        foreach ($files as $file):
            if (in_array($file,array('.','..'))) continue;
        if (is_dir($dir.$file)) {
            $file_listing.= '<li><a href="'.$site_path.$file.'/" title="'.__('click to get a list of nested directories','path-gallery').'" data-pg-action="get-directories"><i class="icon-folder-empty"></i>'.$file.'</a> <button type="button" data-pg-action="add-path" data-pg-path="'.$site_path.$file.'/"><i class="icon-plus-circled"></i></button></li>';
        }else{
            $file_ext=explode('.',$file);
            if(in_array($file_ext[1],array(jpg,jpeg,png))){
                $icon='<i class="icon-file-image"></i>';
            }
            $file_listing.= '<li>'.$icon.' '.$file.'</li>';
        }
        endforeach;
        $file_listing.= "</ul>";
    }
    return $file_listing; 
}

/**
 * создаёт миниатюру изображения с помощью класса WP_Image_Editor
 * @param  строка $path  - путь к изображению
 * @param  массив  $args  - дополнительные агрументы 
 * @return строка       возвращает урл миниатюры
 */
function pg_thumbnail($path,array $args){
    $settings=new Path_Gallery_Settings;
    $args=wp_parse_args($args, array(
        'width'=>300,
        'height'=>160,
        'q'=>100,
        'crop'=>true
        ));
    $thumbnail=$path;
    $image = wp_get_image_editor( $_SERVER['DOCUMENT_ROOT'].$path );
    $file_name='thumb-'.$args['width'].'x'.$args['height'].'-'.basename($path);
    $thumbnail_url=$settings->settings['galleries_path'].'cache/'.$file_name;
    
    //  если миниатюра существует, то просто возвращаем её
    if (file_exists($_SERVER['DOCUMENT_ROOT'].$thumbnail_url))   return $thumbnail_url;

    //  если всё-таки нет миниатюры, генерируем её используя класс WP_Image_Editor
    if ( ! is_wp_error($image ) ) {
        $image->resize($args['width'],$args['height'],$args['crop']);
        $image->set_quality($args['q']);
        $file_name='thumb-'.$args['width'].'x'.$args['height'].'-'.basename($path);
        $image->save( $_SERVER['DOCUMENT_ROOT'].$thumbnail_url );
    }
    return $thumbnail;
}
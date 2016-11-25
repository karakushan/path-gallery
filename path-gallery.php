<?php
/*
Plugin Name: Path Gallery
Plugin URI: http://profglobal.ru/fast-shop/
Description:  The plugin will help in the creation of any online store without changing your template.
Version: 1.0.0
Author: Vitaliy Karakushan
Author URI: http://profglobal.ru/
License: GPL2
Domain: path-gallery
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

require_once('BFI_Thumb.php');
 @define( BFITHUMB_UPLOAD_DIR,'shop');

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





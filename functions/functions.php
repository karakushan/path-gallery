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
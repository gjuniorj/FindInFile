<?php


$file_to_search = "file.pdf";

//search_file('../../../teste',$file_to_search);
search_file('.',$file_to_search);

/**
 * Find file inside specified directory
 *
 * @param string $dir
 * @param string $file_to_search
 */
function search_file($dir,$file_to_search){

    //Returns an array with directories and file names found inside given directory
    $files = scandir($dir);

    //
    foreach($files as $value){

        //Gets full path of directory or file
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);

        //Checks if path is a file or directory
        if(!is_dir($path)) {

            //It's a file. Checks if it's the searched file
            if($file_to_search == $value){
                echo "file found<br>";
                echo $path;
                break;
            }

        } //It's a directory.
        elseif($value != "." && $value != "..") {

            //Searches file inside found directory
            search_file($path, $file_to_search);

        }
    }
}
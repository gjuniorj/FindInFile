<?php


$something_to_search = ".pdf";

search_file('../../../teste',$something_to_search);
//search_file('.',$file_to_search);


/**
 * Find file inside specified directory
 *
 * @param string $dir
 * @param string $file_to_search
 */
function search_file($dir,$file_to_search){

    //Returns an array with directories and files names found inside given directory
    $files = scandir($dir);

    //
    foreach($files as $value){

        //Gets full path of directory or file
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);

        //Checks if path is a file or directory
        if(!is_dir($path)) {

            //It's a file. Searches for a file with specified extension.
            if(strcmp($file_to_search, getFileExtension($value)) == 0){

                //var_dump($file_to_search); var_dump(substr($value,-3));
                echo "file found".PHP_EOL;
                echo $path.PHP_EOL;

            }

        } //It's a directory.
        elseif($value != "." && $value != "..") {

            //Searches file inside found directory
            search_file($path, $file_to_search);

        }
    }
}

function getFileExtension($haystack){

    $needlePos = strrpos($haystack,'.');
    if ( $needlePos === false ){
        //echo "ERROR - File name must have an extension.";
        var_dump($haystack);
        //exit();
    }

    $fileExtension =  substr($haystack, $needlePos);

   // var_dump($fileExtension);

    return $fileExtension;



}
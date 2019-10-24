<?php


$extension_to_search = ".xml";
//$directory_to_search = '../../../teste';
$directory_to_search = '/home/gilberto/Downloads/ojs-2.4.8-2';

$elementsArray = searchFileWithExtension($directory_to_search,$extension_to_search, 'getXMLTypeAttribute');
fileWriter ($elementsArray);
echo 'File created!'.PHP_EOL;




/**
 * Goes inside given directory and searches for files with specified extension.
 * Returns an array of elements from a callback function.
 *
 * @param $dir
 * @param $extension_to_search
 * @param $callback
 * @return array
 */
function searchFileWithExtension($dir, $extension_to_search, $callback){
    $typesArray = array();

    //Returns an array with directories and file names found inside given directory
    $files = scandir($dir);

    //Go inside the directory searching for the file with the given extension
    foreach($files as $value){

        //Gets full path of directory or file
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);

        //Checks if path is a file or directory
        if(!is_dir($path)) {


            //It's a file. Searches for a file with specified extension.
            if(strcmp($extension_to_search, getFileExtension($value)) == 0){

                $typesArray = array_unique(array_merge($typesArray, $callback($path)));


            }

        } //It's a directory.
    elseif($value != "." && $value != "..") {

            //Searches file inside found directory
            $typesArray = array_unique(array_merge($typesArray, searchFileWithExtension($path, $extension_to_search, $callback)));

        }
    }

    return $typesArray;

}


/**
 * Returns file extension.
 *
 * @param string $haystack
 * @return string
 */
function getFileExtension($haystack){

    $needlePos = strrpos($haystack,'.');

    //Verifies if file has an extension
    if ( $needlePos === false ){
        //echo "ERROR - File name must have an extension.";
        return '';
    }

    $fileExtension =  substr($haystack, $needlePos);

   return $fileExtension;

}


/**
 * Iterates each field tag and returns an array of attributes of "type" type.
 *
 * @param $xmlFilePath
 * @return array
 */
function getXMLTypeAttribute($xmlFilePath){

    $typesArray = array();

    // Creates an object that provides recursive iteration over all nodes of a SimpleXMLElement object
    // Parameter "data_is_url" must be "true" because the object is created from a xml file path, and not a string (see first parameter of function)
    $xmlIterator = new SimpleXMLIterator($xmlFilePath, null, true);
    //Constructs a recursive iterator from an iterator
    $recursive = new RecursiveIteratorIterator($xmlIterator,RecursiveIteratorIterator::SELF_FIRST);

    foreach ($recursive as $tag => $object) {

        if ($tag === 'field') {

            $type = $object['type'];

            if ( !(is_null($type)) && !( in_array( $type, $typesArray) ) ){
                array_push($typesArray,(string)$object['type']);
            }
        }

    }

    return $typesArray;

}

/**
 * @param $array
 *
 */
function fileWriter ($array){

    $fp = fopen('file.txt', 'w');

    sort($array);

    foreach ($array as $value){
        fwrite($fp, print_r($value, true) . PHP_EOL);
    }
    fclose($fp);

}


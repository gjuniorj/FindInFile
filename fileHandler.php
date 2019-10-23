<?php


$something_to_search = ".xml";

//search_file('.',$file_to_search);
$elementsArray = search_element('../../../teste',$something_to_search, 'getXMLAttribute');
fileWriter ($elementsArray);
echo 'File created!';


/**
 * @param $dir
 * @param $something_to_search
 * @param $callback
 * @return mixed
 */
function search_element($dir,$something_to_search, $callback){
    $typesArray = array();

    //Returns an array with directories and files names found inside given directory
    $files = scandir($dir);

    //
    foreach($files as $value){

        //Gets full path of directory or file
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);

        //Checks if path is a file or directory
        if(!is_dir($path)) {

            //It's a file. Searches for a file with specified extension.
            if(strcmp($something_to_search, getFileExtension($value)) == 0){

                $typesArray = $callback($path);

            }

        } //It's a directory.
        elseif($value != "." && $value != "..") {

            //Searches file inside found directory
            search_element($path, $something_to_search, $callback);

        }
    }

    return $typesArray;

}


/**
 * Returns file extension
 *
 * @param string $haystack
 * @return string
 */
function getFileExtension($haystack){

    $needlePos = strrpos($haystack,'.');

    //Verifies if file has an extension
    if ( $needlePos === false ){
        //echo "ERROR - File name must have an extension.";
        //var_dump($haystack);
        return '';
    }

    $fileExtension =  substr($haystack, $needlePos);

   return $fileExtension;

}


/**
 * Iterates each field tag and returns an array of attributes of "type" type
 *
 * @param $xmlFilePath
 * @return array
 */
function getXMLAttribute($xmlFilePath){

    $typesArray = array();

    // Creates an object that provides recursive iteration over all nodes of a SimpleXMLElement object
    // Parameter "data_is_url" must be "true" because the object is created from a xml file path, and not a string (see first parameter of function)
    $xmlIterator = new SimpleXMLIterator($xmlFilePath, null, true);
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


function fileWriter ($array){

    $fp = fopen('file.txt', 'w');
    //TODO: foreach aqui
    fwrite($fp, print_r($array, TRUE));
    fclose($fp);

}





//    // Creates an object that provides recursive iteration over all nodes of a SimpleXMLElement object
//    // Parameter "data_is_url" must be "true" because the object is created from a xml file path, and not a string (see first parameter of function)
//    $xmlIterator = new SimpleXMLIterator($xmlFilePath, null, true);
//    //Constructs a recursive iterator from an iterator
//    $recursive = new RecursiveIteratorIterator($xmlIterator);
//
//    foreach ($recursive as $tag => $object){
//        if ($tag === 'field') {
//            //echo $object, "\n";
//            var_dump((string)$object['type']);
//        }
//    }

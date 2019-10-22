<?php


$something_to_search = ".xml";

search_file('../../../teste',$something_to_search, 'getXMLAttribute');
//search_file('.',$file_to_search);


/**
 * @param string $dir
 * @param string $something_to_search
 * @param $callback
 */
function search_file($dir,$something_to_search, $callback){

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

//                echo 'arquivo => '.$value;
//                echo 'Vai chamar callback';
                $callback($path);

            }

        } //It's a directory.
        elseif($value != "." && $value != "..") {

            //Searches file inside found directory
            search_file($path, $something_to_search, $callback);

        }
    }
}

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
 * @param $xmlFilePath
 * @return int|SimpleXMLElement|string
 */
function getXMLAttribute($xmlFilePath){

    //Loading xml file to object
    $xml = simplexml_load_file($xmlFilePath, null, null);

    $typesArray = array();

    //Iterating table attributes
    for ($i=0; $i < count($xml->table); $i++){

        //verifies if field tag exists
        if ( !is_null($xml->table[$i]->field) ){

            //Iterating field tags of table
            for ($j=0; $j < count($xml->table[$i]->field); $j++){

                //Gets type attribute from field tag/node
                $typeAttribute = $xml->table[$i]->field[$j]->attributes()['type'];

                if ( !is_null($typeAttribute) ) {
                    array_push($typesArray, $typeAttribute);
                }
            }


        }
    }
    var_dump($typesArray);

//    //Iterating attributes of field tag in the xml file
//    foreach($xml as $attribute => $value) {
//
//        //Verifies if attribute name is "type"
//        if ( strcmp($attribute, 'type') === 0){
//            //echo $attribute.' => '.$value;
//        }
//
//    }

    //return $attribute;
}
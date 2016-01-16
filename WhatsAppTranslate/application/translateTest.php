<?php
require_once 'MScredentials.php';
require_once 'MSHTTPTranslator.php';

try {
 
    //Set the params.//
    //$fromLanguage = "en";
    $toLanguage   = "es";
    $inputStr     = "this is a test";

    
    //Create the Translator Object.
    $translatorObj = new HTTPTranslator($clientID,$clientSecret);
    
    $translatedStr = $translatorObj->translate($inputStr, $toLanguage);
    
    echo $inputStr." ===> ".$translatedStr;

} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . PHP_EOL;
}
?>
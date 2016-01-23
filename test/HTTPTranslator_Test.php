<?php
require_once '../application/MScredentials.php';
require_once '../application/HTTPTranslator.php';

try {
 
    //Set the params
    $toLanguage   = "en";
    $inputStr     = "Esto es una mesa";
    
    //Create the Translator Object.
    $translatorObj = new HTTPTranslator($clientID,$clientSecret,TRUE);
    
    // 1.- Translate String
    $translatedStr = $translatorObj->translate($inputStr, $toLanguage);
    echo "OK. Translation ".$inputStr." ===> ".$translatedStr."\n";
    
    // 2.- Detect Language
    $languageCode = $translatorObj->detectLanguage($inputStr);
    echo "OK. Language detection ".$inputStr." ===> ".$languageCode."\n";

    // 3.- Speak in Spanish
    $mp3 = $translatorObj->speak($translatedStr, 'en');
    echo "OK. Speak ".$translatedStr." ===> "."\n";
    $fp = fopen('../data/data.mp3', 'w');
    fwrite($fp, $mp3);
    fclose($fp);
    exec('"C:\Program Files (x86)\VideoLAN\VLC\vlc.exe" -Idummy C:\Users\Javier\git\WhatsAppTranslate\WhatsAppTranslate\data\data.mp3' );
    
} catch (Exception $e) {
    echo "Error. Exception: " . $e->getMessage() . PHP_EOL;
}
?>
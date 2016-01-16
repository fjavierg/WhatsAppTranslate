<?php

require_once 'C:/Users/Javier/git/Whatsapp/src/whatsprot.class.php';
require_once 'credentials.php';
require_once 'MScredentials.php';
require_once 'MSHTTPTranslator.php';
require 'MyEvents.php';

$username = $config['TEST']['fromNumber'];
$password = $config['TEST']['waPassword'];
$nickname = $config['TEST']['nick'];
$debug = false;

//Create the Translator Object.
$translatorObj = new HTTPTranslator($clientID,$clientSecret);

//Connect to whatsapp
$w = new WhatsProt($username, $nickname, $debug);
$events = new MyEvents($w);
$events->setEventsToListenFor($events->activeEvents);
$events->setTranslator($translatorObj);
$w->connect();
$w->loginWithPassword($password);

while(1){
    $w->pollMessage();
}

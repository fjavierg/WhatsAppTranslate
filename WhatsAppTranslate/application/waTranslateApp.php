<?php

require_once '../lib/whanonymous/whatsprot.class.php';
require_once 'WAcredentials.php';
require_once 'MScredentials.php';
require_once 'HTTPTranslator.php';
require 'MyEvents.php';

$username = $config['TEST']['fromNumber'];
$password = $config['TEST']['waPassword'];
$nickname = $config['TEST']['nick'];
$debug = false;

//Create the Translator Object.
$translatorObj = new HTTPTranslator($clientID,$clientSecret);

//Connect to whatsapp
$w = new WhatsProt($username, $nickname, $debug);
$events = new MyEvents($w,$translatorObj);
$events->setEventsToListenFor($events->activeEvents);
$w->connect();
$w->loginWithPassword($password);

while(1){
    $w->pollMessage();
}


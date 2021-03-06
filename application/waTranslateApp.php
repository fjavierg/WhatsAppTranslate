<?php

require_once '../lib/whanonymous/whatsprot.class.php';
require_once 'WAcredentials.php';
require 'MyEvents.php';

$username = WAUSERNAME;
$password = WAPWD;
$nickname = WANICKNAME;
$debug = FALSE;


//Connect to whatsapp
$w = new WhatsProt($username, $nickname, $debug);
$events = new MyEvents($w,$debug);
$events->setEventsToListenFor($events->activeEvents);
$w->connect();
$w->loginWithPassword($password);

while(1){
    $w->pollMessage();
}


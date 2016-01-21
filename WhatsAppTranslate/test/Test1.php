<?php 
require_once 'whatsprot.dummy.class.php';
require '../application/MyEvents.php';

$output;
$debug = false;
$w = new WhatsProt($ouput);
$events = new MyEvents($w,$debug);
$mynumber = '346xxxxxxx';

function sendMessage( $mynumber, $from, $id, $type, $time, $name, $body ){
	global $output;
	global $events;
	$output = $output."Message from: $from->$body \n";
	$events->onGetMessage( $mynumber, $from, $id, $type, $time, $name, $body );
}

//
// Basic translation test
//		Create chat
//		send message from A->B check it's translated
//		send message from B->A check it's translated
//
//
// 34625369980 Says Chat 34644016790
sendMessage($mynumber, '34625369980', '', '', '', 'Javier W', 'Chat 34000000000');
// assert Chat created
if (strpos($output,'to: 34625369980->Chat created') !== false AND
	strpos($output,'to: 34000000000->Hi Javier W') !== false) echo "OK Chat created \n";
else echo "ERROR ******************************* \n";

// 34625369980 Says Hola
sendMessage($mynumber, '34625369980', '', '', '', 'Javier W', 'Hola');
// assert Hola translated
if (strpos($output,'to: 34000000000->[Javier W] Hello') !== false) echo "OK Message trasnlated \n";
	else echo "ERROR ******************************* \n";

// 34000000000 Says Hello
sendMessage($mynumber, '34000000000', '', '', '', 'Manuel', 'Hello');
// assert Hola translated
if (strpos($output,'to: 34625369980->[Manuel] Hola') !== false) echo "OK Message trasnlated \n";
	else echo "ERROR ******************************* \n";
		




echo "\nOUTPUT\n$output";
?>
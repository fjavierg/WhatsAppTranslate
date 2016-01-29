<?php

//
//
//  Test Class
//
require_once '../application/ChatSet.php';
require_once '../application/Chat.php';


date_default_timezone_set('Europe/Madrid');
echo "\n";
$myChats = new ChatSet();



//
// 1.-Add New Chat
//
$myChat=$myChats->add('34644016791','34625369981');
//assert chat created
if ($myChats->search('34644016791'))
	echo "OK. Chat found: Origin = ".$myChat->source." Destination = ".$myChat->destination." Last message at = ".$myChat->interactionDate;
else
	echo "Error Chat can not be created, destination already involved in a chat";
echo "\n";


//
// 2.- Add New Chat
//
$myChat=$myChats->add('34644016792','34625369982');
//assert chat created
if ($myChat AND $myChats->search('34625369982'))
		echo "OK. Chat found: Origin = ".$myChat->source." Destination = ".$myChat->destination." Last message at = ".$myChat->interactionDate;
	else
		echo "Error Chat can not be created, destination already involved in a chat";
echo "\n";


//
// 3.- Add New Chat
//
$myChat=$myChats->add('34644016793','34625369983');
//assert chat created
if ($myChat AND $myChats->search('34625369983'))
		echo "OK. Chat found: Origin = ".$myChat->source." Destination = ".$myChat->destination." Last message at = ".$myChat->interactionDate;
	else
		echo "Error Chat can not be created, destination already involved in a chat";
echo "\n";

//
// 4.- Add Chat. Destination involved in another valid chat as origin
//
$myChat=$myChats->add('34644016794','34625369981');
//assert chat failed
if ($myChat AND $myChats->search('34644016794'))
		echo "OK. Chat found: Origin = ".$myChat->source." Destination = ".$myChat->destination." Last message at = ".$myChat->interactionDate;
	else
		echo "OK. Chat can not be created, destination already involved in a chat as origin";
echo "\n";

//
// 5.- Add Chat. Destination involved in another valid chat as destination
//
$myChat=$myChats->add('34644016795','34644016792');
//assert chat failed
if ($myChat AND $myChats->search('34644016795'))
		echo "OK. Chat found: Origin = ".$myChat->source." Destination = ".$myChat->destination." Last message at = ".$myChat->interactionDate;
	else
		echo "OK. Chat can not be created, destination already involved in a chat as destination";
echo "\n";
		
//
// 6.- Add Chat. Origin involved in another valid chat. Should delete existing chat and overwrite
//
$myChat=$myChats->add('34644016792','34625369986');
//assert chat failed
if ($myChat AND $myChats->search('34625369986') AND !$chatId2=$myChats->search('34625369982'))
		echo "OK. Chat overwritten: Origin = ".$myChat->source." Destination = ".$myChat->destination." Last message at = ".$myChat->interactionDate;
	else
		echo "Error Chat not overwritten";
echo "\n";

//
// 7.- Set chat language
//
$myChats->setLanguage('34644016792', 'ca');
$myChat=$myChats->search('34644016792');

//assert chat failed
if ($myChat->srcLang=='ca')
	echo "OK. Chat language set";
	else
		echo "Error Chat language set";
		echo "\n";

?>
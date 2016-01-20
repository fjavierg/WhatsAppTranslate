<?php

//
//
//  Test Class
//
require_once 'ChatSet.php';


date_default_timezone_set('Europe/Madrid');
echo "\n";
$myChats = new ChatSet();



//
// 1.-Add New Chat
//
$key=$myChats->add('34644016791','34625369981');
//assert chat created
if ($key AND $chatId=$myChats->search('34625369981'))
	echo "OK. Chat found: Origin = ".$myChats->get($chatId)['origin']." Destination = ".$myChats->get($chatId)['destination']." Last message at = ".$myChats->get($chatId)['last_message']." Key = ".$key;
else
	echo "Error Chat can not be created, destination already involved in a chat";
echo "\n";


//
// 2.- Add New Chat
//
$key=$myChats->add('34644016792','34625369982');
//assert chat created
if ($key AND $chatId=$myChats->search('34625369982'))
		echo "OK. Chat found: Origin = ".$myChats->get($chatId)['origin']." Destination = ".$myChats->get($chatId)['destination']." Last message at = ".$myChats->get($chatId)['last_message']." Key = ".$key;
	else
		echo "Error Chat can not be created, destination already involved in a chat";
echo "\n";


//
// 3.- Add New Chat
//
$key=$myChats->add('34644016793','34625369983');
//assert chat created
if ($key AND $chatId=$myChats->search('34625369983'))
		echo "OK. Chat found: Origin = ".$myChats->get($chatId)['origin']." Destination = ".$myChats->get($chatId)['destination']." Last message at = ".$myChats->get($chatId)['last_message']." Key = ".$key;
	else
		echo "Error Chat can not be created, destination already involved in a chat";
echo "\n";

//
// 4.- Add Chat. Destination involved in another valid chat as origin
//
$key=$myChats->add('34644016794','34625369981');
//assert chat failed
if ($key AND $chatId=$myChats->search('34644016794'))
		echo "Error. Chat found: Origin = ".$myChats->get($chatId)['origin']." Destination = ".$myChats->get($chatId)['destination']." Last message at = ".$myChats->get($chatId)['last_message']." Key = ".$key;
	else
		echo "OK. Chat can not be created, destination already involved in a chat as origin";
echo "\n";

//
// 5.- Add Chat. Destination involved in another valid chat as destination
//
$key=$myChats->add('34644016795','34644016792');
//assert chat failed
if ($key AND $chatId=$myChats->search('34644016795'))
		echo "Error. Chat found: Origin = ".$myChats->get($chatId)['origin']." Destination = ".$myChats->get($chatId)['destination']." Last message at = ".$myChats->get($chatId)['last_message']." Key = ".$key;
	else
		echo "OK. Chat can not be created, destination already involved in a chat as destination";
echo "\n";
		
//
// 6.- Add Chat. Origin involved in another valid chat. Should delete existing chat and overwrite
//
$key=$myChats->add('34644016792','34625369986');
//assert chat failed
if ($key AND $chatId=$myChats->search('34625369986') AND !$chatId2=$myChats->search('34625369982'))
		echo "OK. Chat overwritten: Origin = ".$myChats->get($chatId)['origin']." Destination = ".$myChats->get($chatId)['destination']." Last message at = ".$myChats->get($chatId)['last_message']." Key = ".$key;
	else
		echo "Error Chat not overwritten";
echo "\n";

//
// 7.- Set chat language
//
$myChats->setLanguage(1, 'es', 'en');
$chat = $myChats->get(1);
//assert chat failed
if ($chat['lang_origin']=='es' AND $chat['lang_destination']=='en')
	echo "OK. Chat language set";
	else
		echo "Error Chat language set";
		echo "\n";

?>
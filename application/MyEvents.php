<?php

require 'AllEvents.php';
require_once 'Chat.php';
require_once 'ChatSet.php';
require_once 'MScredentials.php';
require_once 'HTTPTranslator.php';

/*
 * Class MyEvents extents AllEvents abstract class
 *
 * Manages whatsapp events
 */
class MyEvents extends AllEvents
{
	const DEFAULT_LANGUAGE = "en";
	const DEFAULT_LANGUAGE_ORIGIN = "es";
	const NEW_CHAT = ' wants to chat with you';
	const NEW_CHAT_OK = "Chat created";
	const NEW_CHAT_ERROR = "Contact busy";
	const NEW_CHAT_ERROR2 = "Not valid number received in contact";
	const HELP = "Attach contact to start a new chat or send [Chat number_to_contact]";
	const STATUS = "You are in a chat with ";
    const SELECT_LANGUAGE = "To change language send: 1-Catalan 2-Spanish 3-French 4-Deutsch 5-English." ;
	protected $LANGUAGES = Array((0) => 'en',(1) => 'ca',(2) => 'es',(3) => 'fr',(4) => 'de',(5) => 'en');
	protected $LANGUAGE_NAMES = Array((0) => 'Engish',(1) => 'Catal�',(2) => 'Castellano',(3) => 'French',(4) => 'Deutsch',(5) => 'English');
	
	/**
	 * This is a list of all current events. Uncomment the ones you wish to listen to.
	 * Every event that is uncommented - should then have a function below.
	 */
    public $activeEvents = [
//        'onClose',
//        'onCodeRegister',
//        'onCodeRegisterFailed',
//        'onCodeRequest',
//        'onCodeRequestFailed',
//        'onCodeRequestFailedTooRecent',
        'onConnect',
//        'onConnectError',
//        'onCredentialsBad',
//        'onCredentialsGood',
        'onDisconnect',
//        'onDissectPhone',
//        'onDissectPhoneFailed',
//        'onGetAudio',
//        'onGetBroadcastLists',
//        'onGetError',
//        'onGetExtendAccount',
//        'onGetGroupMessage',
//        'onGetGroupParticipants',
//        'onGetGroups',
//        'onGetGroupsInfo',
//        'onGetGroupsSubject',
//        'onGetImage',
//        'onGetLocation',
        'onGetMessage',
//        'onGetNormalizedJid',
//        'onGetPrivacyBlockedList',
//        'onGetProfilePicture',
//        'onGetReceipt',
//        'onGetServerProperties',
//        'onGetServicePricing',
//        'onGetStatus',
//        'onGetSyncResult',
//        'onGetVideo',
        'onGetvCard',
//        'onGroupCreate',
//        'onGroupisCreated',
//        'onGroupsChatCreate',
//        'onGroupsChatEnd',
//        'onGroupsParticipantsAdd',
//        'onGroupsParticipantsPromote',
//        'onGroupsParticipantsRemove',
//        'onLoginFailed',
//        'onLoginSuccess',
//        'onAccountExpired',
//        'onMediaMessageSent',
//        'onMediaUploadFailed',
//        'onMessageComposing',
//        'onMessagePaused',
//        'onMessageReceivedClient',
//        'onMessageReceivedServer',
//        'onPaidAccount',
//        'onPing',
//        'onPresenceAvailable',
//        'onPresenceUnavailable',
//        'onProfilePictureChanged',
//        'onProfilePictureDeleted',
//        'onSendMessage',
//        'onSendMessageReceived',
//        'onSendPong',
//        'onSendPresence',
//        'onSendStatusUpdate',
//        'onStreamError',
//        'onUploadFile',
//        'onUploadFileFailed',
    ];
    protected $translator;
    protected $myChats;
    protected $debug;
    
    /*
     * Constructor. Creates HTTPTranslator and ChatSet Objects
     *
     * @param WhatsProt $whatsprot      WhatsUp protocol object
     *
     * @return .
     *
     */
    public function __construct($whatsProt,$debug) 
    {
    	global $clientID;
    	global $clientSecret;
    	
    	parent::__construct($whatsProt);
    	$this->debug = $debug;
    	//Create the Translator Object.
    	$this->translator = new HTTPTranslator(MSCLIENTID,MSCLIENTSECRET,$debug);
    	$this->myChats = new ChatSet();
    	return $this;
    }
    /*
     * Creates a new chat and notifies both parties
     *
     * @param string $from      Chat originator
     * @param string $fromName	Chat originator Name
     * @param string $to    	Chat destination
     *
     * @return string 	Chat identifier
     *
     */
    protected function newChat($from,$fromName,$to)
    {
    	if ($id =$this->myChats->add($from,$to,self::DEFAULT_LANGUAGE_ORIGIN,self::DEFAULT_LANGUAGE)){
    		$this->whatsProt->sendMessage($from,self::NEW_CHAT_OK );
            $this->whatsProt->sendMessage($from,self::SELECT_LANGUAGE );
    		$this->whatsProt->sendMessage($to,$fromName.self::NEW_CHAT );
    		$this->whatsProt->sendMessage($to,self::SELECT_LANGUAGE );
    		return $id;
    	}
    	else {
    		$this->whatsProt->sendMessage($from,self::NEW_CHAT_ERROR );
    		return null;
    	}
    
    }
    /*
     * Forwards a message to the counterppart in chat
     *
     * @param string $body      Message to be forwarded
     * @param string $from		Message originator.
     * @param string $chatid    Chat identfiert.
     *
     * @return .
     *
     */
    protected function forward($body,$from,$fromName,$chat)
    {

    	if ($from == $chat->source){
    		$to = $chat->destination;
    		$to_lang = $chat->destLang;
    	}
    	else {
    		$to = $chat->source;
    		$to_lang = $chat->srcLang;
    	}
    	$translatedBody = $this->translator->translate($body,$to_lang);
    	$this->whatsProt->sendMessage($to,"[".$fromName."] ".$translatedBody );
    	$chat->updateDate();
    }
    public function onConnect($mynumber, $socket)
    {
        echo "Phone number $mynumber connected successfully! \n";
    }

    public function onDisconnect($mynumber, $socket)
    {
        echo "Phone number $mynumber is disconnected! \n";
    }
    public function onGetMessage( $mynumber, $from, $id, $type, $time, $name, $body )
    {
    	$tmp=explode("@",$from);
    	$from = $tmp[0];
    	echo "Message from $from:$body\n\n";
    	
    	switch ($body) {
    		case 'Help':
    		case 'help':
    			// Send information abut current chat (if existing) and help message
    			if ($chat = $this->myChats->search($from)){
    				$this->whatsProt->sendMessage($from,self::STATUS.$chat->source." -> ".$chat->destination );
    			}
    			$this->whatsProt->sendMessage($from,self::HELP );
    			break;
    		case (preg_match('/Chat*/', $body) ? true : false) :
    			$pattern = '/hat [+]*([0-9]{9,14})/'; //Basic validation
    			if (preg_match($pattern, $body, $matches))
    				$to = $matches[1];
    			else
    				$to='';
    			if (strlen($to)==9 AND $to[0]==6) $to="34".$to; //Spanish mobile numbers whithout prefix Add prefix
    			if ($to)
    				$this->newChat($from, $name, $to);
    			else 
    				$this->whatsProt->sendMessage($from,"[".self::NEW_CHAT_ERROR );
    			break;
    		case (preg_match('/[1-5]/', $body) ? true : false) :
    			if ($chat = $this->myChats->search($from)){
    				if ($chat->source == $from)	$chat->setSrcLanguage ($this->LANGUAGES[$body]);
    				if ($chat->destination == $from) $chat->setDestLanguage ($this->LANGUAGES[$body]);
    			}
   				//$this->myChats->setLanguage($from,$this->LANGUAGES[$body]);
   				$this->whatsProt->sendMessage($from,"Language changed to ".$this->LANGUAGE_NAMES[$body] );
    			break;
    		default:
    			if ($chat = $this->myChats->search($from)){
    				$this->forward($body, $from, $name, $chat);
    			}
    			else{
    				// Reflect translated message if originatpr not involved in a chat
    				$translatedBody = $this->translator->translate($body,self::DEFAULT_LANGUAGE);
    				$this->whatsProt->sendMessage($from,"[".$name."] ".$translatedBody );
    			}
    			break;
    	}
    	
    	/*$mp3 = $this->translator->speak($translatedBody, 'en');
    	$fp = fopen('../data/data.mp3', 'w');
    	fwrite($fp, $mp3);
    	fclose($fp);
    	$this->whatsProt->sendMessageAudio($fromNb,'../data/data.mp3' );*/
    	
    	 
    }
    public function onGetvCard($mynumber, $from, $id, $type, $time, $name, $vcardname, $vcard, $fromJID_ifGroup = null)
    {
    	$tmp=explode("@",$from);
    	$from = $tmp[0];
    	// Extracts WA identifier from vCard
    	$pattern = '/waid=([0-9]+)/';
     	preg_match($pattern, $vcard, $matches);
     	
    	if ($to=$matches[1]){
			$this->newChat($from, $name, $to);
    	}
    	else {
    		$this->whatsProt->sendMessage($from,self::NEW_CHAT_ERROR2 );
    	}
    }

}

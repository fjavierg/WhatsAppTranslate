<?php

require 'AllEvents.php';
require_once 'ChatSet.php';

class MyEvents extends AllEvents
{
    /**
     * This is a list of all current events. Uncomment the ones you wish to listen to.
     * Every event that is uncommented - should then have a function below.
     *
     * @var array
     */
	const DEFAULT_LANGUAGE = "en";
	const NEW_CHAT = 'Hi $name wants to chat with you';
	const NEW_CHAT_OK = "Chat created";
	const NEW_CHAT_ERROR = "Contact busy";
	const NEW_CHAT_ERROR2 = "Not valid number received in contact";
	const HELP = "Attach contact to start a new chat";
	const STATUS = "You are in a chat with ";
	
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
    
    public function __construct($whatsProt,$translator)
    {
    	parent::__construct($whatsProt);
    	$this->translator = $translator;
    	$this->myChats = new ChatSet();
    	return $this;
    }

    public function onConnect($mynumber, $socket)
    {
        echo "Phone number $mynumber connected successfully!";
    }

    public function onDisconnect($mynumber, $socket)
    {
        echo "Phone number $mynumber is disconnected!";
    }
    public function onGetMessage( $mynumber, $from, $id, $type, $time, $name, $body )
    {
    	$tmp=explode("@",$from);
    	$from = $tmp[0];
    	echo "Message from $from:$body\n\n";
    	
    	switch ($body) {
    		case 'Help':
    			if ($chatId = $this->myChats->search($from)){
    				$this->whatsProt->sendMessage($from,self::STATUS.$this->myChats->get($chatId)['destination']." -> ".$this->myChats->get($chatId)['destination'] );
    			}
    			$this->whatsProt->sendMessage($from,self::HELP );
    			break;
    		default:
    			if ($chatId = $this->myChats->search($from)){
    				$this->forward($body, $from, $chatid);
    			}
    			else{
    				echo "Chat not found \n";
    				$to = $from;
    				$to_lang = self::DEFAULT_LANGUAGE;
    				$translatedBody = $this->translator->translate($body,$to_lang);
    				$this->whatsProt->sendMessage($to,"[".$name."] ".$translatedBody );
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
    	$pattern = '/waid=([0-9]+)/';
     	preg_match($pattern, $vcard, $matches);
    	echo "vCard received : Number = $matches[1]";
    	if ($to=$matches[1]){
    		if ($id =$this->myChats->add($from,$matches[1])){
    			$this->whatsProt->sendMessage($from,self::NEW_CHAT_OK );
    			$this->whatsProt->sendMessage($to,"Hi ".$name." wants to chat with you." );
    			$this->whatsProt->sendMessage($to,"Please select your language: 1-English." );
    		}
    		else
    			$this->whatsProt->sendMessage($from,self::NEW_CHAT_ERROR );
    	}
    	else {
    		$this->whatsProt->sendMessage($from,self::NEW_CHAT_ERROR2 );
    	}
    }
    protected function forward($body,$from,$chatid)
    {
    	echo "Chat found \n";
    	$chat = $this->myChats->get($chatId);
    	if ($from == $chat['origin']){
    		echo "Message from origin \n";
    		$to = $chat['destination'];
    		$to_lang = self::DEFAULT_LANGUAGE;
    	}
    	else {
    		echo "Message from destination \n";
    		$to = $chat['origin'];
    		$to_lang = 'es';
    	}
    	$translatedBody = $this->translator->translate($body,$to_lang);
    	$this->whatsProt->sendMessage($to,"[".$name."] ".$translatedBody );
    }
}

<?php

require_once 'C:/Users/Javier/git/Whatsapp/src/whatsprot.class.php';
require_once 'credentials.php';
require_once 'MScredentials.php';
require_once 'MSHTTPTranslator.php';

$username = $config['TEST']['fromNumber'];
$password = $config['TEST']['waPassword'];
$nickname = $config['TEST']['nick'];
$debug = false;


//Create the Translator Object.
$translatorObj = new HTTPTranslator($clientID,$clientSecret);

//Connect to whatsapp

echo "[] logging in as '$nickname' ($username)\n";
$w = new WhatsProt($username, $nickname, $debug);
$w->connect();
$w->loginWithPassword($password);
echo "[*]Conectado a WhatsApp\n\n";

$pn = new ProcessNode($translatorObj,$w);

while(1){
    $w->pollMessage();
    $msgs = $w->getMessages();
    foreach($msgs as $m)
        $pn->process($m);
}


class ProcessNode
{   
	public $translator;
	public $w;
	public $target = '34644016790';
	public $toLanguage = "en";

    public function __construct($translator,$w)
    {
        $this->translator = $translator;
        $this->w = $w;
    }
    public function process($node)
    {
        $text = $node->getChild('body');
        $text = $text->getData();
        echo "\n- ".$text;
        $translatedStr = $this->translator->translate($text,$this->toLanguage);
        $this->w->sendMessage($this->target,$translatedStr );
        echo "\n- ".$translatedStr;
    }
}

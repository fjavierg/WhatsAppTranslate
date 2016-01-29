<?php
require 'Chat.php';

class ChatSet
//
// This class implements a set of Chats. 
// Each Chat object is composed of origin, destination and date of last message sent
// Unicity: A number may be involved in a unique chat either as origin or destination
//          Adding a new chat deletes any existing chat where origin is involved
//          Adding a new chat fails if destination is involved in a valid chat (not expired)
// Chats expired after $EXPIRATION_TIME
//

{
    const EXPIRATION_TIME = 6000;
    protected $chats=Array();

    
     //
    
    //
    // Adds a new chat. Checks unicity.
    //      Input: origin, destination, lang_origin (optional), lang_destination (optional)
    //      Returns Chat Id
    //
    //
    public function add($origin, $destination, $lang_origin='',$lang_destination='')
    {
    	if ($myChat = $this->search($origin)){
    		$myChat->update($origin, $destination, $lang_origin,$lang_destination);
    		return $myChat;
    	}

        if ($myChat=$this->search($destination))
            return false;
      
        $myChat = new Chat($origin, $destination, $lang_origin,$lang_destination);
        $this->chats[]= $myChat;
        
        return $myChat;
    }

    //
    // Search for chats where number is involved either as origin or destination and returns Chat id
    //      Input: Number
    //      Returns Chat Object
    //
    
    
    public function search($number)
    {
    	$neededObject = array_filter(
    			$this->chats,
    			function ($e) use (&$number) {
    				return ($e->source == $number OR $e->destination == $number) AND (time() - strtotime($e->interactionDate)<self::EXPIRATION_TIME);
    			}
    			);
    	
        if (!empty($neededObject))return current($neededObject);
        	else return null;      
    }
    

    //
    // Sets chat language
    //      Input: number, language
    //      Returns:
    //

    public function setLanguage($number,$language)
    {
    	if ($myChat = $this->search($number)){
	    	if ($myChat->source == $number){
	    		$myChat->setSrcLanguage($language);
	    	}
	    	else {
	    		$myChat->setDestLanguage($language);
	    	}
    	}
    }
}
?>
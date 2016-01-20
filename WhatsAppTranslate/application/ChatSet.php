<?php
class ChatSet
//
// This class implements a set of Chats. 
// Each Chat object is composed of origin, destination and date of last message sent
// Unicity: A number may be involved in a unique chat either as origin or destination
//          Adding a new chat deletes any existing chat where origin is involved
//          Adding a new chat fails if destination is involved in a valid chat (not expired)
//

{
    protected $EXPIRATION_TIME = 6000;
    protected $chats=Array
    (
        (0) => Array
            (
                ('origin') => 'Dummy',
                ('destination') => 'Dummy',
                ('last_message') => '2016-01-18T11:20:20+01:00'
            )
    );

    
     //
    // Gets chat from id
    //      Input: Chat id
    //      Returns Chat: Array(('origin') => ,('destination') => ,('last_message') );
    //
    public function get($key)
    {
        return $this->chats[$key];      
    }
    
    //
    // Adds a new chat. Checks unicity.
    //      Input: origin, destination
    //      Returns Chat Id
    //
    //
    public function add($origin, $destination)
    {
        if ($key=$this->search($origin)){
            $this->chats[$key]=Array(('origin') => $origin,('destination') => $destination,('last_message') => date('c'));
            return $key;
        }
        if ($key=$this->search($destination))
            return false;
            
        $this->chats[]=Array(('origin') => $origin,('destination') => $destination,('last_message') => date('c'));
        end($this->chats);
        return key($this->chats);
    }

    //
    // Search for chats where number is involved either as origin or destination and returns Chat id
    //      Input: Number
    //      Returns Chat id
    //
    public function search($number)
    {
        $key = null;
        
        if (!$key=array_search($number, array_column($this->chats, 'origin'))){
            $key=array_search($number, array_column($this->chats, 'destination'));
        }
        
        if ($key) {
            if (time() - strtotime($this->chats[$key]['last_message'])>$this->EXPIRATION_TIME) {
                $key = null;
                }
            }
        return $key;      
    }
    
    //
    // Updates chat last message with current date
    //      Input: Chat Id
    //      Returns:
    //
    public function updateDate($key)
    {
        $this->chats[$key]['last_message'] = date('c');      
    }
}
?>
<?php 
class WhatsProt
{

    public function sendMessage($to, $plaintext, $force_plain = false)
    {
    	global $output;
    	$output = $output. "Message to: $to->$plaintext \n";
    }
}
?>
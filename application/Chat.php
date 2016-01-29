<?php 

class Chat
{
	public $source;
	public $destination;
	public $srcMessage;
	public $destMessage;
	public $interactionDate;
	public $srcLang;
	public $destLang;
	
	public function __construct ($source,$destination,$srcLang,$destLang)
	{
		$this->source = $source;
		$this->destination = $destination;
		$this->srcLang = $srcLang;
		$this->destLang = $destLang;
		$this->interactionDate = date('c');
	}
	public function update ($source,$destination,$srcLang,$destLang)
	{
		$this->source = $source;
		$this->destination = $destination;
		$this->srcLang = $srcLang;
		$this->destLang = $destLang;
		$this->interactionDate = date('c');
	}
	public function setSrcLanguage($lang)
	{
		$this->srcLang = $lang;
	}
	public function setDestLanguage($lang)
	{
		$this->destLang = $lang;
	}
	public function setSrcMessage($message)
	{
		$this->srcMessage = $message;
	}
	public function setDestMessage($message)
	{
		$this->destMessage = $message;
	}
	public function updateDate()
	{
		$this->interactionDate = date('c');
	}
	
}

?>
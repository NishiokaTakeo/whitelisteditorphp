<?php

date_default_timezone_set('Australia/Perth');

require_once( __DIR__ . '/class.logger.php');


final class Entry
{

	private $raw= "";	//white list file path.
	private $logger ;
	public $key = "";
	public $flag = "";

    public function __construct($raw)
    {
		$this->logger = new Logger(__FILE__); 
		
		if (!empty(trim($raw)))
		{
			$this->raw = $raw;
		
			$this->parse();
			// $this->logger->trace(sprintf("Entry is %s",$path));	
		}
		
    }

    public function __destruct()
    {

    }

	public static function GetEntryBy($key,$flag= 'OK')
	{
		if ( empty(trim($key))) return null;

		$entry = new Entry($key . "\t" . $flag . "\n");
		$entry->parse();

		return $entry;
	}

	public function parse()
	{
		$sp = Entry::entryBy($this->raw);
		
		$this->key = $sp[0];
		$this->flag = $sp[1];
	}

	public function  GetLineForFile()
	{
		$str =  $this->key . "\t" . $this->flag ."\n";

		return $str;
	}

	public function formatOK()
	{
		$domainMatched = preg_match("/^[^@\s]+\.[^@\s]+$/",$this->key);

		$emailMatched = preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/",$this->key);
	
		if ( $domainMatched || $emailMatched)
		{
			return true;
		}
		else
		{
			return false;
		}	
	}


	static function entryBy($raw)
	{
		$sp = preg_split ("/(\t|\s)+/", $raw); 

		return $sp;
	}

}

?>
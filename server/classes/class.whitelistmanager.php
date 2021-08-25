<?php

date_default_timezone_set('Australia/Perth');

require_once( __DIR__ . '/class.logger.php');


final class WhiteListMgr
{

    // private $folder = "";
    // private $filename = "";
    // private $logFormat = "%s    %s  %s";
	private $filepath= "";	//white list file path.
	private $logger;

    public function __construct($path)
    {
		$this->logger = new Logger(__FILE__); 
		
		$this->filepath = $path;
		
		$this->logger->trace(sprintf("White List Path is %s",$path));

        // $this->folder = realpath(dirname(__FILE__)) . '/' . "../logs/";
        // $this->filename  = date("Y.m.d") . ".log";
		// $this->script_path = $scriptpath;
    }

    public function __destruct()
    {

    }

	public function exclude($key)
	{
		$key = trim($key);

		if ( empty($key) ) return;
		$ans = array();
		$latetEntries = $this->read($this->filepath);

		foreach($latetEntries as $item)
		{
			$entry = new Entry($item);
			$entry->parse();
			if ( $entry->key != $key )
			{
				array_push($ans, $item);
			}
		}

		return $ans;

	}

	/**
	 * Delete entry by given key.
	 * Do nothing if no entry found.
	 */
	public function delete($key)
	{
		
		if ( empty($key) ) return;

		$latetEntries = $this->read($this->filepath);

		$array = $this->exclude($key);
		
		$this->out($this->filepath, $array);
	}

	public function add($raw)
	{
		if ( empty($raw) ) return;

		$entry = new Entry($raw);

		$entry->parse();

		if ( $entry->formatOK() )
		{
			$array = $this->exclude($entry->key);

		 	array_push($array, $entry->GetLineForFile());

			 $this->out($this->filepath, $array);
		}
		else
		{
			throw new Exception("Invalid");
		}

	}

	public function edit($key,$domainOrEmail )
	{
		if ( empty($key) ) return;

		$entry = Entry::GetEntryBy($domainOrEmail);

		if ( $entry->formatOK() )
		{
			$this->delete($key);
			
			$this->add($entry->GetLineForFile());
		}
		else
		{
			throw new Exception("Invalid");
		}
	}

	public function keys()
	{
		$ans = array();
		$latetEntries = $this->read($this->filepath);

		foreach($latetEntries as $item)
		{
			$entry = Entry::GetEntryBy($item);
			
			if ( $entry->formatOK())
			{
				array_push($ans,$entry->key);
			}
		}

		return $ans;

	}

	public static function entryBy($raw)
	{
		$sp = preg_replace ("(/\t|\s/)+", $line); 

		return $sp;
	}

	public static function read($path)
	{
		$logger = new Logger(__FILE__); 

		$ans = array();
		$array = file($path);

		foreach($array as $item)
		{
			if ( !empty(trim($item)))
			{
				$entry = new Entry($item);
				$entry->parse();

				if ( !empty($entry->key) && !empty($entry->flag) && $entry->formatOK() )
				{
					array_push($ans,$item);
					continue;
				}
				else
				{
					$logger->warn(sprintf("Found not valid item: %s",$item));
				}
				
			}

			

		}

		return $ans;
	}

    // public function info($message)
    // {
    //     $level = 'info';
    //     try
    //     {
    //         $formated = $this->format($level ,$message);

    //         $this->put( $level, $formated );
    //     }
    //     catch(Exception $e)
    //     {

    //     }
    // }
	
    // function put($level,$message)
    // {
    //     if (false == file_exists($this->folder . $level))
    //     {
    //         mkdir($this->folder . $level,0777,true);
    //     }

    //     file_put_contents( $this->folder . $level . '/' . $this->filename,  $message . PHP_EOL , FILE_APPEND);
    // }



	// function read()
	// {
	// 	$array = file($path);
	
	// 	return $array;
	// }
	
	public static function out($path, $lines)
	{
		$write = array();
		foreach($lines as $line)
		{
			$line = preg_replace( "/\r|\n/", "", $line);
			$line .= "\n";
			array_push($write,$line);
		}

		file_put_contents($path, $write, LOCK_EX);	
	}

}

?>
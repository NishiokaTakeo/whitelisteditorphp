<?php
include ( __DIR__ . '/../inc/global.php');
require_once( __DIR__ . '/../classes/class.logger.php');
require_once( __DIR__ . '/../classes/class.whitelistmanager.php');
require_once( __DIR__ . '/../classes/class.entry.php');

$Logger = new Logger($_SERVER['SCRIPT_FILENAME']); 


header('Access-Control-Allow-Origin: *');

// $path = 'C:\Users\takeo\source\repos\whitelisteditor\data\supervisor_allowed_email';


// $key 
// $mode
// $domainOrEMail
$key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';

// $Logger->trace(sprintf ("Request received: %s %s %s",$key,$mode,$domainOrEMail));

//$entry = Entry::GetEntryBy($key);

function run()
{
	Global $path;
	Global $key;
	try
	{
		$mgr = new WhiteListMgr($path);
		$keys = $mgr->keys();
		
		$keys = filter($keys, $key);
		
		header('Content-Type: application/json');
		echo json_encode($keys);	

	}
	catch(Exception $e)
	{
		$Logger->error(sprintf ("ERROR: %s\t%s\t%s\t$s",$e->getMessage(), $key,$mode,$domainOrEMail));

		header('Content-Type: application/json');
		echo json_encode("NG");	
	}
}

function filter($array, $word)
{
	if ( empty(trim($word))) return $array;

	$ans = array();
	foreach($array as $item)
	{
		if ( strpos($item, $word) !== false )
		{
			array_push($ans, $item);
		}		
	}

	return $ans;
}

run();

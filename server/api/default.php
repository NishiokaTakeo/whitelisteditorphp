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
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$domainOrEMail = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';

$Logger->trace(sprintf ("Request received: %s %s %s",$key,$mode,$domainOrEMail));

$entry = Entry::GetEntryBy($key);

function run()
{
	Global $key;
	Global $mode;
	Global $domainOrEMail;
	Global $entry;
	Global $Logger;
	Global $path;
	try
	{
		if ( $entry->formatOK() )
		{
		
				$mgr = new WhiteListMgr($path);
		
				switch($mode)
				{
					case 'add':
						$mgr->add($entry->GetLineForFile());
						break;
					
					
					case 'delete':
						$mgr->delete($entry->key);
						break;
					
					
					// case 'edit':	// not support

					// 	$work = Entry::GetEntryBy($domainOrEMail);
					// 	if ( $work->formatOK() )
					// 	{
					// 		$mgr->edit($entry->key, $work->key);
					// 	}

						break;		

					default:
						throw new Exception("Invalid");
				}

			header('Content-Type: application/json');
			echo json_encode("OK");			
		}
	}
	catch(Exception $e)
	{
		$Logger->error(sprintf ("ERROR: %s\t%s\t%s\t$s",$e->getMessage(), $key,$mode,$domainOrEMail));

		header('Content-Type: application/json');
		echo json_encode("NG");	
	}
}



run();

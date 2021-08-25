<?php

date_default_timezone_set('Australia/Perth');

class Logger
{


    private $folder = "";
    private $filename = "";
    private $logFormat = "%s    %s  %s";
	private $script_path = "";
    public function __construct($scriptpath)
    {
        $this->folder = realpath(dirname(__FILE__)) . '/' . "../logs/";
        $this->filename  = date("Y.m.d") . ".log";
		$this->script_path = $scriptpath;
    }

    public function __destruct()
    {

    }

    public function info($message)
    {
        $level = 'info';
        try
        {
            $formated = $this->format($level ,$message);

            $this->put( $level, $formated );
        }
        catch(Exception $e)
        {

        }
    }

    public function trace($message)
    {
        $level = 'trace';
        try
        {
            $formated = $this->format($level ,$message);

            $this->put( $level, $formated );
        }
        catch(Exception $e)
        {

        }
    }

    public function debug($message)
    {
        $level = 'debug';
        try
        {
            $formated = $this->format($level ,$message);

            $this->put( $level, $formated );
        }
        catch(Exception $e)
        {

        }
    }
    public function warn($message)
    {
        $level = 'warn';
        try
        {
            $formated = $this->format($level ,$message);

            $this->put( $level, $formated );
        }
        catch(Exception $e)
        {

        }
    }	
    public function error($message)
    {
        $level = 'error';
        try
        {
            $formated = $this->format($level ,$message);

            $this->put( $level, $formated );
        }
        catch(Exception $e)
        {

        }
    }		
    function format($level, $message)
    {
        $logstr = sprintf($this->logFormat, date("Y.m.d H:i:s e"), strtoupper($level), $this->script_path . "\t" .$message );

        return $logstr;
    }

    function put($level,$message)
    {
        if (false == file_exists($this->folder . $level))
        {
            mkdir($this->folder . $level,0777,true);
        }

        file_put_contents( $this->folder . $level . '/' . $this->filename,  $message . PHP_EOL , FILE_APPEND);
    }
}

?>
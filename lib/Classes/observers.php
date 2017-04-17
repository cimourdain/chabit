<?php

namespace chabit;

class observers //singleton
{
  use tDatesManager;

  protected static $observer; // unique instance
  
  protected function __construct() { } // set contructor as private
  protected function __clone() { } // set clone as private

  protected $logs = [];

  
  public static function getInstance()
  {
    if (!isset(self::$observer)) // if class is not already created 
    {
      self::$observer = new self; // instanciate new

    }
    
    return self::$observer;
  }

  public static function addNewLog($source, $message, $log_level)
  {

  	array_push( self::$observer -> logs, array('LEVEL' => $log_level, 'MESSAGE' => $message, 'SOURCE' => $source));//, 'DATE' => $this -> getCurrentDate()
  }

  public static function getLogs($log_level)
  {
  	echo '<hr />======== LOG<br />';
  	foreach(self::$observer -> logs as $log)
  	{
  		
  		if($log['LEVEL'] <= $log_level)
  		{
  			echo $log['SOURCE'].'::'.$log['LEVEL'].'::'.$log['MESSAGE'].'<br />';
  		}
  		
  	}
  	echo '======== //LOG<hr />';
  }

}
?>
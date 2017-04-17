<?php

namespace chabit;

trait tObservedClass
{
	protected $observer;
	
	protected function setObserver($class_name)
	{
		$this -> observer = observers::getInstance();
		$this -> observer -> addNewLog($class_name, 'Class creation', 3);
	}

	public function getObserver()
	{
		return $this -> observer;
	}
}

?>
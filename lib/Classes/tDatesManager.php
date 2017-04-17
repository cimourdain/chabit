<?php

namespace chabit;

trait tDatesManager
{
	protected $current_date;
	protected $timezone;
	protected $date_format = 'Y-m-d H:i:s';

	public function getCurrentDate()
	{
		$timezone = new \DateTimeZone("UTC");
		$current_date = new \DateTime();
		return $current_date -> setTimezone( $timezone );
	}
}

?>
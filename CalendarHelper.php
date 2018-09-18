<?php

use Carbon\Carbon;

class CalendarHelper
{

	public static $timezones = array(
		"Pacific/Wake" => "(GMT-12:00) International Date Line West",
		"Pacific/Apia" => "(GMT-11:00) Samoa",
		"Pacific/Honolulu" => "(GMT-10:00) Hawaii",
		"America/Anchorage" => "(GMT-09:00) Alaska",
		"America/Los_Angeles" => "(GMT-08:00) Pacific Time (US & Canada); Tijuana",
		"America/Phoenix" => "(GMT-07:00) Arizona",
		"America/Chihuahua" => "(GMT-07:00) Mazatlan",
		"America/Denver" => "(GMT-07:00) Mountain Time (US & Canada)",
		"America/Managua" => "(GMT-06:00) Central America",
		"America/Chicago" => "(GMT-06:00) Central Time (US & Canada)",
		"America/Mexico_City" => "(GMT-06:00) Monterrey",
		"America/Regina" => "(GMT-06:00) Saskatchewan",
		"America/Bogota" => "(GMT-05:00) Quito",
		"America/New_York" => "(GMT-05:00) Eastern Time (US & Canada)",
		"America/Indiana/Indianapolis" => "(GMT-05:00) Indiana (East)",
		"America/Halifax" => "(GMT-04:00) Atlantic Time (Canada)",
		"America/Caracas" => "(GMT-04:00) La Paz",
		"America/Santiago" => "(GMT-04:00) Santiago",
		"America/St_Johns" => "(GMT-03:30) Newfoundland",
		"America/Sao_Paulo" => "(GMT-03:00) Brasilia",
		"America/Argentina/Buenos_Aires" => "(GMT-03:00) Georgetown",
		"America/Godthab" => "(GMT-03:00) Greenland",
		"America/Noronha" => "(GMT-02:00) Mid-Atlantic",
		"Atlantic/Azores" => "(GMT-01:00) Azores",
		"Atlantic/Cape_Verde" => "(GMT-01:00) Cape Verde Is.",
		"Africa/Casablanca" => "(GMT) Monrovia",
		"Europe/London" => "(GMT) London",
		"Europe/Berlin" => "(GMT+01:00) Vienna",
		"Europe/Belgrade" => "(GMT+01:00) Prague",
		"Europe/Paris" => "(GMT+01:00) Paris",
		"Europe/Sarajevo" => "(GMT+01:00) Zagreb",
		"Africa/Lagos" => "(GMT+01:00) West Central Africa",
		"Europe/Istanbul" => "(GMT+02:00) Minsk",
		"Europe/Bucharest" => "(GMT+02:00) Bucharest",
		"Africa/Cairo" => "(GMT+02:00) Cairo",
		"Africa/Johannesburg" => "(GMT+02:00) Pretoria",
		"Europe/Helsinki" => "(GMT+02:00) Vilnius",
		"Asia/Jerusalem" => "(GMT+02:00) Jerusalem",
		"Asia/Baghdad" => "(GMT+03:00) Baghdad",
		"Asia/Riyadh" => "(GMT+03:00) Riyadh",
		"Europe/Moscow" => "(GMT+03:00) Volgograd",
		"Africa/Nairobi" => "(GMT+03:00) Nairobi",
		"Asia/Tehran" => "(GMT+03:30) Tehran",
		"Asia/Muscat" => "(GMT+04:00) Muscat",
		"Asia/Tbilisi" => "(GMT+04:00) Yerevan",
		"Asia/Kabul" => "(GMT+04:30) Kabul",
		"Asia/Yekaterinburg" => "(GMT+05:00) Ekaterinburg",
		"Asia/Karachi" => "(GMT+05:00) Tashkent",
		"Asia/Calcutta" => "(GMT+05:30) New Delhi",
		"Asia/Katmandu" => "(GMT+05:45) Kathmandu",
		"Asia/Novosibirsk" => "(GMT+06:00) Novosibirsk",
		"Asia/Dhaka" => "(GMT+06:00) Dhaka",
		"Asia/Colombo" => "(GMT+06:00) Sri Jayawardenepura",
		"Asia/Rangoon" => "(GMT+06:30) Rangoon",
		"Asia/Bangkok" => "(GMT+07:00) Jakarta",
		"Asia/Krasnoyarsk" => "(GMT+07:00) Krasnoyarsk",
		"Asia/Hong_Kong" => "(GMT+08:00) Urumqi",
		"Asia/Irkutsk" => "(GMT+08:00) Ulaan Bataar",
		"Asia/Singapore" => "(GMT+08:00) Singapore",
		"Australia/Perth" => "(GMT+08:00) Perth",
		"Asia/Taipei" => "(GMT+08:00) Taipei",
		"Asia/Tokyo" => "(GMT+09:00) Tokyo",
		"Asia/Seoul" => "(GMT+09:00) Seoul",
		"Asia/Yakutsk" => "(GMT+09:00) Yakutsk",
		"Australia/Adelaide" => "(GMT+09:30) Adelaide",
		"Australia/Darwin" => "(GMT+09:30) Darwin",
		"Australia/Brisbane" => "(GMT+10:00) Brisbane",
		"Australia/Sydney" => "(GMT+10:00) Sydney",
		"Pacific/Guam" => "(GMT+10:00) Port Moresby",
		"Australia/Hobart" => "(GMT+10:00) Hobart",
		"Asia/Vladivostok" => "(GMT+10:00) Vladivostok",
		"Asia/Magadan" => "(GMT+11:00) Solomon Is.",
		"Pacific/Auckland" => "(GMT+12:00) Wellington",
		"Pacific/Fiji" => "(GMT+12:00) Marshall Is.",
		"Pacific/Tongatapu" => "(GMT+13:00) Nuku'alofa",
	);
	public static $accordingTime = array(
		6 => '6am',
		7 => '7am',
		8 => '8am',
		9 => '9am',
		10 => '10am',
		11 => '11am',
		12 => '12pm',
		13 => '1pm',
		14 => '2pm',
		15 => '3pm',
		16 => '4pm',
		17 => '5pm',
		18 => '6pm',
		19 => '7pm',
		20 => '8pm',
		21 => '9pm',
		22 => '10pm'
	);

	public static function viewPreferences($sessionVarName, $startDay = 'Monday', $preferedStartDate = null)
	{
		$sessionPreferences = Yii::app()->user->getState($sessionVarName, array());
		$selectedTimestamp = strtotime(Yii::app()->request->getPost(
				'selectedDate', isset($preferedStartDate) ? $preferedStartDate : (isset($sessionPreferences['selectedDate']) ? $sessionPreferences['selectedDate'] : 'now')));
		$view = Yii::app()->request->getPost('view', isset($sessionPreferences['view']) ? $sessionPreferences['view'] : 'week');
		$roleId = Yii::app()->request->getPost('filterRole', isset($sessionPreferences['filterRole']) ? $sessionPreferences['filterRole'] : 0);

		$month = date("m", $selectedTimestamp);
		$year = date("Y", $selectedTimestamp);

		switch ($view) {
			case 'month':
				$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$viewStart = mktime(0, 0, 0, $month, 1, $year);
				$viewEnd = mktime(0, 0, 0, $month, $days_in_month, $year);
				$viewStartPrevious = strtotime("-1 month", $viewStart);
				$viewStartNext = strtotime("+1 day", $viewEnd);
				$heading = date('F, Y', $viewStart);
				$monthStartDate = date('Y-m-01', strtotime($heading));
				$monthEndDate = date('Y-m-t', strtotime($heading));
				$endDay = date("l", strtotime('next ' . $startDay . ' -1 day'));

				if (date("l", $viewStart) !== $startDay) {
					$previousSaturday = strtotime('previous ' . $startDay, $viewStart);
					$monthStart = date('m', $previousSaturday);
					$yearStart = date("Y", $previousSaturday);
					$dayStart = date('j', $previousSaturday);
					$viewStart = mktime(0, 0, 0, $monthStart, $dayStart, $yearStart);
				}

				if (date("l", $viewEnd) !== $endDay) {
					$nextFriday = strtotime('next ' . $endDay, $viewEnd);
					$monthEnd = date('m', $nextFriday);
					$yearEnd = date('Y', $nextFriday);
					$dayEnd = date('j', $nextFriday);
					$viewEnd = mktime(0, 0, 0, $monthEnd, $dayEnd, $yearEnd);
				}
				break;

			case 'week':
				if (date("l", $selectedTimestamp) !== $startDay) {
					$previousSaturday = strtotime('previous ' . $startDay, $selectedTimestamp);
					if (date('m', $previousSaturday) !== $month) {
						$month = date('m', $previousSaturday);
					}
					if (date('Y', $previousSaturday) !== $year) {
						$year = date("Y", $previousSaturday);
					}
					$day = date('j', $previousSaturday);
				} else
					$day = date('j', $selectedTimestamp);

				$viewStart = mktime(0, 0, 0, $month, $day, $year);
				$viewEnd = mktime(0, 0, 0, $month, $day + 6, $year);
				$viewStartPrevious = strtotime("-1 week", $viewStart);
				$viewStartNext = strtotime("+1 day", $viewEnd);
				$heading = date('l F jS - ', $viewStart) . date('jS,', $viewEnd) . date(' Y', $viewStart);
				break;

			case 'day' :
				$day = date("j", $selectedTimestamp);
				$viewStart = mktime(0, 0, 0, $month, $day, $year);
				$viewStartPrevious = strtotime("-1 day", $viewStart);
				$viewStartNext = strtotime("+1 day", $viewStart);
				$viewEnd = mktime(0, 0, 0, $month, $day, $year);
				$heading = date('F jS, Y', $viewStart);
				break;
		}

		Yii::app()->user->setState($sessionVarName, array(
			'view' => $view,
			'selectedDate' => date('Y-m-d', $selectedTimestamp),
			'filterRole' => $roleId
		));

		$preferences = array(
			'view' => $view,
			'viewStart' => $viewStart,
			'viewEnd' => $viewEnd,
			'viewStartPrevious' => $viewStartPrevious,
			'viewStartNext' => $viewStartNext,
			'heading' => $heading,
			'selectedRole' => $roleId,
			'selectedDate' => date('Y-m-d', $selectedTimestamp)
		);
		if ($view == 'month') {
			$preferences['monthStartDate'] = $monthStartDate;
			$preferences['monthEndDate'] = $monthEndDate;
		}
		return $preferences;
	}

	public static function weekDaysVerbose()
	{
		return array(
			'Sunday' => Yii::t('app', 'Sunday'),
			'Monday' => Yii::t('app', 'Monday'),
			'Tuesday' => Yii::t('app', 'Tuesday'),
			'Wednesday' => Yii::t('app', 'Wednesday'),
			'Thursday' => Yii::t('app', 'Thursday'),
			'Friday' => Yii::t('app', 'Friday'),
			'Saturday' => Yii::t('app', 'Saturday'),
		);
	}

	public static function weekDays()
	{
		return array(
			'0' => Yii::t('app', 'Sunday'),
			'1' => Yii::t('app', 'Monday'),
			'2' => Yii::t('app', 'Tuesday'),
			'3' => Yii::t('app', 'Wednesday'),
			'4' => Yii::t('app', 'Thursday'),
			'5' => Yii::t('app', 'Friday'),
			'6' => Yii::t('app', 'Saturday'),
		);
	}

	public static function getDayNumberFromWeekDay($weekDay)
	{
		$arr = array(
			'sunday' => 0,
			'monday' => 1,
			'tuesday' => 2,
			'wednesday' => 3,
			'thursday' => 4,
			'friday' => 5,
			'saturday' => 6,
		);

		return isset($arr[$weekDay]) ? $arr[$weekDay] : FALSE;
	}

	public static function getDayOfWeek($dayNumber)
	{
		$weekDays = self::weekDays();
		return isset($weekDays[$dayNumber]) ? $weekDays[$dayNumber] : $weekDays[1];
	}

	/**
	 * 
	 * @param type $startDayNumber
	 * @param type $startDate
	 * @param type $endDate
	 * @return type
	 * @author Shazia Sadiq
	 */
	public static function getStartEndDays($startDayNumber, $startDate = false, $endDate = false)
	{

		if (!$startDate) {
			$startDate = date('Y-m-d');
		}
		if (!$endDate) {
			$endDate = date('Y-m-d', strtotime('+6 Days'));
		}
		/* $startDay = self::getDayOfWeek($startDayNumber);
		  if (!(date("l", strtotime($startDate)) == $startDay)):
		  $startDate = date('Y-m-d',strtotime('previous ' . $startDay, strtotime($startDate)));
		  else:
		  $startDate = date('Y-m-d',strtotime($startDay, strtotime($startDate)));
		  endif;

		  if(!$endDate){

		  $endDate =date('Y-m-d', strtotime('+6 Days', $startDate));

		  }
		  else{

		  $endDate = strtotime('previous '.$startDay, strtotime($endDate));
		  $endDate = date('Y-m-d',strtotime('+6 Days', $endDate));


		  }

		 */

		$response['startDate'] = $startDate;
		$response['endDate'] = $endDate;


		return $response;
	}

	/**
	 * 
	 * @end
	 */
	public static function getWeekStartEndDays($startDayNumber, $startDate = false)
	{

		if (!$startDate) {
			$startDate = date('Y-m-d');
		}

		$startDay = self::getDayOfWeek($startDayNumber);
		if (!(date("l", strtotime($startDate)) == $startDay)):
			$startDate = strtotime('previous ' . $startDay, strtotime($startDate));
		else:
			$startDate = strtotime($startDay, strtotime($startDate));
		endif;

		$endDate = strtotime('+6 Days', $startDate);

		$response['startDate'] = $startDate;
		$response['endDate'] = $endDate;

		return $response;
	}

	/**
	 * 
	 * @param type $startDayNumber
	 * @param type $startDate
	 * @return start and end dates of month
	 * @autor Shaiza Sadiq
	 */
	public static function getMonthStartEndDays($startDate = false)
	{

		if (!$startDate) {
			$startDate = date('Y-m-01');
		}
		$startDate = date('Y-m-01', strtotime($startDate));
		$endDate = date('Y-m-t', strtotime($startDate));
		$response['startDate'] = $startDate;
		$response['endDate'] = $endDate;
		return $response;
	}

	/**
	 * 
	 * @end
	 */
	public static function returnDayofDate($date, $day = 1)
	{
		$time = strtotime($date);

		if (date('N', $time) == $day)
			return date('Y-m-d', strtotime($date));

		if ($time) {
			$dayofdate = date("N", $time);
			$dayofdate = ($dayofdate == 7) ? 0 : $dayofdate;
			$datediff = $dayofdate - $day;

			return ($datediff > 0) ? date('Y-m-d', strtotime("-" . $datediff . " day", $time)) :
				date('Y-m-d', strtotime("+" . intval(($datediff < 0) ? $datediff * -1 : $datediff) . " day", $time));
		} else {
			return false;
		}
	}

	public static function getHourSelect()
	{
		static $hoursSelect;

		if ($hoursSelect == null) {
			$m = ' AM';
			for ($i = 0; $i < 24; $i++) {
				if ($i === 0) {
					$t = 12;
				} else if ($i >= 12) {
					$t = $i === 12 ? $i : $i % 12;
					$m = ' PM';
				} else {
					$t = $i;
				}

				$v = $i < 10 ? '0' . $i : $i;

				$hoursSelect[$v . ':00:00'] = $t . ':00' . $m;
				$hoursSelect[$v . ':15:00'] = $t . ':15' . $m;
				$hoursSelect[$v . ':30:00'] = $t . ':30' . $m;
				$hoursSelect[$v . ':45:00'] = $t . ':45' . $m;
			}
		}

		return $hoursSelect;
	}

	public static function getWeekDaysList($startDate)
	{
		$weekDays = array();

		$weekDays[] = date('Y-m-d', $startDate);
		for ($i = 1; $i < 7; $i++) {

			$weekDays[] = date('Y-m-d', strtotime('next day', strtotime($weekDays[$i - 1])));
		}

		return $weekDays;
	}
	
	public static function getWeekNumbersByStoreStartDay($weekDates){
		$weekNumbers = []; $start = 0;
		foreach ($weekDates as $_date){
			$weekNumbers[Carbon::parse($_date)->format('l')] = $start++;
		}
		return $weekNumbers;
	}

	/**
	 * 
	 * @param type $startDate
	 * @return type
	 */
	public static function getMonthDaysList($start, $end)
	{
		$_start = new DateTime($start);
		$_end = new DateTime($end);
		$dateDifference = $_start->diff($_end);
		$max = (int) $dateDifference->format('%R%adays');
		$monthDays = array();
		$monthDays[] = date('Y-m-d', strtotime($start));
		for ($i = 1; $i < $max + 1; $i++) {

			$monthDays[] = date('Y-m-d', strtotime('next day', strtotime($monthDays[$i - 1])));
		}
		return $monthDays;
	}

	public static function getDaysList($startDate, $endDate)
	{

		$startDate = new DateTime($startDate);
		$endDate = new DateTime($endDate);
		echo($startDate);
		die();
		$dateDifference = $startDate->diff($endDate);
		$max = (int) $dateDifference->format('%R%adays');
		$monthDays = array();
		$monthDays[] = date('Y-m-d', strtotime($startdate));
		for ($i = 1; $i < $max + 1; $i++) {

			$monthDays[] = date('Y-m-d', strtotime('next day', strtotime($monthDays[$i - 1])));
		}
		return $monthDays;
	}

	/**
	 * 
	  @end
	 */
	public static function getStoreWeekDateInfo($startDate)
	{

		$storeWeekStartEndDate = CalendarHelper::getWeekStartEndDays((int) $this->store->start_day, $startDate);
		$weekDays = CalendarHelper::getWeekDaysList($storeWeekStartEndDate['startDate']);

		return array(
			'startDate' => date('Y-m-d', $storeWeekStartEndDate['startDate']),
			'endDate' => date('Y-m-d', $storeWeekStartEndDate['endDate']),
			'weekDays' => $weekDays,
			'heading' => date('F jS - ', strtotime($startDate)) . date('jS,', strtotime(date('Y-m-d', $storeWeekStartEndDate['endDate']))) . date(' Y', strtotime(date('Y-m-d', $storeWeekStartEndDate['startDate']))),
		);
	}

	public static function getDatesListBetween($startdate, $enddate)
	{
		$startDate = new DateTime($startdate);
		$endDate = new DateTime($enddate);
		$dateDifference = $startDate->diff($endDate);
		$max = (int) $dateDifference->format('%R%adays');
		$weekDays = array();
		$weekDays[] = date('Y-m-d', strtotime($startdate));
		for ($i = 1; $i < $max + 1; $i++) {

			$weekDays[] = date('Y-m-d', strtotime('next day', strtotime($weekDays[$i - 1])));
		}
		return $weekDays;
	}

	public static function weeksInYear()
	{
		return date("W", mktime(0, 0, 0, 12, 28, date('Y', time())));
	}

	public static function weeksInMonth()
	{
		$month = date('m', time());
		$year = date('Y', time());
		$lastday = date("t", mktime(0, 0, 0, $month, 1, $year));
		$no_of_weeks = 0;
		$count_weeks = 0;
		while ($no_of_weeks < $lastday) {
			$no_of_weeks += 7;
			$count_weeks++;
		}
		return $count_weeks;
	}

	public static function numberOfWeeksBetween($startdate, $enddate)
	{
		$startDate = new DateTime($startdate . "T00:00:00");
		$endDate = new DateTime($enddate . "T23:59:59");
		return ($startDate->diff($endDate)->days + 1) / 7;
	}

	public static function dateDiffInWeeks($start, $end)
	{
		if ($start > $end) {
			return self::dateDiffInWeeks($end, $start);
		}
		$first = DateTime::createFromFormat('Y-m-d', $start);
		$second = DateTime::createFromFormat('Y-m-d', $end);
		return ceil($first->diff($second)->days / 7);
	}

	public static function getWeekIntervals($startDay, $startDate, $endDate)
	{
		$weekIntervals = array();
		$week = self::getWeekStartEndDays($startDay, $startDate);

		while (date('Y-m-d', $week['startDate']) <= $endDate) {
			array_push($weekIntervals, $week);
			$week = self::getWeekStartEndDays($startDay, date('Y-m-d', strtotime('+1 day', $week['endDate'])));
		}

		$weekIntervals[0]['startDate'] = strtotime($startDate);
		$weekIntervals[count($weekIntervals) - 1]['endDate'] = strtotime($endDate);


		return $weekIntervals;
	}

	public static function getPayPeriodIntervals($startDay, $startDate, $endDate)
	{
		$weekIntervals = array();
		$intervalStart = Carbon::parse($startDate);
		$intervalEnd = Carbon::parse($endDate);

		$startWeek = self::getWeekStartEndDays($startDay, $intervalStart->toDateString());
		$endWeek = self::getWeekStartEndDays($startDay, $intervalEnd->toDateString());
		$startOfWeek = Carbon::createFromTimestamp($startWeek['startDate']);
		$endOfWeek = Carbon::createFromTimestamp($endWeek['endDate']);

		while ($startOfWeek <= $endOfWeek) {
			$weekIntervals[] = $startWeek;
			$startOfWeek->addWeek();
			$startWeek = self::getWeekStartEndDays($startDay, $startOfWeek->toDateString());
		}

		return $weekIntervals;
	}

	public static function getDiffInDays($start, $end, $signed = false)
	{
		$first = DateTime::createFromFormat('Y-m-d', $start);
		$second = DateTime::createFromFormat('Y-m-d', $end);

		if ($signed === true && $first > $second) {
			return -abs($first->diff($second)->days);
		}
		return $first->diff($second)->days;
	}

	public static function getWeekDayDatesInMonth($startDayNumber = 0, $date = null)
	{
		$weekDayDatesInMonth = array();
		$weekStartEnd = self::getWeekStartEndDays($startDayNumber, $date);
		$weekDates = CalendarHelper::getWeekDaysList($weekStartEnd['startDate']);
		
		foreach($weekDates as $_date){
			$weekDayDatesInMonth[date('l', strtotime($_date))] = array();
		}
		
		if (!$date) {
			$date = date('Y-m-d');
		}
		
		$monthInterval = self::getMonthStartEndDays($date);
		$monthDates = self::getMonthDaysList($monthInterval['startDate'], $monthInterval['endDate']);

		foreach ($monthDates as $date) {
			switch (date('l', strtotime($date))) {
				case "Monday":
					$weekDayDatesInMonth['Monday'][] = $date;
					break;
				case "Tuesday":
					$weekDayDatesInMonth['Tuesday'][] = $date;
					break;
				case "Wednesday":
					$weekDayDatesInMonth['Wednesday'][] = $date;
					break;
				case "Thursday":
					$weekDayDatesInMonth['Thursday'][] = $date;
					break;
				case "Friday":
					$weekDayDatesInMonth['Friday'][] = $date;
					break;
				case "Saturday":
					$weekDayDatesInMonth['Saturday'][] = $date;
					break;
				case "Sunday":
					$weekDayDatesInMonth['Sunday'][] = $date;
					break;
			}
		}
		
		return $weekDayDatesInMonth;
	}
	
	public static function getDateOfDayBetween($datesList, $day)
	{
		foreach ($datesList as $_date) {
			if (strtolower(date('l', strtotime($_date))) === strtolower($day)) {
				return $_date;
			}
		}
	}

	public static function validateFormat($date, $format = "Y-m-d")
	{
		try {
			$d = Carbon::createFromFormat($format, $date);
			return $d && $d->format($format) == $date;
		} catch (InvalidArgumentException $e) {
			return false;
		}
	}

}

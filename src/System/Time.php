<?php

namespace simserver\System;

class Time {

	public static function setTimeZone(String $timezone = "GMT"): String {
		date_default_timezone_set($timezone);
		return date_default_timezone_get();
	}

	public static function DateRFC2822(?int $timestamp = null): String {
		$timestamp = $timestamp ?? time();
 		return sprintf("%s%s", substr(date(DATE_RFC2822, $timestamp), 0, -5), date("T"));
	}

};

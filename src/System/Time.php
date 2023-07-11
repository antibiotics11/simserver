<?php

namespace simserver\System;

class Time {

	public static function setTimeZone(String $timezone = "GMT"): String {
		date_default_timezone_set($timezone);
		return date_default_timezone_get();
	}

	public static function DateYMD(String $separator = "-", ?int $timestamp = null): String {
		$timestamp ??= time();
		return date(sprintf("Y%sm%sd", $separator, $separator), $timestamp);
	}

	public static function DateRFC2822(?int $timestamp = null): String {
		$timestamp ??= time();
 		return sprintf("%s%s", substr(date(DATE_RFC2822, $timestamp), 0, -5), date("T"));
	}

};

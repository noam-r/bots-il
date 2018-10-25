<?php

namespace Twitter;

use BotsIL\Output;

class AppStatus extends API {

	static $url='1.1/application/rate_limit_status.json?resources=help,users,search,statuses';

	public static function process() {
		$data = self::get();
		$limits = self::parse($data);
		Output::showLimits($limits);
	}

	public static function get() {
		$data = self::call(self::$url, []);
		return \GuzzleHttp\json_decode($data, true);
	}

	public static function parse($response) {
		$limits =[];
		foreach($response['resources'] as $section=>$data) {
			$limits[$section]=[];
			foreach ($data as $type => $limit) {
				$limits[$section][$type] = $limit;
			}
		}
		return $limits;
	}

}
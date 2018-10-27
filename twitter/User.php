<?php

namespace Twitter;

use BotsIL\CLI;

class User extends API {

	static $url='1.1/users/show.json';

	public static function get($username) {
		try {
			$data = self::call(self::$url, ['screen_name' => $username]);
			return \GuzzleHttp\json_decode($data, true);
		} catch (\Exception $e) {
			CLI::endScript("Error retreiving username ".$username);
		}

	}
}
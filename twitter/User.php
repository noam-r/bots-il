<?php

namespace Twitter;

class User extends API {

	static $url='1.1/users/show.json';

	public static function get($username) {
		$data = self::call(self::$url, ['screen_name'=>$username]);
		return \GuzzleHttp\json_decode($data, true);
	}
}
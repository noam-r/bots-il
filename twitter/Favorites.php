<?php

namespace Twitter;

class Favorites extends API {

	static $url='1.1/favorites/list.json';

	public static function get($username, $count=200) {
		$data = self::call(self::$url, ['screen_name'=>$username, 'count'=>$count]);
		return \GuzzleHttp\json_decode($data, true);
	}

	public static function getUsers($tweets) {
		$users =[];
		foreach($tweets as $tweet) {
			$user = $tweet['user']['screen_name'];
			if (false === isset($users[$user])) $users[$user]=0;
			$users[$user]++;
		}
		arsort($users, SORT_NUMERIC);
		return $users;
	}

}
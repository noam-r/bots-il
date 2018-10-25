<?php

namespace Twitter;

use BotsIL\CLI;

class Token extends API {

	public static function getToken() {
		$token='';
		try {
			$token = self::call('oauth2/token', 'grant_type=client_credentials', 'POST');
		} catch (\Exception $e) {
			CLI::critical($e->getMessage());
		}
		$tokenObj = \GuzzleHttp\json_decode($token);
		if (isset($tokenObj->access_token)) {
			CLI::info("Great Success!");
			CLI::info("Your token is: ".$tokenObj->access_token);
			CLI::info("Now put it in your .env file to continue");
		} else throw new \Exception("could not get auth token");
	}
}


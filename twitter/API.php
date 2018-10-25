<?php

namespace Twitter;

use BotsIL\CLI;
use GuzzleHttp;

class API {

	static $domain = 'https://api.twitter.com/';

	public static function call($url, $params, $method='GET') {

		if (isset($_ENV['TWITTER_TOKEN']) && !empty($_ENV['TWITTER_TOKEN'])) $auth = 'Bearer '.$_ENV['TWITTER_TOKEN'];
			elseif ($url =='oauth2/token') $auth = self::getBearer();
			else CLI::endScript("No Twitter token defined");

		$client = new GuzzleHttp\Client();
		$options = [
			'headers'=>[
				'Authorization'=>$auth,
				'Content-Type'=>'application/x-www-form-urlencoded;charset=UTF-8'
			],
			'timeout' => 10
		];
		switch($method) {
			case 'POST':
				$options['body']=$params;
				break;
			case 'GET':
				$url.="?".http_build_query($params);
				break;
		}
//echo "calling ".self::$domain.$url."\n";
		$response = $client->request($method, self::$domain.$url, $options);
//echo "got response\n";
	//	var_dump($response);
//echo "statuscode = ".$response->getStatusCode()."\n";
//echo "response = ".$response->getBody()."\n";
		if ($response->getStatusCode()==200) return $response->getBody();
			else throw new \Exception('response error from twitter');
	}

	private static function getBearer() {
		if (!isset($_ENV['CONSUMER_KEY']) || empty($_ENV['CONSUMER_KEY']) || !isset($_ENV['CONSUMER_SECRET']) || empty($_ENV['CONSUMER_SECRET']))
			CLI::endScript("check your configuration .env file");
		return 'Basic '.base64_encode($_ENV['CONSUMER_KEY'].":".$_ENV['CONSUMER_SECRET']);
	}


}
<?php

namespace Twitter;

use BotsIL\CLI;

class Timeline extends API {

	static $url='1.1/statuses/user_timeline.json';
	static $maxTotalTweets = 1000;
	static $tweetsPerIteration = 200;

	private static $weekDays=['', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

	public static function get($username) {
		$tweets = [];
		$iterations = 0;
		$earliestID=null;
		echo CLI::prepare("Getting tweets", CLI::C_GREEN, false);
		while ($iterations < (self::$maxTotalTweets/self::$tweetsPerIteration)) {
			$batch = self::getFromTwitter($username, $earliestID);
			$progress = round((count($tweets)+count($batch))/self::$maxTotalTweets*100);
			echo CLI::prepare("...".$progress."%", CLI::C_GREEN, false);
			if (empty($batch)) break;
			$earliestID = self::getOldestID($batch);
			$tweets = array_merge($tweets, $batch);
			if (count($tweets) >= self::$maxTotalTweets) break;
			if (count($batch) < self::$tweetsPerIteration) break;
			$iterations++;
		}
		CLI::newLine();
		CLI::info("Parsing total ".count($tweets)." tweets");
		return $tweets;
	}

	public static function parse($timelineObj) {
		$retweetsAndMentions = self::getRetweetsAndMentions($timelineObj);
		return [
			'total'=>count($timelineObj),
			'retweets'=>$retweetsAndMentions['retweets'],
			'mentions'=>$retweetsAndMentions['mentions'],
			'sources'=>self::getSources($timelineObj),
			'languages'=>self::getLanguages($timelineObj),
			'weekdays'=>self::getWeekdays($timelineObj),
			'times'=>self::getTimes($timelineObj),
			'firstDaysAgo'=>self::getFirstTweetDaysAgo($timelineObj)
		];
	}

	private static function getFromTwitter($username, $oldestID=null) {
		try {
			$data = self::call(self::$url, ['screen_name' => trim($username), 'count' => self::$tweetsPerIteration, 'max_id'=>$oldestID]);
		} catch (\Exception $e) {
			\BotsIL\CLI::endScript("Could not get tweets, error: ".$e->getMessage());
		}
		return \GuzzleHttp\json_decode($data, true);
	}

	private static function getRetweetsAndMentions($timelineObj) {
		$retweets=$mentions=[];
		$tweets=self::getItems($timelineObj, 'text');
		foreach ($tweets as $tweet) {
			preg_match_all("/[@]+([a-zA-Z0-9_]+)/",$tweet, $matches);
			if (empty($matches[0])) continue;
			if (substr($tweet, 0, 4)=='RT @') {
				if (!isset($retweets[$matches[0][0]])) $retweets[$matches[0][0]]=1;
				else $retweets[$matches[0][0]]++;
				unset ($matches[0][0]);
			}
			if (empty($matches[0])) continue;
			foreach ($matches[0] as $mention) {
				if (!isset($mentions[$mention])) $mentions[$mention]=1;
				else $mentions[$mention]++;
			}
		}
		return ['retweets'=>$retweets, 'mentions'=>$mentions];
	}

	private static function getSources($timelineObj) {
		return self::getItemList($timelineObj,'source');
	}

	private static function getLanguages($timelineObj) {
		return self::getItemList($timelineObj,'lang');
	}

	private static function getWeekdays($timelineObj) {
		$days=[];
		$times = self::getItems($timelineObj, 'created_at');
		foreach ($times as $time) {
			$dateObj = strtotime($time);
			$day=date('N', $dateObj);
			if (false === isset($days[$day])) $days[$day]=0;
			$days[$day]++;
		}
		ksort($days);
		foreach ($days as $key=>$val) {
			$days[self::$weekDays[$key]]=$val;
			unset($days[$key]);
		}
		return $days;
	}

	private static function getTimes($timelineObj) {
		$hours=[];
		$times = self::getItems($timelineObj, 'created_at');
		foreach ($times as $time) {
			$dateObj = strtotime($time);
			$hour=date('H', $dateObj);
			if (false === isset($hours[$hour])) $hours[$hour]=0;
			$hours[$hour]++;
		}
		ksort($hours);
		return $hours;
	}

	private static function getItemList($timelineObj, $key) {
		$items=[];
		foreach ($timelineObj as $item) {
			$val=strip_tags($item[$key]);
			if (false === isset($items[$val])) $items[$val]=0;
			$items[$val]++;
		}
		return $items;
	}

	private static function getItems($timelineObj, $key) {
		$items=[];
		foreach ($timelineObj as $item) {
			$items[$item['id']]=$item[$key];
		}
		return $items;
	}

	private static function getFirstTweetDaysAgo($timelineObj) {
		$earliest = new \DateTime();
		foreach ($timelineObj as $item) {
			$tweetDate=new \DateTime($item['created_at']);
			if ($tweetDate < $earliest) $earliest=$tweetDate;
		}
		$now = new \DateTime();
		return $now->diff($earliest)->days;
	}

	private static function getOldestID($timelineObj) {
		$earliestID=null;
		foreach($timelineObj as $item) {
			if ($earliestID==null) $earliestID=$item['id'];
				elseif ($item['id'] < $earliestID) $earliestID=$item['id'];
		}
		return $earliestID;
	}

}
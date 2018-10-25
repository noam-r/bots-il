<?php

namespace Twitter;

class Timeline extends API {

	static $url='1.1/statuses/user_timeline.json';

	private static $weekDays=['', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

	public static function get($username) {
		$data = self::call(self::$url, ['screen_name'=>trim($username), 'count'=>200]);
		return \GuzzleHttp\json_decode($data, true);
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
			'times'=>self::getTimes($timelineObj)
		];
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

}
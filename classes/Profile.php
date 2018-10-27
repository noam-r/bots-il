<?php

namespace BotsIL;

use GetOpt\GetOpt;
use Twitter\Favorites;
use Twitter\Timeline;
use Twitter\User;

class Profile {

	public static function get(GetOpt $options) {
		$username = trim($options->getOperand(0));
		if (empty($username)) CLI::endScript("no username provided");
		$userObj = User::get($username);
		Output::user($userObj);
		$timeline = Timeline::get($username);
		$timelineData=Timeline::parse($timeline);
		$favedTweets =Favorites::get($username, 200);
		$favedUsers = Favorites::getUsers($favedTweets);
		Output::sources($timelineData['sources'], $timelineData['total']);
		Output::languages($timelineData['languages'], $timelineData['total'], $userObj['lang']);
		Output::tweetsPer($timelineData['firstDaysAgo'], $timelineData['total'], $userObj['created_at'], $userObj['statuses_count']);
		Output::places($timelineData['places'], $timelineData['total']);
		Output::hours($timelineData['times']);
		Output::weekdays($timelineData['weekdays']);
		Output::retweets($timelineData['retweets'], $timelineData['total']);
		Output::mentions($timelineData['mentions'], $timelineData['total']);
		Output::favoritedUsers($favedUsers, count($favedTweets), 7);
	}

	public static function favs(GetOpt $options) {
		$username = trim($options->getOperand(0));
		if (empty($username)) CLI::endScript("No username provided");
		$tweets = Favorites::get($username);
		$favedUsers = Favorites::getUsers($tweets);
		Output::favoritedUsers($favedUsers, count($tweets));
	}

}
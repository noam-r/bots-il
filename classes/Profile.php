<?php

namespace BotsIL;

use GetOpt\GetOpt;
use Twitter\Favorites;
use Twitter\Timeline;
use Twitter\User;

class Profile {

	public static function get(GetOpt $options) {
		$username = trim($options->getOperand(0));
		$userObj = User::get($username);
		Output::user($userObj);
		$timeline = Timeline::get($username);
		$timelineData=Timeline::parse($timeline);
		Output::sources($timelineData['sources'], $timelineData['total']);
		Output::languages($timelineData['languages'], $timelineData['total'], $userObj['lang']);
		Output::hours($timelineData['times']);
		Output::weekdays($timelineData['weekdays']);
		Output::retweets($timelineData['retweets'], $timelineData['total']);
		Output::mentions($timelineData['mentions'], $timelineData['total']);
	}

	public static function favs(GetOpt $options) {
		$username = trim($options->getOperand(0));
		if (empty($username)) CLI::endScript("No username provided");
		$tweets = Favorites::get($username);
		$favedUsers = Favorites::getUsers($tweets);
		Output::favoritedUsers($favedUsers, count($tweets));
	}

}
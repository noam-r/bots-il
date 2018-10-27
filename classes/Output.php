<?php

namespace BotsIL;

class Output {

	public static function user($userObj) {
		$tableColumns = [20, 30, 20, 30];

		CLI::out('Profile Summary', CLI::C_BROWN);
		echo CLI_Table::Row([
			$tableColumns,
			[CLI::caption('Username'), $userObj['screen_name'], CLI::caption('User ID'), $userObj['id_str']]
		], true);
		$created = date('M Y', strtotime($userObj['created_at'])).' ('.round((time()-strtotime($userObj['created_at']))/(3600*24*365.25)).' year(s) ago)';
		echo CLI_Table::Row([
			$tableColumns,
			[CLI::caption('Name'), $userObj['name'], CLI::caption('Created'), $created]
		]);
		echo CLI_Table::Row([
			$tableColumns,
			[CLI::caption('Followers'), $userObj['followers_count'], CLI::caption('Following'), $userObj['friends_count']]
		]);
		if ($userObj['followers_count']==0 || $userObj['friends_count']==0) $ffRatio="N/A";
		else $ffRatio = (round(((int)$userObj['followers_count']/(int)$userObj['friends_count'])*100)).'%';
		echo CLI_Table::Row([
			$tableColumns,
			[CLI::caption('F/F Ratio'), $ffRatio, CLI::caption('Tweets'), $userObj['statuses_count']]
		]);
	}

	public static function sources($sources, $total) {
		$tableColumns = [30, 70];
		CLI::newLine();
		CLI::out('Sources', CLI::C_BROWN);
		foreach ($sources as $source=>$value) {
			echo CLI_Table::Row([
				$tableColumns,
				[CLI::caption($source), $value.' ('.(round(($value/$total)*100)).'%)']
			]);
		}
	}

	public static function languages($languages, $total, $interfaceLanguage) {
		$tableColumns = [30, 70];
		CLI::newLine();
		$caption = CLI::prepare('Languages (interface language is ', CLI::C_BROWN, false);
		$caption .= CLI::prepare($interfaceLanguage, CLI::C_RED, false);
		$caption .= CLI::prepare(')', CLI::C_BROWN);
		echo $caption;
		foreach ($languages as $language=>$value) {
			echo CLI_Table::Row([
				$tableColumns,
				[CLI::caption($language), $value.' ('.(round(($value/$total)*100)).'%)']
			]);
		}
	}

	public static function tweetsPer($daysAgo, $total, $accountCreated, $totalTweets) {
		$tableColumns = [40, 60];
		$now = new \DateTime();
		CLI::newLine();
		CLI::out('Average tweets per', CLI::C_BROWN);
		$createdAgo = $now->diff(new \DateTime($accountCreated))->days;
		echo CLI_Table::Row([
			$tableColumns,
			[CLI::caption('Lifetime avg. (total '.$createdAgo.' days)'), round($totalTweets / $createdAgo, 2).' per day']
		]);
		echo CLI_Table::Row([
			$tableColumns,
			[CLI::caption('Last '.$total.' tweets avg.'), round($total/ $daysAgo, 2).' per day']
		]);

	}

	public static function hours($hours) {
		CLI::newLine();
		CLI::out('Operating Hours', CLI::C_BROWN);
		CLI_Graph::drawBars($hours);
	}

	public static function weekdays($weekdays) {
		CLI::newLine();
		CLI::out('Weekdays', CLI::C_BROWN);
		CLI_Graph::drawBars($weekdays);
	}

	public static function retweets($retweets, $total, $top=7) {
		CLI::newLine();
		$caption = CLI::prepare('Retweets: ratio is ', CLI::C_BROWN, false);
		$caption .= CLI::prepare(round((array_sum($retweets)/$total)*100).'%', CLI::C_RED, false);
		$caption .= CLI::prepare(' retweets and ', CLI::C_BROWN, false);
		$caption .= CLI::prepare(100-round((array_sum($retweets)/$total)*100).'%', CLI::C_RED, false);
		$caption .= CLI::prepare(' original content ; showing top '.$top.' retweeted users', CLI::C_BROWN);
		echo $caption;
		$tableColumns = [30, 70];
		arsort($retweets, SORT_NUMERIC);
		$index=0;
		foreach ($retweets as $retweet=>$value) {
			echo CLI_Table::Row([
				$tableColumns,
				[CLI::caption($retweet), $value.' ('.(round(($value/$total)*100)).'%)']
			]);
			if (++$index==$top) break;
		}
	}

	public static function mentions($mentions, $total, $top=7) {
			CLI::newLine();
			$caption = CLI::prepare('Mentions ; showing top  '.$top, CLI::C_BROWN);
			echo $caption;
			$tableColumns = [30, 70];
			arsort($mentions, SORT_NUMERIC);
			$index=0;
			foreach ($mentions as $mention=>$value) {
				echo CLI_Table::Row([
					$tableColumns,
					[CLI::caption($mention), $value.' ('.(round(($value/$total)*100)).'%)']
				]);
				if (++$index==$top) break;
			}

	}

	public static function favoritedUsers($favs, $total, $top=50) {
		CLI::newLine();
		echo CLI::prepare('Favorited Users (last '.$total.') ; showing top  '.$top.' favorited users', CLI::C_BROWN);
		$tableColumns = [30, 70];
		$index=0;
		foreach ($favs as $user=>$value) {
			echo CLI_Table::Row([
				$tableColumns,
				[CLI::caption($user), $value.' ('.(round(($value/$total)*100)).'%)']
			]);
			if (++$index==$top) break;
		}

	}

	public static function showLimits($limits) {
		$columns=[40, 20, 40];
		CLI::newLine();
		echo CLI::prepare('Current Usage', CLI::C_BROWN);
		echo CLI_Table::Row([
			$columns,
			[CLI::caption('Type'), CLI::caption('Limit'), CLI::caption('Remaining')]
		]);
		foreach ($limits as $section=>$data) {
			foreach ($data as $type=>$limits) {
				echo CLI_Table::Row([
					$columns,
					[CLI::caption($type), $limits['limit'], $limits['remaining']]
				]);
			}
		}
	}

}
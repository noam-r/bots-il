<?php

namespace BotsIL;

class CLI_Graph extends CLI{

	private static $width=100;

	private static $fillEmpty=true;

	private static $blockChar='█';

	public static function drawBars($array) {

		$max = max($array);

		foreach ($array as $key=>$val) {
			$valMax=self::$width/$max;
			$calcWidth=self::$width-(self::$width-($val*$valMax));
			$bar = str_repeat(self::$blockChar, floor($calcWidth));

			echo CLI_Table::Row([
				[10,80,10],
				[$key, $bar, $val]
			]);
		}






	}

}
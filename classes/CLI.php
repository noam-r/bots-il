<?php

namespace BotsIL;

class CLI {

	const C_RESET = 'reset';
	const C_BLACK = 'black';
	const C_DARKGRAY = 'darkgray';
	const C_BLUE = 'blue';
	const C_LIGHTBLUE = 'lightblue';
	const C_GREEN = 'green';
	const C_LIGHTGREEN = 'lightgreen';
	const C_CYAN = 'cyan';
	const C_LIGHTCYAN = 'lightcyan';
	const C_RED = 'red';
	const C_LIGHTRED = 'lightred';
	const C_PURPLE = 'purple';
	const C_LIGHTPURPLE = 'lightpurple';
	const C_BROWN = 'brown';
	const C_YELLOW = 'yellow';
	const C_LIGHTGRAY = 'lightgray';
	const C_WHITE = 'white';

	/** @var array known color names */
	static $colors = array(
		self::C_RESET => "\33[0m",
		self::C_BLACK => "\33[0;30m",
		self::C_DARKGRAY => "\33[1;30m",
		self::C_BLUE => "\33[0;34m",
		self::C_LIGHTBLUE => "\33[1;34m",
		self::C_GREEN => "\33[0;32m",
		self::C_LIGHTGREEN => "\33[1;32m",
		self::C_CYAN => "\33[0;36m",
		self::C_LIGHTCYAN => "\33[1;36m",
		self::C_RED => "\33[0;31m",
		self::C_LIGHTRED => "\33[1;31m",
		self::C_PURPLE => "\33[0;35m",
		self::C_LIGHTPURPLE => "\33[1;35m",
		self::C_BROWN => "\33[0;33m",
		self::C_YELLOW => "\33[1;33m",
		self::C_LIGHTGRAY => "\33[0;37m",
		self::C_WHITE => "\33[1;37m",
	);

	public static function critical($message) {
		echo self::color($message,self::C_RED).PHP_EOL;
	}

	public static function info($message) {
		echo self::color($message, self::C_YELLOW).PHP_EOL;
	}

	public static function out($message, $color) {
		echo self::color($message, $color).PHP_EOL;
	}

	public static function prepare($message, $color, $newline=true) {
		$str=self::color($message, $color);
		if ($newline) $str.=PHP_EOL;
		return $str;
	}

	public static function caption($message) {
		return self::color($message, self::C_LIGHTBLUE);
	}

	public static function newLine() {
		echo PHP_EOL;
	}

	public static function endScript($message) {
		self::critical($message);
		exit;
	}

	private static function color($message, $color) {
		return self::$colors[$color].$message.self::$colors[self::C_RESET];
	}

}
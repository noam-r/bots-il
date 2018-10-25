<?php

namespace BotsIL;

class CLI_Table extends CLI {

	private static $width=130;

	/***
	 *  $rowArr is an array that consists of 2 arrays:
	 *      columns: [30, 30, 40]
	 *         each item in the columns array is a width of a column in percentage
	 *      content: ['a', 'b', 'c']
	 *         echo item in the content array in the content of the row
	 */
	public static function Row($rowArr, $withLine=false) {
		//TODO: validate input
		//TODO: break lines (or use ellipsis) to fit columns
		$output='';
		if ($withLine) $output.=str_repeat('-', self::$width).PHP_EOL;
		foreach ($rowArr[1] as $key=>$val) {
			$colWidth = self::calculateWidth($rowArr[0][$key]);
			//echo "val = ".$val.' ; width='.$colWidth."\n";
			$output.=self::pad_right($val, $colWidth)."|";
		}
		return $output.PHP_EOL;
	}

	private static function calculateWidth($percentage) {
		//return round(($percentage/self::$width)*100);
		return round(self::$width*($percentage/100));
	}

	//this function is here because damn str_pad doesn't work with multibyte chars
	private static function pad_right($str, $length, $padChar=' ') {
		$padLength = $length - mb_strlen($str);
		//echo 'str='.$str.' ; celllength='.$length.' ; strlen='.mb_strlen($str).' ;padlength='.$padLength."\n";
		if ($padLength<=0) return $str;
		return $str.str_repeat($padChar, $padLength);
	}

}
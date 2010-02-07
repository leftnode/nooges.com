<?php

class Library {
	public static function parseBbc($string) {
		$replace = array(
			'/\[b\](.*)\[\/b\]/i',
			'/\[i\](.*)\[\/i\]/i',
			'/\[u\](.*)\[\/u\]/i',
			'/\[s\](.*)\[\/s\]/i',
			'/\[size=(\d+pt)\](.*)\[\/size\]/i',
			'/\[color=([a-z]+)\](.*)\[\/color\]/i',
			'/\[img](.*)\[img]/i'
		);
		
		$replace_to = array(
			'<strong>$1</strong>',
			'<em>$1</em>',
			'<u>$1</u>',
			'<s>$1</s>',
			'<span style="font-size:$1">$2</span>',
			'<span style="color:$1">$2</span>',
			'<img src="$1" />'
		);
		
		return preg_replace($replace, $replace_to, $string);
	}
	
	public static function removeBbc($string) {
		return preg_replace('/(\[.*\])(.*)(\[\/.*\])/iU', '\\2', $string);
	}
	
	public static function removeHtml($string) {
		return preg_replace('/<.*>(.*)<\/.*>/iU', '\\1', $string);
	}
}
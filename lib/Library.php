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
			'/\[img\](.*)\[\/img\]/i',
			'/\[url=(.+)\](.*)\[\/url\]/iU'
		);
		
		$replace_to = array(
			'<strong>$1</strong>',
			'<em>$1</em>',
			'<u>$1</u>',
			'<s>$1</s>',
			'<span style="font-size:$1">$2</span>',
			'<span style="color:$1">$2</span>',
			'<img src="$1" />',
			'<a href="$1" target="_blank">$2</a>'
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
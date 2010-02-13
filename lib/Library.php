<?php

class Library {
	public static function parseBbc($string) {
		$replace = array(
			'/\[b\](.*)\[\/b\]/iU',
			'/\[i\](.*)\[\/i\]/iU',
			'/\[u\](.*)\[\/u\]/iU',
			'/\[s\](.*)\[\/s\]/iU',
			'/\[size=(\d+pt)\](.*)\[\/size\]/iU',
			'/\[color=([a-z]+)\](.*)\[\/color\]/iU',
			'/\[img\](.*)\[\/img\]/iU',
			'/\[url=(.+)\](.*)\[\/url\]/iU',
			'/\[center\](.*)\[\/center\]/iU'
		);
		
		$replace_to = array(
			'<strong>$1</strong>',
			'<em>$1</em>',
			'<u>$1</u>',
			'<s>$1</s>',
			'<span style="font-size:$1">$2</span>',
			'<span style="color:$1">$2</span>',
			'<img src="$1" />',
			'<a href="$1" target="_blank">$2</a>',
			'<div align="center">$1</div>'
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
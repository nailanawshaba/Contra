<?php

	/// Function for converting unicode characters to UTF8 html entities.
	/// Created as a fix for issue #14. 
	/// Author: Justin Eittreim (DivinityArcane) > eittreim.justin@live.com
	function hexentity($char) {
		/*
		 * 7	U+007F	0xxxxxxx
		 * 11	U+07FF	110xxxxx	10xxxxxx
		 * 16	U+FFFF	1110xxxx	10xxxxxx	10xxxxxx
		 * 21	U+1FFFFF	11110xxx	10xxxxxx	10xxxxxx	10xxxxxx */
		$ords = array(ord($char));
		$bin = decbin(ord($char));
		
		// Ensure the char is holding more than one byte.
		if (!isset($char{1})) {
			if ($ords[0] >= 0 AND 127 >= $ords[0])
				return $char;
			else
				throw new Exception('Invalid unicode characted.');
		} else {
			$ords[1] = ord($char{1});
		}
		
		if (!isset($char{2})) {
			if ($ords[0] >= 192 AND 223 >= $ords[0])
				return '&#'. (64*($ords[0]-192)+($ords[1]-128)) .';';
			else
				throw new Exception('Invalid unicode characted.');
		} else {
			$ords[2] = ord($char{2});
		}
		
		if (!isset($char{3})) {
			if ($ords[0] >= 224 AND 239 >= $ords[0])
				return '&#'. ((4096*($ords[0]-224))+(64*($ords[1]-128))+($ords[2]-128)) .';';
			else
				throw new Exception('Invalid unicode characted.');
		} else {
			$ords[3] = ord($char{3});
			if ($ords[0] >= 240 AND 247 >= $ords[0])
				return '&#'. ((262144*($ords[0]-240))+(4096*($ords[1]-128))+(64*($ords[2]-128))+($ords[3]-128)) .';';
			else
				throw new Exception('Invalid unicode characted.');
		}
		
		// No 5, 6, 7, or 8 byte chars should be supported.
		// As per RFC3629, UTF-8 is no longer allowed to pass 0x1FFFFF
		throw new Exception('Invalid character passed for conversion.');
	}

?>
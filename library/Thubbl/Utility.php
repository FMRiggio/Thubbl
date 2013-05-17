<?php
class Thubbl_Utility
{
	public static function debug($x)
	{
		echo '<pre>';
		print_r($x);
		echo '</pre>';
	}

	public static function random_string($length) {
		$string = "";

		// genera una stringa casuale che ha lunghezza
		// uguale al multiplo di 32 successivo a $length
		for ($i = 0; $i <= ($length/32); $i++) {
			$string .= md5(time()+rand(0,99));
		}

		// indice di partenza limite
		$max_start_index = (32*$i)-$length;

		// seleziona la stringa, utilizzando come indice iniziale
		// un valore tra 0 e $max_start_point
		$random_string = substr($string, rand(0, $max_start_index), $length);

		return $random_string;
	}

	public static function permalink($str, $replace = array(), $delimiter = '-') {
		setlocale(LC_ALL, 'en_US.UTF8');
		if (!empty($replace)) {
			$str = str_replace((array)$replace, ' ', $str);
		}

		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	
		return $clean;
	}
}
?>